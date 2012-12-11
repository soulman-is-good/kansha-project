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
class JobCommand extends X3_Command {

    public function init() {
        
    }

    public function runCheck() {
        $companies = X3::db()->fetchAll("SELECT cs.company_id as id, c.title as title FROM company_settings cs INNER JOIN data_company c ON c.id=cs.company_id WHERE cs.autoload_auto"); //Company_Settings::get(array('autoload_auto'));
        $base = X3::app()->basePath;
        if (is_file($base . '/uploads/company.mailer'))
            @unlink($base . '/uploads/company.mailer');
        foreach ($companies as $company) {
            echo "===============================\n";
            echo $company['title'] . "\n";
            echo "===============================\n";
            system("php $base/run.php company autoupdate cid=" . $company['id'] . " accumulate=1");
        }
        if (is_file($base . '/uploads/company.mailer')) {
            $mailer = json_decode(file_get_contents($base . '/uploads/company.mailer'), 1);
            @unlink($base . '/uploads/company.mailer');
            if (TRUE !== ($msg = Notify::sendMail('Company.GeneralReport', array('companies' => $mailer)))) {
                echo "\n===============\n$msg\n======================\n";
                X3::log($msg, 'kansha_error');
            }
        }
    }

    public function runPrices() {
        /////////////////////////
        //Subscribers sending routine
        /////////////////////////
        if (!isset(X3::app()->global['skipprice'])) {
            $prices = X3::db()->query("SELECT * FROM user_price WHERE status=0");
            if (is_resource($prices)) {
                while ($pr = mysql_fetch_assoc($prices)) {
                    $coms = X3::db()->fetchAll("SELECT ci.price,ci.company_id, cc.title company, cc.login, si.title item FROM company_item ci
                        INNER JOIN data_company cc ON cc.id = ci.company_id INNER JOIN shop_item si ON si.id = ci.item_id
                        WHERE ci.item_id={$pr['item_id']} AND ci.price<={$pr['price']} ORDER BY ci.price");
                    if ($coms !== false && count($coms) > 0) {
                        $item = $coms[0]['item'];
                        if (TRUE === ($err = Notify::sendMail('priceNotify', array('title' => 'Снижение цен! Kansha.kz', 'item' => $item, 'companies' => $coms, 'host' => '95.57.119.93')))) {
                            X3::db()->query("UPDATE user_price SET status=1 WHERE id={$pr['id']}");
                        } else {
                            $msg = "Не удалось отправить сообщение о снижении цены на '{$pr['email']}' `{$err}`";
                            X3::log($msg, 'kansha_error');
                            echo $msg . "\r\n";
                        }
                    }
                }
            }
        }
        ///////////////////////////
        //Price actuality check
        ///////////////////////////
        if (!isset(X3::app()->global['skipcompany'])) {
            $cmps = X3::db()->query("SELECT * FROM data_company WHERE status AND updated_at>0 AND updated_at<=" . (time() - 86400 * 14));
            $out_companies = array();
            $warnin_companies = array();
            if (is_resource($cmps)) {
                while ($comp = mysql_fetch_assoc($cmps)) {
                    $out_companies[] = $comp;
                    if (!empty($comp['email_private']))
                        Notify::sendMail('Company.Deadline', array('company' => $comp['title']), $comp['email_private']);
                }
                X3::db()->query("UPDATE data_company SET status=0 WHERE status AND updated_at>0 AND updated_at<=" . (time() - 86400 * 14));
            }
            $cmps = X3::db()->query("SELECT * FROM data_company dc WHERE status AND updated_at>0 AND dc.updated_at<=" . (time() - 86400 * 7));
            if (is_resource($cmps)) {
                while ($comp = mysql_fetch_assoc($cmps)) {
                    $t = (int) date("d", time() - $comp['updated_at']) - 1;
                    $time = $t . X3_String::create("")->numeral($t, array(' дня', ' дней', ' дней'));
                    $comp['time'] = $time;
                    $warnin_companies[$t] = $comp;
                    if (!empty($comp['email_private']))
                        Notify::sendMail('Company.PriceWarning', array('company' => $comp['title'], 'time' => $time, 'last_update' => date("d.m.Y", $comp['updated_at'])), $comp['email_private']);
                }
                ksort($warnin_companies);
                $warnin_companies = array_reverse($warnin_companies);
            }
            if (!empty($out_companies) || !empty($warnin_companies)) {
                Notify::sendMail('Company.NotifyAdmin', array('companies' => $warnin_companies, 'hidden_companies' => $out_companies));
            }
        }
        /////////////////////////////
        // Take care of debiters
        /////////////////////////////
        if (!isset(X3::app()->global['skipdebiters'])) {
            for ($type = 0; $type < 3; $type++) {
                $isfree = ($type > 0) ? "isfree$type" : "isfree";
                $cmps = X3::db()->query("SELECT dc.title title, dc.email_private email, cb.ends_at, cb.created_at, document, cb.paysum payment, cb.type FROM company_bill cb INNER JOIN data_company dc ON cb.company_id=dc.id WHERE dc.$isfree=0 AND cb.type=$type AND cb.ends_at<=" . time() . " ORDER BY cb.ends_at DESC LIMIT 1");
                if (is_resource($cmps) && false != ($cmp = mysql_fetch_array($cmps))) {
                    X3::db()->query("UPDATE data_company SET isfree=1");
                    $data = array(
                        'company' => $cmp['title'],
                        'document' => $cmp['document'],
                        'end_date' => date('d.m.Y', $cmp['ends_at']),
                        'pay_date' => date('d.m.Y', $cmp['created_at']),
                        'payment' => $cmp['payment'],
                    );
                    Notify::sendMail('Company.PaymentEnd', $data, $cmp['email']);
                }
            }
            for ($type = 0; $type < 3; $type++) {
                $isfree = ($type > 0) ? "isfree$type" : "isfree";
                $cmps = X3::db()->query("SELECT dc.title title, dc.email_private email, cb.ends_at, cb.created_at, document, cb.paysum payment, cb.type FROM company_bill cb INNER JOIN data_company dc ON cb.company_id=dc.id WHERE dc.$isfree=0 AND cb.type=$type AND cb.ends_at<=" . (time() - 86400 * 7) . " ORDER BY cb.ends_at DESC GROUP BY cb.type LIMIT 1");
                if (is_resource($cmps) && false != ($cmp = mysql_fetch_array($cmps))) {
                    $data = array(
                        'company' => $cmp['title'],
                        'document' => $cmp['document'],
                        'end_date' => date('d.m.Y', $cmp['ends_at']),
                        'pay_date' => date('d.m.Y', $cmp['created_at']),
                        'payment' => $cmp['payment'],
                    );
                    Notify::sendMail('Company.PaymentRequires', $data, $cmp['email']);
                }
            }
        }
    }

    public function runTest() {
        $mailer = new X3_Mailer();
        $mailer->encoding = 'utf-8';
        $mailer->send('soulman.is.good@gmail.com', 'Тест', "RAEA ываадлоаыдлваоывлдао");
    }

    public function runClean() {
        $cis = X3::db()->query("SELECT * FROM company_item ci WHERE (SELECT COUNT(0) FROM company_item ci2 WHERE ci2.item_id=ci.item_id AND ci2.company_id=ci.company_id)>1");
        while ($item = mysql_fetch_assoc($cis)) {
            echo $item['item_id'] . "\r\n";
        }
    }

    public function runDump() {
        $d = date("d.m.Y");
        exec('mysqldump -uroot -proot -d kansha_tmp > ' . X3::app()->basePath . '/dump/kansha.' . $d . '.sql');
    }

    public function runStore() {
        $cis = X3::db()->query("SELECT * FROM company_item");
        $date = date("d_m");
        $fh = fopen(X3::app()->basePath . DIRECTORY_SEPARATOR . "application" .
                DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "company_item.$date.sql", "wb");
        if ($fh === FALSE || !is_resource($cis))
            die('Error initializing job. ' . X3::db()->getErrors());
        $qkeys = X3::db()->query("SHOW COLUMNS FROM company_item");
        $keys = array();
        while ($key = mysql_fetch_assoc($qkeys))
            $keys[] = $key['Field'];
        $keys = '(`' . implode('`, `', $keys) . '`)';
        $count = mysql_num_rows($cis);
        $kk = 0;
        echo "\r\n";
        fwrite($fh, "TRUNCATE company_item;\r\n");
        while ($ci = mysql_fetch_assoc($cis)) {
            $percent = sprintf("%.02f", ($kk / $count) * 100);
            if ($kk++ > 0)
                echo str_repeat("\x08", strlen("$percent%"));
            echo "$percent%";
            $ci = array_map(function($item) {
                        return mysql_real_escape_string($item);
                    }, $ci);
            fwrite($fh, "INSERT INTO company_item $keys VALUES ('" . implode("', '", $ci) . "')\r\n");
        }
        fclose($fh);
        echo "\nOK!\n\n";
    }

    public function runRestore() {
        if (!isset(X3::app()->global['table']) || !isset(X3::app()->global['date']))
            exit;
        $table = X3::app()->global['table'];
        $date = X3::app()->global['date'];
        $file = X3::app()->basePath . DIRECTORY_SEPARATOR . "application" .
                DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "$table.$date.sql";
        if (!is_file($file))
            exit;
        $sqls = file($file, FILE_SKIP_EMPTY_LINES);
        $count = count($sqls);
        $status = X3::app()->basePath . DIRECTORY_SEPARATOR . "application" .
                DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "restore.$table.status";
        foreach ($sqls as $sql) {
            $percent = sprintf("%.02f", ($kk / $count) * 100);
            if ($kk++ > 0)
                echo str_repeat("\x08", strlen("$percent%"));
            echo "$percent%";
            if (X3::db()->query($sql))
                @file_put_contents($status, json_encode(array('status' => 'loading', 'message' => $percent)));
        }
        if (is_file($status))
            @unlink($status);
        echo "\nOK!\n\n";
    }

    public function runGrabnews() {
        $grabbers = News_Grabber::get(array('status'));
        require_once('application/helpers/SimpleDom.php');
        foreach ($grabbers as $grabber) {
            $class = "News";
            if ($grabber->data == 'Обзоры')
                $class = "Article";
            echo "Grabbing $grabber->url for $class ($grabber->type)\n--------------------------\n";
            $model = new $class;
            if ($grabber->type == 'RSS') {
                $rss = RssParser::getInstance($grabber->url)->call();
                foreach($rss as $item){
                    $link = (string) $item->link;
                    $kid = md5($link);
                    if(X3::db()->count("SELECT id FROM `$model->tableName` WHERE kvaziid='$kid'")==0){
                        $model->title = (string) $item->title;
                        $model->kvaziid = $kid;                        
                        $date = time();
                        if (property_exists($item, 'pubDate'))
                            $date = strtotime($item->pubDate);
                        $model->created_at = $date;
                        if(property_exists($item, 'image')){
                            $url = $item->image->url;
                            $ext = pathinfo($url,PATHINFO_EXTENSION);
                            $filename = "$class-".time().rand(10,99).".$ext";
                            if(@file_put_contents(X3::app()->basePath . "/uploads/$class/$filename", file_get_contents($url)))
                                $model->image = $filename;
                        }
                        if($grabber->regtext == '')
                            $model->text = (string) $item->description;
                        else {
                            $html = file_get_html($link);
                            $idx = 0;
                            if(strpos($grabber->regtext,':')){
                                $idx = explode(':',$grabber->regtext);
                                $grabber->regtext = $idx[0];
                                $idx = (int)array_pop($idx);
                            }
                            $model->text = nl2br($html->find($grabber->regtext, $idx)->plaintext);
                        }
                        if(!$model->save())
                            print_r($model->table->getErrors());
                    }else
                        echo "\t$class with $item->title already exists\n";
                }
            } else {
                $html = file_get_html($grabber->url);
                $elems = $html->find($grabber->tag);
                $tidx = 0;
                if(strpos($grabber->regtitle,':')){
                    $tidx = explode(':',$grabber->regtitle);
                    $grabber->regtitle = $tidx[0];
                    $tidx = (int)array_pop($tidx);
                }
                $didx = 0;
                if(strpos($grabber->regdate,':')){
                    $didx = explode(':',$grabber->regdate);
                    $grabber->regdate = $didx[0];
                    $didx = (int)array_pop($didx);
                }
                $cidx = 0;
                if(strpos($grabber->regtext,':')){
                    $cidx = explode(':',$grabber->regtext);
                    $grabber->regtext = $cidx[0];
                    $cidx = (int)array_pop($cidx);
                }
                $iidx = 0;
                if(strpos($grabber->regimage,':')){
                    $iidx = explode(':',$grabber->regimage);
                    $grabber->regimage = $iidx[0];
                    $iidx = (int)array_pop($iidx);
                }                
                foreach ($elems as $elem) {
                    $kid = md5($elem->href);
                    if(X3::db()->count("SELECT id FROM `$model->tableName` WHERE kvaziid='$kid'")==0){
                        $html2 = file_get_html($elem->href);
                        if($grabber->regtitle != ''){
                            $model->title = $html2->find($grabber->regtitle, $tidx)->plaintext;
                        }
                        if($grabber->regdate != ''){
                            $find = $html2->find($grabber->regdate, $didx);
                            if($find)
                                $model->created_at = strtotime($find->plaintext);
                            else
                                $model->created_at = time();
                        }
                        if($grabber->regtext != ''){
                            $model->text = nl2br($html2->find($grabber->regtext, $cidx)->plaintext);
                        }
                        if($grabber->regimage != ''){
                            $find = $html2->find($grabber->regimage, $iidx);
                            if($find){
                                $url = $find->src;
                                $ext = pathinfo($url,PATHINFO_EXTENSION);
                                $host = parse_url($grabber->url);
                                $url = $host['scheme'] . "://" . $host['host'] . "/" . ltrim($url,'/');
                                $filename = "$class-".time().rand(10,99).".$ext";
                                if(@file_put_contents(X3::app()->basePath . "/uploads/$class/$filename", file_get_contents($url)))
                                    $model->image = $filename;
                            }
                        }
                        $model->kvaziid = $kid;
                        if(!$model->save())
                            print_r($model->table->getErrors());
                    }else
                        echo "\t$class with $elem->href already exists\n";
                }
            }
        }
    }

}

?>
