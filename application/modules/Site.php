<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Default
 *
 * @author Soul_man
 */
class Site extends X3_Module {

    public static function newInstance($class=null) {
        return parent::newInstance(__CLASS__);
    }
    public static function getInstance($class=null) {
        return parent::getInstance(__CLASS__);
    }
    
    public function filter() {
        return array(
        /*    'allow'=>array(
                '*'=>array('*'),
                'user'=>array('map','limit','feedback','error'),
                'sale'=>array('map','limit','feedback','error'),
                'admin'=>array('map','limit','feedback','error')
            ),
            'deny'=>array(
                '*'=>array('*'),
                'admin'=>array('index'),
                'user'=>array('index'),
                'sale'=>array('index')
            ),
            'handle'=>'redirect:/user/settings.html'*/
        );
    }
    
    public function err401(){
            header("HTTP/1.0 401 Authorization Required");
            echo '<h1>Доступ запрещен.</h1>';
            exit(0);
    }
    public function cache(){
        return array(
            //'cache'=>array('actions'=>'map','role'=>'*','expire'=>'+1 day','filename'=>'sitemap.xml','directory'=>X3::app()->basePath),
            //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }

    public function route() { //Using X3_Request $url propery to parse
        return array(
            '/^sitemap\.xml$/'=>'actionMap',
            '/^download\/(.+?).html/'=>array(
                'class'=>'Download',
                'argument'=>'$1'
            )
        );
    }
    public function actionIndex() {
        //$this->template->layout='index';
        $this->template->render('index');
    }

    public function actionMap() {
        if (!isset($_GET['type']) || $_GET['type'] != 'xml') {
            $pages = Page::get(array('name' => array('<>' => "'about'"), 'name' => array('<>' => "'contacts'"), 'name' => array('<>' => "'help'"), 'status'));
            $inside = strpos(X3::app()->request->url, 'group') > 0;
            $grps = X3::db()->query("SELECT sg.* FROM shop_group sg WHERE status ORDER BY weight, title");
            $i = 0;
            if ($inside) {
                $p = array();
                $groupTitle = '';
                if (preg_match("/group([0-9]+)\-p([0-9]+)/", X3::app()->request->url, $p) > 0) {
                    $gid = $p[1];
                    $pid = $p[2];
                    $name = X3::db()->fetch("SELECT name FROM shop_properties sp INNER JOIN shop_proplist spl ON spl.property_id=sp.id WHERE spl.id=$pid LIMIT 1");
                    if ($name !== null) {
                        $name = $name['name'];
                        $groupTitle = X3::db()->fetch("SELECT title FROM shop_proplist spl WHERE spl.id=$pid LIMIT 1");
                        $groupTitle = $groupTitle['title'];
                        $items = X3::db()->query("SELECT si.* FROM shop_item si INNER JOIN prop_$gid p ON p.id=si.id WHERE si.group_id=$gid AND p.$name=$pid");
                    } else {
                        $name = X3::db()->fetch("SELECT name,type,label FROM shop_properties sp WHERE sp.id=$pid LIMIT 1");
                        $type = $name['type'];
                        $groupTitle = $name['label'];
                        $name = $name['name'];
                        if ($type != 'boolean')
                            throw new X3_404();
                        $items = X3::db()->query("SELECT si.* FROM shop_item si INNER JOIN prop_$gid p ON p.id=si.id WHERE si.group_id=$gid AND p.$name=1");
                    }
                    if ($name === null)
                        throw new X3_404();
                }elseif (preg_match("/group([0-9]+)/", X3::app()->request->url, $p) > 0) {
                    $gid = $p[1];
                    $groupTitle = X3::db()->fetch("SELECT title FROM shop_group WHERE id=$gid");
                    $groupTitle = $groupTitle['title'];
                    $items = X3::db()->query("SELECT si.* FROM shop_item si WHERE si.group_id=$gid");
                }else
                    throw new X3_404();
                $this->template->render('sitemap', array('class' => 'error',
                    'inside'=>$inside,'items'=>$items,'groupTitle'=>$groupTitle,'pages'=>$pages,'grps'=>$grps,
                    'bread' => '<div style="position:relative;float:left;top:1px;"><a href="/sitemap.html" class="service">Карта сайта</a></div><div style="position:relative;float:left;margin-left:5px"><span class="other_noun inside">→</span></div><span>'.$groupTitle.'</span>'));
                exit;
            }else {
                $groups = array();
                while ($g = mysql_fetch_array($grps)) {
                    $pps = X3::db()->query("SELECT id, group_id, title, value, property_id FROM shop_proplist WHERE group_id={$g['id']} AND property_id IN (SELECT id FROM shop_properties WHERE isgroup AND group_id={$g['id']}) ORDER BY weight, title");
                    if (is_resource($pps) && mysql_num_rows($pps) > 0) {
                        while ($ps = mysql_fetch_assoc($pps))
                            $groups[] = array(
                                'title' => $ps['title'],
                                'url' => '/' . strtolower(X3_String::create($ps['title'])->translit(false, "'")) . '-group' . $ps['group_id'] . '-p' . $ps['id'],
                                'sm' => 'group' . $ps['group_id'] . '-p' . $ps['id'],
                                'mans' => X3::db()->fetchAll("SELECT m.title, m.name FROM manufacturer m INNER JOIN shop_item si ON si.manufacturer_id=m.id INNER JOIN company_item ci ON ci.item_id=si.id INNER JOIN prop_{$g['id']} p ON p.id=si.id WHERE m.status AND ci.price>0 AND si.group_id={$g['id']} GROUP BY m.id")
                            );
                    }else
                        $groups[] = array(
                            'title' => $g['title'],
                            'url' => '/' . strtolower(X3_String::create($g['title'])->translit(false, "'")) . '-group' . $g['id'],
                            'sm' => 'group' . $g['id'],
                            'mans' => X3::db()->fetchAll("SELECT DISTINCT m.title, m.name FROM manufacturer m INNER JOIN shop_item si ON si.manufacturer_id=m.id INNER JOIN company_item ci ON ci.item_id=si.id INNER JOIN prop_{$g['id']} p ON p.id=si.id WHERE m.status AND ci.price>0 AND si.group_id={$g['id']} GROUP BY m.id")
                        );
                    $i++;
                }
                $this->template->render('sitemap', array('class' => 'error', 
                    'inside'=>$inside,'groups'=>$groups,'groupTitle'=>$groupTitle,'pages'=>$pages,'grps'=>$grps,
                    'bread' => '<span>Карта сайта</span>'));
                exit;
            }            
        }else{
            //throw new X3_404();
            $this->template->layout = null;
            $links = array();
            $i=0;
            $file = 0;
            $files = array();
            //CATEGORIES
            $models = X3::db()->query("SELECT id,title FROM shop_category WHERE status");
            while($m = mysql_fetch_assoc($models)){
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($m['title'])->translit(false,"'"))."-c".$m['id'].".html");
                $i++;
                if($i%50000==0){
                    file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                    $links = array();
                    $files[] = $fname;
                    $file++;
                }
            }
            //GROUPS
            $models = X3::db()->query("SELECT id,title FROM shop_group WHERE status");
            while($m = mysql_fetch_assoc($models)){
                $prs = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE status AND isgroup AND group_id={$m['id']}");
                if(count($prs)>0){
                    foreach($prs as $pr){
                        if($pr['type'] == 'string' || $pr['type']=='content'){
                            $pls = X3::db()->fetchAll("SELECT * FROM shop_proplist sl WHERE group_id={$m['id']} AND property_id={$pr['id']}");
                            foreach($pls as $pl){
                                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($pl['value'])->translit())."-group".$m['id']."-p{$pl['id']}.html");
                                $i++;
                                if($i%50000==0){
                                    $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                                    $links = array();
                                    $files[] = $fname;
                                    $file++;
                                }
                            }
                        }else{
                            $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($pr['label'])->translit())."-group".$m['id']."-pr{$pr['id']}.html");
                            $i++;
                            if($i%50000==0){
                                $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                                $links = array();
                                $file++;
                            }
                        }
                    }
                }else{
                    $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($m['title'])->translit())."-group".$m['id'].".html");
                    $i++;
                    if($i%50000==0){
                        $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                        $links = array();
                        $files[] = $fname;
                        $file++;
                    }
                }
            }
            //ITEMS
            $models = X3::db()->query("SELECT id,title,articule FROM shop_item WHERE status");
            while($m = mysql_fetch_assoc($models)){
                $title = $m['title']. ($m['articule']!=''?" ({$m['articule']})":'');
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($title)->translit())."-".$m['id'].".html");
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($title)->translit())."-".$m['id']."/prices.html");
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($title)->translit())."-".$m['id']."/feedback.html");
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl."/".strtolower(X3_String::create($title)->translit())."-".$m['id']."/services.html");
                $i+=4;
                if($i%50000==0){
                    $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                    $links = array();
                    $files[] = $fname;
                    $file++;
                }
            }
            //COMPANIES
            $models = X3::db()->query("SELECT * FROM data_company WHERE status");
            while($m = mysql_fetch_assoc($models)){
                $name = X3_String::create($m['title'])->translit();
                $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);  
                $url = X3::app()->baseUrl."/{$name}-company{$m['id']}";
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),"$url.html");
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),"$url/feedback.html");
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),"$url/services.html");
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),"$url/sale.html");
                $i+=4;
                if($i%50000==0){
                    $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                    $links = array();
                    $files[] = $fname;
                    $file++;
                }
            }
            //BUY PAGES Google do not like this
            /*$models = X3::db()->query("SELECT ci.id AS `id` FROM company_item ci INNER JOIN data_company c ON c.id=ci.company_id");
            while($m = mysql_fetch_assoc($models)){
                $links[] = X3::app()->baseUrl . "/buy/" . $m['id'] . ".html";
                $i++;
                if($i%50000==0){
                    $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                    $links = array();
                    $files[] = $fname;
                    $file++;
                }
                
            }*/
            //todo:sales
            //NEWS
            $models = X3::db()->query("SELECT id FROM data_news WHERE status");
            while($m = mysql_fetch_assoc($models)){
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl . "/news/" . $m['id'] . ".html");
                $i++;
                if($i%50000==0){
                    $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                    $links = array();
                    $files[] = $fname;
                    $file++;
                }
                
            }
            //ARTICLES
            $models = X3::db()->query("SELECT id FROM data_article WHERE status");
            while($m = mysql_fetch_assoc($models)){
                $links[] = str_replace(array('&',"'",'"','>','<'),array('&amp;','&apos;','&quot;','&gt;','&lt;'),X3::app()->baseUrl . "/article/" . $m['id'] . ".html");
                $i++;
                if($i%50000==0){
                    $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                    $links = array();
                    $files[] = $fname;
                    $file++;
                }
                
            }
            if($i%50000!=0){
                $fname=$file==0?"sitemap.xml":"sitemap".($file-1).".xml";file_put_contents(X3::app()->basePath."/$fname",  X3_Widget::run('@views:site:map.php',array('links'=>$links)));
                $links = array();
                $files[] = $fname;
            }
            $robot  = "User-agent: *\r\n";
            $robot .= "Disallow: /admin\r\n";
            $robot .= "Disallow: /login\r\n";
            $robot .= "Host: www.kansha.kz\r\n";
            $robot .= "Sitemap: http://www.kansha.kz/sitemap.xml\r\n";
            for($j=0;$j<$file;$j++){
                $robot .= "Sitemap: http://www.kansha.kz/sitemap$j.xml\r\n";
            }
            file_put_contents(X3::app()->basePath.'/robots.txt', $robot);
            if($file>0){
                echo $i." links generated.";
                $index = X3_Widget::run('@views:site:map_index.php',array('files'=>$files));
                file_put_contents(X3::app()->basePath.'/sitemap_index.xml',$index);
                //header ("content-type: text/xml");
                //echo $index;
            }else{
                echo $i." links generated.";
                //header ("content-type: text/xml");
                //echo file_get_contents(X3::app()->basePath.'/sitemap.xml');
            }
            exit;
            //$this->template->render('map',array('links'=>$links));
        }
    }

    public function actionFeedback() {
        $fos = array('subject'=>'','text'=>'','name'=>'','email'=>'','company'=>'','city'=>'','phone'=>'');
        $success=false;
        $errors = array();
        if(isset($_POST['Fos'])){
            $fos = array_extend($fos,$_POST['Fos']);
            X3::import('application:extensions:swift:swift_required.php',true);
            $transport = Swift_MailTransport::newInstance();
            $user = User::getByPk(X3::app()->user->id);
            $subject = strip_tags($fos['subject']);
            $subject = trim($subject);
            $name = strip_tags($fos['name']);
            $name = trim($name);
            $city = strip_tags($fos['city']);
            $city = trim($city);
            $company = strip_tags($fos['company']);
            $phone = strip_tags($fos['phone']);
            $text = strip_tags($fos['text']);
            $email = strip_tags($fos['email']);
            $text = trim($text);
            $text = nl2br($text);
            if(empty($name))
                $errors['name'] = 'Поле Имя не заполнено.';
            if(empty($city))
                $errors['city'] = 'Поле Город не заполнено.';
            if(empty($text))
                $errors['text'] = 'Поле Сообщение не заполнено.';
            if(empty($email))
                $errors['email'] = 'Поле E-mail не заполнено.';
            elseif(preg_match("/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD", $email)==0 && $email!=''){
                $errors['email'] = 'Поле Email заполнено не корректно.';
            }
            if(empty($errors)){
                $Aemail = Syssettings::getValue('AdminEmail','string[255]','Email администратора','Настройки','soulman.is.good@gmail.com');
                $message = Swift_Message::newInstance()->setSubject('Письмо от пользователя '.$name)->setTo($Aemail);
                if($fos['email']!='')
                    $message->setFrom($fos['email']);
                $date = date("d.m.Y H:i");
                $message->setBody("Пользователь с именем <strong>$name</strong>({$_SERVER['REMOTE_ADDR']}) $date написал сообщение с темой '{$subject}'<br/>$text<hr/>
                <b>Прочие данные:</b><br/>Имя: $name<br />Компания: $company<br/>Город: $city<br/>Телефон: $phone<br/>Email: $email<br />", 'text/html');
                $mailer = Swift_Mailer::newInstance($transport);
                try {
                    $mailer->send($message);
                    $success='Сообщение успешно отправлено!';
                    X3_Session::getInstance()->writeOnce('fos',$success);
                    $this->controller->refresh();
                    //$fos = array('subject'=>'','text'=>'','name'=>'','email'=>'','company'=>'','city'=>'','phone'=>'');
                } catch (Exception $e) {
                    $errors['common']=$e->getMessage();
                    exit;
                }
            }
        }
        $this->template->render('feedback',array('fos'=>$fos,'errors'=>$errors,'fsuccess'=>$success));
    }

    public function actionContacts() {        
        $this->template->render('contacts',array());
    }

    public function actionLimit() {
        $limit = (int)$_POST['val'];
        if($limit<=0 || !IS_AJAX) exit;
        $model = ucfirst($_POST['module']);
        $path = X3::app()->getPathFromAlias("@app:modules:$model.php");
        if(!is_file($path)) exit;
        $model = $model.'Limit';
        X3::app()->user->$model = $limit;
        echo 'OK';
        exit;
    }
    
    public function actionWeights() {
        echo '1111';
        $model = ucfirst($_POST['module']);
        $ids = explode(',',$_POST['ids']);
        if(empty($ids)) exit;
        $tablename = X3_Module_Table::getInstance($model)->tableName;
        X3::db()->startTransaction();
        foreach ($ids as $i=>$id){
            if($id>0)
                X3::db()->addTransaction("UPDATE `$tablename` SET `weight`='$i' WHERE id='$id'");
        }
        X3::db()->commit();
        exit;
    }
    

    public function actionError() {
        $this->template->render('error',array('class'=>'error'));
    }
}
?>
