<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Site
 *
 * @author Soul_man
 */
class JobCommand extends X3_Command{
    
    public function init() {
        
    }
    
    public function runCheck() {
        $companies = X3::db()->fetchAll("SELECT cs.company_id as id, c.title as title FROM company_settings cs INNER JOIN data_company c ON c.id=cs.company_id WHERE cs.autoload_auto");//Company_Settings::get(array('autoload_auto'));
        $base = X3::app()->basePath;
        if(is_file($base.'/uploads/company.mailer'))
            @unlink($base.'/uploads/company.mailer');        
        foreach($companies as $company){
            echo "===============================\n";
            echo $company['title']."\n";
            echo "===============================\n";
            system("php $base/run.php company autoupdate cid=".$company['id']." accumulate=1");
        }
        if(is_file($base.'/uploads/company.mailer')){
            $mailer = json_decode(file_get_contents($base.'/uploads/company.mailer'),1);
            @unlink($base.'/uploads/company.mailer');
            if(TRUE!==($msg = Notify::sendMail('Company.GeneralReport', array('companies'=>$mailer)))){
                echo "\n===============\n$msg\n======================\n";
                X3::log($msg,'kansha_error');
            }
        }
    }
    
    public function runPrices() {
        /////////////////////////
        //Subscribers sending routine
        /////////////////////////
        if(!isset(X3::app()->global['skipprice'])){
            $prices = X3::db()->query("SELECT * FROM user_price WHERE status=0");
            if(is_resource($prices)){
                while($pr = mysql_fetch_assoc($prices)){
                    $coms = X3::db()->fetchAll("SELECT ci.price,ci.company_id, cc.title company, cc.login, si.title item FROM company_item ci
                        INNER JOIN data_company cc ON cc.id = ci.company_id INNER JOIN shop_item si ON si.id = ci.item_id
                        WHERE ci.item_id={$pr['item_id']} AND ci.price<={$pr['price']} ORDER BY ci.price");
                    if($coms !== false && count($coms)>0){
                        $item = $coms[0]['item'];
                        if(TRUE === ($err=Notify::sendMail('priceNotify', array('title'=>'Снижение цен! Kansha.kz','item'=>$item,'companies'=>$coms,'host'=>'95.57.119.93')))){
                            X3::db()->query("UPDATE user_price SET status=1 WHERE id={$pr['id']}");
                        }else{
                            $msg = "Не удалось отправить сообщение о снижении цены на '{$pr['email']}' `{$err}`";
                            X3::log($msg,'kansha_error');
                            echo $msg . "\r\n";
                        }
                    }
                }
            }
        }
        ///////////////////////////
        //Price actuality check
        ///////////////////////////
        if(!isset(X3::app()->global['skipcompany'])){
            $cmps = X3::db()->query("SELECT * FROM data_company WHERE status AND updated_at>0 AND updated_at<=".(time()-86400*14));
            $out_companies = array();
            $warnin_companies = array();
            if(is_resource($cmps)){
                while($comp = mysql_fetch_assoc($cmps)){
                    $out_companies[] = $comp;
                    if(!empty($comp['email_private']))
                        Notify::sendMail('Company.Deadline',array('company'=>$comp['title']),$comp['email_private']);
                }
                X3::db()->query("UPDATE data_company SET status=0 WHERE status AND updated_at>0 AND updated_at<=".(time()-86400*14));
            }
            $cmps = X3::db()->query("SELECT * FROM data_company dc WHERE status AND updated_at>0 AND dc.updated_at<=".(time()-86400*7));
            if(is_resource($cmps)){
                while($comp = mysql_fetch_assoc($cmps)){
                    $t = (int)date("d",time() - $comp['updated_at'])-1;
                    $time = $t . X3_String::create("")->numeral($t,array(' дня',' дней',' дней'));
                    $comp['time'] = $time;
                    $warnin_companies[$t] = $comp;
                    if(!empty($comp['email_private']))
                        Notify::sendMail('Company.PriceWarning',array('company'=>$comp['title'],'time'=>$time,'last_update'=>date("d.m.Y",$comp['updated_at'])),$comp['email_private']);
                }
                ksort($warnin_companies);
                $warnin_companies = array_reverse($warnin_companies);
            }
            if(!empty($out_companies) || !empty($warnin_companies)){
                Notify::sendMail('Company.NotifyAdmin',array('companies'=>$warnin_companies,'hidden_companies'=>$out_companies));
            }
        }
    }
    
    public function runTest() {
        $mailer = new X3_Mailer();
        $mailer->encoding = 'utf-8';
        $mailer->send('soulman.is.good@gmail.com','Тест',"RAEA ываадлоаыдлваоывлдао");
    }
    
    public function runDump() {
        $d = date("d.m.Y");
        exec('mysqldump -uroot -proot -d kansha_tmp > '.X3::app()->basePath.'/dump/kansha.'.$d.'.sql');
    }
}

?>
