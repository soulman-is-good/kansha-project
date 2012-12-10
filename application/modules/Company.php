<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Items
 *
 * @author Soul_man
 */
class Company extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'data_company';
    private $addresses = array();
    private static $settings = array();
    
    public $_fields = array(
        'id' => array('integer[10]', 'unsigned', 'primary', 'auto_increment'),
        'image' => array('file', 'default' => 'NULL', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'title' => array('string[255]', 'orderable'),
        'login' => array('string[255]', 'unique'),
        'password' => array('string[255]', 'password'),
        'site' => array('string[255]', 'default' => 'NULL'),
        'delivery' => array('string[255]', 'default' => 'NULL'),
        'delivery_rules' => array('content', 'default' => 'NULL'),
        'email_private' => array('email'),
        'text' => array('text', 'default' => 'NULL'),
        'servicetext' => array('text', 'default' => 'NULL'),
        'isfree' => array('boolean', 'default' => '1', 'orderable'),
        'isfree1' => array('boolean', 'default' => '1', 'orderable'),
        'isfree2' => array('boolean', 'default' => '1', 'orderable'),
        'status' => array('boolean', 'default' => '1', 'orderable'),
        'weight' => array('integer[5]', 'unsigned', 'default' => '0', 'orderable'),
        'rate' => array('integer[11]', 'unsigned', 'default' => '0', 'orderable'),
        'created_at' => array('integer[11]', 'unsigned', 'default' => '0', 'orderable'),
        'updated_at' => array('integer[11]', 'unsigned', 'default' => '0', 'orderable'),
        'lastbeen_at' => array('integer[11]', 'unsigned', 'default' => '0', 'orderable'),
        'metatitle'=>array('string','default'=>'','language'),
        'metakeywords'=>array('content','default'=>'','language'),
        'metadescription'=>array('content', 'default'=>'','language'),          
    );

    public function moduleTitle() {
        return 'Компании';
    }

    public function fieldNames() {
        return array(
            'title' => 'Название',
            'site' => 'Сайт',
            'image' => 'Лого',
            'login' => 'Логин',
            'delivery' => 'Доставка по Казахстану',
            'delivery_rules' => 'Условия доставки',
            'password' => 'Пароль',
            'email_public' => 'E-mail для пользователей',
            'email_private' => 'E-mail для внутренней рассылки',
            'text' => 'Описание компании',
            'servicetext' => 'Описание для услуг',
            'isfree' => 'Бесплатный пакет услуг',
            'isfree1' => 'Бесплатный пакет магазина',
            'isfree2' => 'Бесплатный пакет распродаж',
            'status' => 'Видимость',
            'rate' => 'Рейтинг',
            'weight' => 'Порядок',
        );
    }
    
    public function getAddress($type=0) {
        if ($this->table->getIsNewRecord())
            return array();
        if (isset($this->addresses[$this->id]))
            return $this->addresses[$this->id];
        if($type === false)
            $cond = array('@condition'=>array('company_id' => $this->id),'@order'=>'id');
        else
            $cond = array('@condition'=>array('company_id' => $this->id,'type'=>"$type "),'@order'=>'id');
        if(X3::user()->city>0)
            $cond['@condition']['city'] = X3::user()->city;
        else if(is_array(X3::user()->region) && X3::user()->region[0]>0)
            $cond['@condition']['city'] = X3::user()->region[0];
        if(null == ($addr = Address::get($cond, 1))){
            unset($cond['@condition']['city']);
            $addr = Address::get($cond, 1);
        }
        
        return $this->addresses[$this->id] = $addr;
    }

    public function getItems() {
        return Shop_Item::get(array('@join' => array('table' => 'company_item', 'on' => array('company_id' => $this->id, 'item_id' => 'shop_item.id'))));
    }
    
    public function _getPassword() {
        $pass = base64_decode($this->table->getAttribute('password'));
        $tmp = '';
        for($i=0;$i<strlen($pass);$i++){
            $tmp.=chr(ord($pass[$i])-1);
        }
        return $tmp;
    }
    
    public function _setPassword($val) {        
        $tmp = '';
        for($i=0;$i<strlen($val);$i++){
            $tmp.=chr(ord($val[$i])+1);
        }
        $this->table->setAttribute('password', base64_encode($tmp));
    }


    public function cache() {
        return array(
                //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
    }

    public function filter() {
        return array(
            //'allow' => array(
                //'admin' => array('*'),
                //'moder' => array('*'),
                //'root' => array('*'),
            //),
            //'deny' => array(
                //'*' => array('*')
            //)
        );
    }

    public function getDefaultScope() {
        $scope = array('@order' => 'title, weight ASC');
        if(isset($_POST['filter'])){
            if($_POST['filter']=='1'){
                $scope['@join'] = "INNER JOIN company_item ci ON ci.company_id=data_company.id";
                $scope['@group'] = "ci.company_id";
            }
            if($_POST['filter']=='2')
                $scope['@join'] = "INNER JOIN company_service cs ON cs.company_id=data_company.id";
                $scope['@group'] = "cs.company_id";
        }
        return $scope;
    }

    public function menu($cur = 'edit') {
        $a = array(
            'edit' => 'Основные',
            'excel' => 'Excel',
            'content' => 'Наличие',
            'autoupdate' => 'Обновление',
            'upload' => 'Поиск в интернете',
        );
        $p = array();
        foreach ($a as $k => $v) {
            if ($k == $cur)
                $p[] = "<span>$v</span>";
            else
                $p[] = "<a href=\"/admin/company/$k/$this->id\">$v</a>";
        }
        return implode(' :: ', $p);
    }

    //ADMIN Features

    /**
     * 
     * Загружает данные из excel файла в базу. 
     * Формат файла: в столбце А - название, B - цена, C - id клиента
     * D - url товара у клиента. Если название есть в базе то товар добавляется к товарам компании
     * Если цена < 0 то товар будет удален
     * @param string $fname имя файла
     */
    public function execExcel() {
        //no file no business
        if ((!isset($_FILES['price']) || !isset($_GET['excel'])))
            return false;
        set_time_limit(240);
        if ($_FILES['price']['type'] != 'application/vnd.ms-excel' &&
                $_FILES['price']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' &&
                $_FILES['price']['type'] != 'application/force-download')
            return false;
        $company_id = (int) $_GET['excel'];
        if (!$company_id > 0)
            return false;
        $file = $_FILES['price']['tmp_name'];

        $props = $_POST['prop'];

        X3::profile()->begin('Company Excel', true);

        require_once(X3::app()->basePath . "/application/extensions/PHPExcel.php");
        $sheets = array();
        $sheets_lower = array();
        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        if (method_exists($objReader, 'setReadDataOnly')) {
            $objReader->setReadDataOnly(true);
        }
        
        $objPHPExcel = $objReader->load($file);
        if($objPHPExcel->getSheetCount()==0)
            return false;
        $objWorksheet = $objPHPExcel->getSheet(0);
        $counter = -1;
        //$objPHPExcel = new PHPExcel;
        //var_dump($objPHPExcel->getAllSheets());exit;
        //echo $objPHPExcel->getAllSheets();exit;
        //if(is_object($objWorksheet))
        foreach ($objWorksheet->getRowIterator() as $row) {
            $counter++;
            $sheets[$counter] = array();

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $j = 0;
            foreach ($cellIterator as $cell) {
                $val = $cell->getValue();
                if ($props['name'] == $j) {
                    $sheets[$counter][0] = $val; //iconv('UTF-8', 'WINDOWS-1251',);
                    $sheets_lower[$counter][0] = mb_strtolower(trim($val), 'UTF-8');
                }
                if ($props['price'] == $j) {
                    $sheets_lower[$counter][1] = $sheets[$counter][1] = $val;
                }
                if ($props['id'] == $j) {
                    $sheets_lower[$counter][2] = $sheets[$counter][2] = $val;
                }
                if ($props['url'] == $j) {
                    $sheets_lower[$counter][3] = $sheets[$counter][3] = $val;
                }
                if ($props['manufacturer'] == $j) {
                    $sheets_lower[$counter][4] = $sheets[$counter][4] = $val;
                }
                $j++;
            }
        }
        //////	 PHPExcel	////////

        if (empty($sheets))
            return false;

        $inserted = 0;
        $deleted = 0;
        

            function like($a, $s, $no = false) {
                if ($no)
                    return "'" . implode("%' AND $a LIKE '%", explode(' ', $s)) . "'";
                else
                    return "$a LIKE '" . implode("%' AND $a LIKE '%", explode(' ', $s)) . "'";
            }        

            $query = "SELECT item_id, client_id FROM company_item WHERE company_id='" . $company_id . "'";
            $result = X3::db()->query($query);
            if (!is_resource($result))
                $result = array();
            else
                while($row = mysql_fetch_assoc($result)) {
                    if ($row['client_id']) {
                        $price[$row['client_id']] = $row['item_id'];
                    }
                }
        $s_array = array();
        $_items = X3::db()->query("SELECT articule, model_name, shop_item.title title, shop_item.id, manu.title manufacturer FROM shop_item INNER JOIN manufacturer manu ON shop_item.manufacturer_id=manu.id");
        $k = 0;
        while ($it = mysql_fetch_assoc($_items)) {
            $t = trim($it['title']);
            $t = trim(preg_replace("/\(.*\)/", "", $t));
            $s_array[$it['id']] = array(trim($it['articule']), $t, $it);
            $k++;
        }
        $md5 = isset($_POST['md5']);
        $parse_type = 1;
        X3::db()->startTransaction();
        $found = array();
        foreach ($sheets_lower as $price_num => $price_name) {
            //$articule = trim($price_name[]);
            $articule = '';
            $name = strip_tags($price_name[0]);
            $name = trim($name,' !@#$_`\'"/|?,.');
            if(empty($name) || isset($found[md5($name)]))
                continue;
            $v = array(
                $md5?md5($price_name[2]):$price_name[2],
                str_replace(' ', '', $price_name[1]),
                $price_name[3],
                $price_name[0],
                null,
                null,
                //$v['articule']
            ); // HATERESS
            $v[1] = preg_replace('~[";\s]+~', '', $v[1]);
            $rus1 = false;//preg_match('~[а-яА-Я]{4,}~', $v[0])>0;
            $rus2 = preg_match('~[а-яА-Я]{4,}~', $v[1])>0;
            $v[1] = str_replace(',', '.', $v[1]);
            $v[1] *= 1;            
            if(!isset($price[$v[0]])){
                $si = array();
                $si = $this->searchMeItem($name, $articule, $s_array,$parse_type);
                if (count($si) != 1) {
                    $not_found[$price_num] = $sheets[$price_num][0];
                    continue;
                }
            }else{
                $si = X3::db()->fetch("SELECT * FROM shop_item WHERE id='{$price[$v[0]]}'");
                if(empty($si)){
                    continue;
                }
                $si = array($si);
            }
            $found[md5($name)]=true;
            $url = $v[2];
            $url = str_replace('&amp;', '&', $url);

            if (empty($url) || !X3_String::create($url)->check_protocol() || !X3_Thread::create($url)->run(X3_Thread::CHECK_CONNECTION)) {
                $url = '';
            }
            $item = NULL;
            //Zero price
            if($v[1]==0){
                if (isset($price[$v[0]])) {
                    X3::db()->addTransaction("DELETE FROM company_item WHERE company_id=$company_id AND item_id={$price[$v[0]]}");
                    $deleted++;
                }
                //unset($vs[$idx]);
            }else{
                $iid = 0;
                if(isset($price[$v[0]])){
                    $iid = $price[$v[0]];
                }elseif (!empty($si) and count($si) == 1){
                    $iid = $si[0]['id'];
                }

                if (!$iid>0 || NULL === ($item = Company_Item::get(array('company_id' => $company_id, 'item_id' => $iid), 1))) {
                    $item = new Company_Item();
                    $item->company_id = $company_id;
                    $item->item_id = $iid;
                }

                $item->client_id = $v[0];
                $item->price = $v[1];
                $item->url = $url;
                $item->url_checked = 1;
                if (!$item->save()) {
                    $serr = 'Ошибка сохранения позиции ' . $v[3];
                    $inserted++;
                    var_dump($serr);
                    //$not_found[$price_num] = $sheets[$price_num][0];
                    //print_r($item->getTable()->getErrors());
                    X3::log('Ошибка сохранения позиции ' . $v[3] . ' "' . print_r($item->table->getErrors(), 1) . '"', 'kansha_error');
                }else $inserted++;//else unset($vs[$idx]);
            }

            unset($price[$v[0]]);            

        }
        
        if(!X3::db()->commit()){
          echo 'Ошибка при удалении. '.X3::db()->getErrors();
          X3::db()->rollback();
        } 


        $query = "UPDATE data_company SET updated_at='" . time() . "' WHERE id='" . $company_id . "' LIMIT 1";
        X3::db()->query($query);

        $all = $inserted + $deleted + count($not_found);
        X3::profile()->end('Company Excel');
        X3_Session::writeOnce('Company_Excel', array('all' => $all, 'not_found' => $not_found, 'inserted' => $inserted,
            'deleted' => $deleted));
        return true;
    }

    public function execContent() {
        
    }

    /**
     * Dynamicaly loading files for autoupdate module
     */
    public function actionLoadup() {
        if (isset($_FILES['upload'])) {
            var_dump($_FILES);
        }
        exit;
    }

    public function update_props($param = NULL) {
        if ($param == NULL) {
            if (!isset(self::$settings[$this->id]))
                if (NULL === (self::$settings[$this->id] = Company_Settings::get(array('company_id' => $this->id), 1)))
                    self::$settings[$this->id] = new Company_Settings;
            return self::$settings[$this->id]->update_props;
        }else{
            if (!isset(self::$settings[$this->id]))
                if (NULL === (self::$settings[$this->id] = Company_Settings::get(array('company_id' => $this->id), 1)))
                    self::$settings[$this->id] = new Company_Settings;
            self::$settings[$this->id]->update_props = $param;
            self::$settings[$this->id]->save();
        }
    }
    
    public function getSettings() {
        if (!isset(self::$settings[$this->id]))
            if (NULL === (self::$settings[$this->id] = Company_Settings::get(array('company_id' => $this->id), 1)))
                self::$settings[$this->id] = new Company_Settings;
        return self::$settings[$this->id];
    }


    private function searchMeItem($name, $articule, &$se,$type = 0) {
        $res = array();
        $unset = array();
        foreach ($se as $id => $s) {
            if (empty($s[0]) && empty($s[1]))
                continue;
            $found = false;
            if($type === 0){
                if (!empty($articule) && !empty($s[0])) {
                    $articule = trim($articule);
                    if (!empty($articule) && stripos($articule, $s[0]) !== false) {
                        $found = true;
                        $res[] = $se[$id][2];
                        unset($se[$id]);
                        break;
                    }
                }
            }elseif($type === 1){
                if (!$found && !empty($name)) {
                    if (!empty($s[0]) && strlen($s[0])>2 && stripos($name,$s[0]) !== false) {
                        //var_dump($se[$id][2]);exit;
                        $res[] = $se[$id][2];
                        unset($se[$id]);
                        break;
                    } elseif (!empty($s[1])) {
                        $tmp1 = trim(preg_replace("/\(.*\)/", "", $name));
                        $tmp2 = trim(preg_replace("/\(.*\)/", "", $s[1]));
                        if(strcmp(strtolower($tmp1), strtolower($tmp2)) === 0){
                            $res[] = $se[$id][2];
                            unset($se[$id]);
                            break;
                        }
                    }
                }
            }
        }
        return $res;
    }
    
    
    public function execAutoupdate() {
        if (!isset($_FILES['upload']) && !isset($_POST['url']))
            return;
        $cid = (int) $_GET['autoupdate'];
        if (!$cid > 0)
            return;


        X3::profile()->begin('Company autoupdate', true);
        $company = $this->where('id=' . $cid)->asObject(1);
        $filename = $_FILES['upload']['name'] | $_POST['url'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        if (!preg_match('/^(xls|xlsx|csv|xml|zip)$/', $ext)) {
            $ext = $_POST['type'];
        }
        if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {
            $unlink = $filename = 'uploads/Company/autoupdate-' . time() . '.' . $ext;
            set_time_limit(0);
            if (!move_uploaded_file($_FILES['upload']['tmp_name'], X3::app()->basePath . DIRECTORY_SEPARATOR . $filename)) {
                throw new X3_Exception('Error moving file');
            }
        $site = X3::app()->baseUrl;
        $dir = X3::app()->basePath;
        exec("php run.php company autoupdate cid=$cid filename='$site/$filename' > $dir/application/log/autoload.$company->id 2>&1 &");
        X3::app()->module->redirect('/admin/company/autoupdate/' . $this->id);
        //system("php run.php company autoupdate cid=$cid filename='http://95.57.119.93/$filename'");
        exit;
        if ($ext == 'zip') {
            $zip = zip_open($filename);
            if (!zip_entry_open($zip, $zip_entry = zip_read($zip)))
                throw new X3_Exception('Невозможно распаковать архив');

            $ext = explode('.', zip_entry_name($zip_entry));
            $ext = array_pop($ext);
            $filename = 'uploads/Company/autoupdate-' . time() . '.' . $ext;
            $f = fopen($filename, 'w');
            if (!$f) {
                throw new X3_Exception('Ошибка при распаковке файла ' . zip_entry_name($zip_entry));
            } else {
                while ($b = zip_entry_read($zip_entry, 10240)) {
                    fputs($f, $b);
                }
                fclose($f);
                zip_close($zip);
                unset($b);
                unset($zip_entry);
                unset($zip);
                @unlink($unlink);
                $unlink = $filename;
            }
        } //finish unpacking
        $base = basename($filename);
        $params = array(
            'id' => $_POST['prop'][$_POST['type']]['id'],
            'articule' => $_POST['prop'][$_POST['type']]['articule'],
            'price' => $_POST['prop'][$_POST['type']]['price'],
            'url' => $_POST['prop'][$_POST['type']]['url'],
            'name' => $_POST['prop'][$_POST['type']]['name'],
            'group' => $_POST['prop'][$_POST['type']]['group'],
            'photo' => $_POST['prop'][$_POST['type']]['photo'],
            'desc' => $_POST['prop'][$_POST['type']]['desc'],
            'delimiter' => $_POST['prop'][$_POST['type']]['delimiter'],
        );
        if($_POST['type']=='xml')
            $params['container'] = $_POST['prop'][$_POST['type']]['item'];
        $loader = Loader::load($filename, $params, $_POST['type']);
        if ($loader === false) {
            $msg = "Обновление прайса компании '$this->title'. Не удалось распознать файл '$base'.";
            //Notify::sendMail('autoupdateError01',array('message'=>$msg));
            X3::log($msg, 'kansha_error');
            X3_Session::writeOnce('error', $msg);
            if(is_file($filename))
                @unlink($filename);
            X3::app()->module->redirect('/admin/company/autoupdate/' . $this->id);
        }
        $n = 0;
        $price = array();
        $gr_props = array();
        $query = "SELECT item_id, client_id FROM company_item WHERE company_id='" . $this->id . "'";
        $result = X3::db()->fetchAll($query);
        if (!is_array($result))
            $result = array();
        else
            foreach ($result as $row) {
                if ($row['client_id']) {
                    $price[$row['client_id']] = $row['item_id'];
                }
            }
        //Generating resulting xls
        //X3::import("@app:extensions:PHPExcel.php",true);
        //spl_autoload_unregister(array('X3','autoload'));
        require_once("application/extensions/PHPExcel.php");
        spl_autoload_register(array('X3','autoload'));
        $retxls = new PHPExcel();
        $retxls->setActiveSheetIndex(0);
        $rs = $retxls->getActiveSheet();
        $retxls->getProperties()->setCreator("kansha.kz")
                ->setLastModifiedBy("kansha.kz")
                ->setTitle("Обновление товаров компании")
                ->setSubject("Компания '" . $this->title . "'")
                ->setDescription("Документ сгенерирован из не найденых товаров в базе данных kansha.kz")
                ->setKeywords("kansha.kz обновление")
                ->setCategory("kansha.kz");
        $rs->setCellValue('A1', 'Название');
        $rs->setCellValue('B1', 'Цена');
        $rs->setCellValue('C1', 'ID');
        $rs->setCellValue('D1', 'URL');
        $rs->setCellValue('G1', 'Фото');
        $rc = 2;
        $srch = array();
        $postfixes = array();
        /////////////////
        //Generating search Articule and unique model
        /////////////////
            $_items = X3::db()->fetchAll("SELECT articule, model_name, shop_item.id, manu.title manufacturer FROM shop_item INNER JOIN manufacturer manu ON shop_item.manufacturer_id=manu.id");
            $s_array = array();
            foreach ($_items as $k => $it) {
                $s_array[$it['id']] = array($it['articule'], $it['model_name'], $_items[$k]);
            }
        /////////////////        
        try {
            $vs = array();
            while ($v = $loader->next()){$vs[]=$v;}
            $loader->free();
            $allcount = count($vs);
            //First we search for articules then secondly for unique model. No other way. Very specific model names.
            for($parse_type=0;$parse_type<2;$parse_type++){
            if(($parse_type == 0 && $params['articule']!=='') || $parse_type == 1)
                foreach($vs as $v) {
                    if($xx%500 == 0){
                        if($xx>0){
                            X3::db()->commit();
                        }
                        X3::db()->startTransaction();
                    }
                $_vc = $v;
                //TODO: REWRITE THIS FUCKIN' PIECE OF SHEET!
                $articule = trim($v['articule']);
                $name = $v['name'];
                $v = array(
                    $v['id'],
                    str_replace(' ', '', $v['price']),
                    $v['url'],
                    $v['name'],
                    $v['category'],
                    $v['photo'],
                    $v['articule']
                ); // HATERESS
                $id = $v[0];
                //$v[0] = id_func($v[0], $c['id_function']);
                //$v[0] = preg_replace('~[";]+~','',$v[0]);
                $v[1] = preg_replace('~[";\s]+~', '', $v[1]);
                $rus1 = preg_match('~[а-яА-Я]{4,}~', $v[0])>0;
                $rus2 = preg_match('~[а-яА-Я]{4,}~', $v[1])>0;
                $v[1] = str_replace(',', '.', $v[1]);
                $v[1] *= 1;
                $si = array();
                $si = $this->searchMeItem($name, $articule, $s_array,$parse_type);
                
                if (!isset($price[$v[0]]) and $v[1] > 0 and !$rus1 and !$rus2 and (!isset($si) or count($si) != 1)) {
                    $found = false;
                    $serr = '';
                    if (!$price[$v[0]])
                        $serr .= 'ID клиента #' . $v[0] . ' не найден. ';
                    //if(!$v[1])
                    //$serr .= 'Отсутствует цена для '.$v[3].' строка '.$rc.'. ';
                    //if(!$rus1[0])
                    //$serr .= 'Отсутствует цена для '.$v[3].' строка '.$rc.'. ';
                    $rs->setCellValueExplicit('A' . $rc, $v[3], PHPExcel_Cell_DataType::TYPE_STRING);
                    $rs->setCellValue('B' . $rc, $v[1]);
                    $rs->setCellValueExplicit('C' . $rc, (string) $v[0], PHPExcel_Cell_DataType::TYPE_STRING);
                    $rs->setCellValue('D' . $rc, $v[2]);
                    if (isset($v[4])) {
                        $rs->setCellValueExplicit('E' . $rc, (string) $v[4], PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    if (preg_match('/\[(.+?)\]/', $params['photo'], $m)) {
                        $v[5] = str_replace($m[0], $v[5], $params['photo']);
                    }
                    $rs->setCellValue('G' . $rc, $v[5]);
                    $rs->setCellValue('H' . $rc, $_vc['desc']);
                    $rs->setCellValueExplicit('I' . $rc, $articule, PHPExcel_Cell_DataType::TYPE_STRING);
                    $rc++;
                    //$res .= 'Не найден ID клиента «'.$id.'»'.(($c['id_function'])?' id в базе '.$v[0]:'')."\r\n";
                    continue;
                }
                //TODO: no price then skip??
                if ($v[1] == 0 or $rus1 or $rus2)
                    continue;

                $url = $v[2];
                if (empty($url) and $_POST['shop_url'])
                    $url = str_replace('[ID]', $v[0], $_POST['shop_url']);
                $url = str_replace('&amp;', '&', $url);
                
                if (!empty($url) && X3_Thread::create($url)->run(2)) {
                    //$url = '';
                }
                if (!isset($price[$v[0]]) || (NULL === ($item = Company_Item::get(array('company_id' => $this->id, 'item_id' => $price[$v[0]]), 1)))){
                    $item = new Company_Item();
                    $item->company_id = $this->id;
                    if($si and count($si)==1){
                        $item->item_id = $si[0]->id;
                    }                    
                }

                $k = (float) str_replace(',', '.', $_POST['price_koef']);
                $pr = $v[1] * $k;

                if (isset($_POST['md5']))
                    $item->client_id = md5($v[0]);
                else
                    $item->client_id = $v[0];
                $item->price = $pr;
                $item->url = $url;
                $item->url_checked = 1;
                if (!$item->save()) {
                    $serr .= 'Ошибка сохранения позиции ' . $v[3];
                    X3::log('Ошибка сохранения позиции ' . $v[3] . ' "' . print_r($item->table->getErrors(), 1) . '"', 'kansha_error');
                }
                //$query = "UPDATE price SET price='" . $pr . "', price_opt='0', url='" . sql($url) . "', url_checked=0 WHERE company_id='" . $cid . "' AND shop_id='" . $price[$v[0]] . "' LIMIT 1";
                unset($price[$v[0]]);
                $n++;
            }
            }
        } catch (Exception $e) {
            $msg = "Обновление прайса компании '$this->title'. Ошибка при сохранении.";
            //Notify::sendMail('autoupdateError01',array('message'=>$msg));
            X3::log($msg, 'kansha_error');
            X3_Session::writeOnce('error', $msg);
            if(is_file($filename))
            @unlink($filename);
            X3::app()->module->redirect('/admin/company/autoupdate/' . $this->id);
            return;
        }
        X3::db()->commit();   
        if (count($price)) {
            $p = "('" . implode("', '", $price) . "')";
            X3::db()->query("UPDATE company_item SET price=0 WHERE item_id IN $p AND company_id=$this->id");
        }
        X3::db()->query("DELETE FROM company_item WHERE company_id=$cid AND price=0");
        //TODO: WTF? Db::query("UPDATE price SET price=0 WHERE company_id=$cid AND client_id=''");
        $query = "UPDATE company SET update_time='" . time() . "' WHERE id='" . $c['id'] . "' LIMIT 1";
        $company->updated_at = time();
        $company->update_props($_POST['prop']);
        $company->save();
        $notfound = '';
        if ($rc > 2) {
            $xls = PHPExcel_IOFactory::createWriter($retxls, 'Excel2007');
            $rfname = 'uploads/Company/autoupdate_' . $this->id . '_' . date('d-m-Y-H-i') . '.xlsx';
            $xls->save($rfname);
            $rc -= 2;
            $notfound = "Не найдено $rc id клиента, ссылка на <a href=\"/$rfname\">excel файл</a>";
            //Notify::sendMail('autoupdateNotify01',array('message'=>$notfound));
        } else {
            //Notify::sendMail('autoupdateNotify01',array('message'=>'Все товары найдены'));
        }
        if(is_file($filename))
            @unlink($filename);
        X3::app()->module->template->addData(array('found' => $n, 'notfound' => $notfound));
        } elseif($_POST['url']!='') {
            $company->update_props($_POST['prop']);
            $s = $company->getSettings();
            //$s = Company_Settings::get(array('company_id'=>$company->id),1);
            $s->company_id = $cid;
            $s->autoload_auto = (int)isset($_POST['autoupdate']);
            $s->autoload_url = $_POST['url'];
            $s->autoload_md5 = isset($_POST['md5'])?1:0;
            $s->autoload_type = $_POST['type'];
            $s->autoload_shopurl = $_POST['shop_url'];
            $s->autoload_koef = (float)$_POST['price_koef'];
            $s->save();
            exec("php run.php company autoupdate cid=$cid > application/log/autoupdate.$cid.log 2>&1 &");
            //system("php run.php company autoupdate cid=$cid");            
            X3::app()->module->template->addData(array('process' => '1'));
        }        
        X3::profile()->end('Company autoupdate');
    }
    
    public function actionSaveautoup() {
        if(X3::user()->isGuest()) exit;
        $id = (int)$_GET['id'];
        $company = self::getByPk($id);
        if($company==null) exit;
        $s = $company->getSettings();
        $s->company_id = $id;
        $s->value = '{}';
        $s->autoload_auto = (int)isset($_POST['autoupdate']);
        $s->autoload_url = $_POST['url'];
        $s->autoload_md5 = isset($_POST['md5'])?1:0;
        $s->autoload_type = $_POST['type'];
        $s->autoload_shopurl = $_POST['shop_url'];
        $s->autoload_koef = (float)$_POST['price_koef'];
        $s->update_props = $_POST['prop'];
        if(!$s->save()){
            print_r($s->table->getErrors());
            echo X3::db()->getErrors();
        }
        //$company->update_props($_POST['prop']);
        exit;
    }

    public function execUpload() {
        $cid = (int) $_GET['upload'];
        $gid = (int) $_GET['group'];
        if (is_file("uploads/Company/upload-$gid-$cid.json")) {
            if (isset($_GET['delete'])) {
                @unlink("uploads/Company/upload-$gid-$cid.json");
                X3::app()->module->redirect("/admin/company/upload/$cid/group/$gid");
            }
            X3::app()->module->template->addData('upload_data', file_get_contents("uploads/Company/upload-$gid-$cid.json"));
        }
        if (!isset($_FILES['file']) || $_FILES['file']['error'] > 0)
            return;
        if (!$cid > 0 || !$gid > 0)
            return;
        $eng_letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $company = $this->where('id=' . $cid)->asObject(1);
        if ($company == null)
            return;
        X3::profile()->begin('Company upload', true);
        $data = array();
        $rules = array();
        foreach ($_POST['props'] as $name => $prop) {
            $k = array_search(strtolower($prop), $eng_letters, 1);
            if ($k !== false && isset($data[$k])) {
                if (!is_array($data[$k]))
                    $data[$k] = array($data[$k]);
                $data[$k][] = $name;
            }elseif ($k !== false)
                $data[$k] = $name;
        }
        $R = array();
        if (isset($_POST['rules'])) {
            $R = $_POST['rules'];
            foreach ($_POST['rules'] as $prop) {
                foreach ($prop as $name => $rule)
                    $rules[$name] = $rule;
            }
        }
        if (NULL === ($c = Company_Settings::get(array('company_id' => $cid), 1)))
            $c = new Company_Settings;
        $c->company_id = $cid;
        $value = $c->value;
        if (isset($value["group-$gid"]))
            unset($value["group-$gid"]);
        if (!is_array($value))
            $value = array();
        $c->value = array_merge($value, array("group-$gid" => array('props' => $_POST['props'], 'rules' => $R, 'adds' => $_POST['adds'])));
        $c->save();
        unset($R);
        $file = $_FILES['file']['tmp_name'];
        X3::import("@app:extensions:PHPExcel.php", true);
        $result = array();
        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        if (method_exists($objReader, 'setReadDataOnly')) {
            $objReader->setReadDataOnly(true);
        }
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $counter = -1;
        foreach ($objWorksheet->getRowIterator() as $row) {
            $counter++;

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $j = 0;
            foreach ($cellIterator as $cell) {
                $val = $cell->getValue();
                if (is_array($data[$j])) {
                    foreach ($data[$j] as $G) {
                        $m = array();
                        if (preg_match("/{$rules[$G]}/", $val, $m) > 0)
                            $result[$counter][$G] = array_pop($m);
                        else
                            $result[$counter][$G] = "";
                    }
                }elseif ($data[$j] != '')
                    $result[$counter][$data[$j]] = $val;
                $j++;
            }
        }
        @file_put_contents("uploads/Company/upload-$gid-$cid.json", json_encode($result));
        @file_put_contents("uploads/Company/upload-$gid-$cid.status", json_encode(array('status' => 'stop', 'percent' => '0', 'position' => '-1', 'found' => array())));
        X3::profile()->end('Company upload');
        X3::app()->module->redirect("/admin/company/upload/$cid/group/$gid");
    }

    public function actionListupdates() {
        $dir = 'uploads/Company/';
        $h = opendir($dir);
        $res = array();
        $id = (int) $_GET['id'];

        while ($file = readdir($h)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($ext, array('xls', 'xlsx')) && strpos($file, 'autoupdate_' . $id . '_') === 0) {
                $res[] = "/$dir$file";
                if (isset($_GET['empty'])) {
                    @unlink("$dir$file");
                }
            }
        }
        echo json_encode($res);
        exit;
    }

    public function actionSavesettings() {
        if (!IS_AJAX || X3::user()->isGuest())
            throw new X3_404;
        $cid = (int) $_GET['cid'];
        $gid = (int) $_GET['gid'];
        if (!$cid > 0 || !$gid > 0)
            return;
        $eng_letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        X3::profile()->begin('Company upload', true);
        $data = array();
        $rules = array();
        foreach ($_POST['props'] as $name => $prop) {
            $k = array_search(strtolower($prop), $eng_letters, 1);
            if ($k !== false && isset($data[$k])) {
                if (!is_array($data[$k]))
                    $data[$k] = array($data[$k]);
                $data[$k][] = $name;
            }elseif ($k !== false)
                $data[$k] = $name;
        }
        $R = array();
        if (isset($_POST['rules'])) {
            $R = $_POST['rules'];
            foreach ($_POST['rules'] as $prop) {
                foreach ($prop as $name => $rule)
                    $rules[$name] = $rule;
            }
        }
        if (NULL === ($c = Company_Settings::get(array('company_id' => $cid), 1)))
            $c = new Company_Settings;
        $c->company_id = $cid;
        $value = $c->value;
        if (isset($value["group-$gid"]))
            unset($value["group-$gid"]);
        if (!is_array($value))
            $value = array();
        $c->value = array_merge($value, array("group-$gid" => array('props' => $_POST['props'], 'rules' => $R, 'adds' => $_POST['adds'])));
        if ($c->save())
            echo 'OK';
        else
            print_r($c->table->getErrors());
        exit;
    }

    public function actionJob() {
        if (isset($_GET['upload'])) {
            $stat = $_GET['status'];
            $cid = (int) $_GET['upload'];
            $gid = (int) $_GET['group'];
            if (!$cid > 0 || !$gid > 0)
                return;
            $status = json_decode(file_get_contents("uploads/Company/upload-$gid-$cid.status"), 1);
            $status['status'] = $stat;
            @file_put_contents("uploads/Company/upload-$gid-$cid.status", json_encode($status));
            $php = "php";
            if (stripos(PHP_OS, "win") === false)
                $php = "Z:\\usr\\bin\\php.exe";
            $dir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "run.php";
            $log = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "application" .
                    DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "company.log";
            exec("$php $dir company upload cid=$cid gid=$gid > $log 2>&1 &");
        }
        exit;
    }
    
    public function actionGo() {
        if(!isset($_GET['to'])) throw new X3_404;
        $cid = explode(',',base64_decode($_GET['to']));
        if($cid[1]>0){
            $data = Company_Item::getByPk($cid[1])->url;
        }else
            $data = Company::getByPk($cid[0])->site;
        if($cid[0]>0){
            Company_Stat::log($cid[0]);
        }
        header("Location: $data");
        exit;
    }
    

    public function actionUpdateupload() {
        $cid = (int) $_GET['cid'];
        $gid = (int) $_GET['gid'];
        if (IS_AJAX && !X3::user()->isGuest() && isset($_POST)) {
            $file = "uploads/Company/upload-$gid-$cid.json";
            $data = json_decode(file_get_contents($file), 1);
            $id = (int) $_POST['id'] - 1;
            $post = $_POST;
            unset($post['id']);
            if (is_array($data) && isset($data[$id])) {
                $data[$id] = $post;
                @file_put_contents($file, json_encode($data));
            }
        }
        exit;
    }
    
    public function getLink() {
        $name = X3_String::create($this->title)->translit();
        $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
        return "/$name-company$this->id";
    }
    
    public function actionUpdatefield() {
        if(!X3::user()->isAdmin())
            throw new X3_404();
        $attr = $_POST['attr'];
        $value = trim($_POST['value']);
        list($attr,$data) = explode(':',$attr);
        $_data = array();
        $data = explode('&', $data);
        foreach ($data as $v) {
            $v = explode('=',$v);
            $_data[$v[0]]=$v[1];
        }
        if(Company_Item::update(array("$attr"=>$value),$_data))
            echo 'OK';
        else
            echo X3::db()->getErrors();
        exit;
    }
    
    public function actionLogin() {
        if(X3::user()->isCompany())
            $this->redirect('/company/settings.html');
        $err = array();
        if(X3::user()->isGuest() && isset($_POST['login']) && isset($_POST['pass'])){
            $ci = new CompanyIdentity($_POST['login'],$_POST['pass']);
            if($ci->authenticate()){
                $this->redirect('/company/settings.html');
            }else{
                $err[]='Не верный логин или пароль.';
            }
        }
        if(!X3::user()->isGuest())
            $err[]='Вы уже зашли под другой учетной записью. ';
        $this->template->render('login',array('errors'=>$err,'bread'=>array(array('#'=>'Вход для компаний')),'class'=>'login'));
    }
    
    public function actionRestore() {
        $err = array();
        if(isset($_POST['email'])){
            $email = mysql_real_escape_string(strip_tags($_POST['email']));
            $c = Company::get(array('email_private'=>$email),1);
            if($c != null){
                X3_Session::writeOnce('passwordSent','На указа');
                $this->refresh();
            }else{
                $err[]='Не верный E-mail';
            }
        }
        if(!X3::user()->isGuest())
            $err[]='Вы уже зашли под другой учетной записью. ';
        $this->template->render('restore',array('errors'=>$err,'bread'=>array(array('#'=>'Восстановление пароля')),'class'=>'login'));
    }
    
    /******************************************
     * USER PART
    ******************************************/
    public function actionIndex() {
        if(isset($_GET['city'])){
            if($_GET['city']!='all'){
                $c = mysql_real_escape_string($_GET['city']);
                $c = X3::db()->fetch("SELECT id FROM data_region WHERE name='{$c}'");
                if($c!=null) $c = $c['id'];
                else $c = 0;
                if(X3::user()->city != $c)
                    X3::user()->CompanyPage = 0;
                X3::user()->city = $c;
            }else
                X3::user()->city = 0;
        }
        $order = '';
        switch(X3::user()->CompanySort){
            case 'feedback':
                $order = 'fcount DESC';
            break;
            case 'marks':
                $order = 'rank DESC';
            break;
            default:
                $order = 'stats DESC';
            break;
        }
        $add = '';
        if(X3::user()->city>0)
            $add = " INNER JOIN data_address a ON a.company_id = c.id AND a.type=0 AND a.city=".X3::user()->city;
        $q = "SELECT DISTINCT c.id id, c.title title, c.image image, c.site site,
            (SELECT COUNT(0) FROM company_feedback cf WHERE status AND cf.company_id=c.id) `fcount`,
            (SELECT COUNT(0) FROM company_stat cs WHERE cs.company_id=c.id) `stats`,
            (SELECT COUNT(0) FROM company_item ci WHERE ci.company_id=c.id) `icount`
            FROM data_company c $add INNER JOIN company_item ci ON ci.company_id=c.id WHERE c.status AND ci.price>0 AND (SELECT COUNT(0) FROM company_item ci WHERE ci.company_id=c.id)>0 ORDER BY c.isfree1, $order, c.title";
        $ms = X3::db()->query($q);
        
        if($ms===false) throw new X3_Exception(X3::db()->getErrors());
        $count = mysql_num_rows($ms);
        $selector = X3_Widget::run('@views:company:_cities.php');
        SeoHelper::setMeta();
        $this->template->render('index',array('q'=>$q,'ms'=>$ms,
            'bread'=>'<div style="position:relative;float:left;"><a class="list_shops" href="#"><span>Перечень магазинов</span><sup>'.$count.'</sup></a></div>'.$selector
        ));
    }
    
    public function actionShow() {
        if(!isset($_GET['id'])) throw new X3_404();
        $id = (int)$_GET['id'];
        if(NULL===($model = Company::getByPk($id))) throw new X3_404();
        if(isset($_GET['type']) && in_array($_GET['type'],array('feedback','services','sale','about','address'))){
            $type = $_GET['type'];
        }else 
            $type = 'about';
        //Company_Stat::log($id);
        $F = null;
        if($type=='feedback'){
            $F = new Company_Feedback();
            $F->rank = -1;
            if(isset($_POST['Feedback'])){
                $feed = $_POST['Feedback'];
                foreach($feed as &$f)
                    $f = strip_tags(trim($f));
                $feed['company_id'] = $model->id;
                $feed['ip'] = $_SERVER['REMOTE_ADDR'];
                $feed['content'] = htmlspecialchars($feed['content']);
                $F->getTable()->acquire($feed);
                if($F->save()){
                    $F->cookie = $model->id;
                    $email = 'info@kansha.kz';
                    if($model->getAddress()!=null && !empty($model->getAddress()->email))
                        $email .= ','.$model->getAddress()->email;
                    User_Storage::store($F->email,$F->name,array('company'=>$model->title,'content'=>$F->content),'ok!');
                    Notify::sendMail('Company.FeedbackUser',array('company'=>$model->title,'username'=>$F->name,'content'=>$F->content),$F->email);
                    Notify::sendMail('Company.FeedbackCompany',array('company'=>$model->title,'username'=>$F->name,'content'=>$F->content),$email);
                    $this->refresh();
                }else
                    echo X3::db()->getErrors();
            }
        }
        $fcount = X3::db()->fetch("SELECT COUNT(0) `cnt`, SUM(`rank`) `marks` FROM company_feedback cf WHERE status AND company_id=$id");
        $mcnt = X3::db()->fetch("SELECT SUM(`rank`) `m` FROM company_feedback cf WHERE status");
        if($mcnt['m']==0) $mcnt['m']=1;
        $mark = ceil($fcount['marks']/$mcnt['m'])*5;
        $fcount = (int)$fcount['cnt'];
        $scount = 0;//TODO Sale count;
        if($model->metatitle == '')
            if($type == 'about')
                $model->metatitle = 'О компании '.$model->title;
            elseif($type == 'services')
                $model->metatitle = 'Услуги компании '.$model->title;
            elseif($type == 'feedback')
                $model->metatitle = 'Отзывы о компании '.$model->title;
            elseif($type == 'sale')
                $model->metatitle = 'Распродажи компании '.$model->title;
            elseif($type == 'address')
                $model->metatitle = 'Адреса компании '.$model->title;
        $srcount = X3::db()->count("SELECT `id` FROM company_service WHERE company_id=$id AND (services<>'[]' OR groups<>'[]')");
        SeoHelper::setMeta($model->metatitle,$model->metakeywords,$model->metadescription);
        $this->template->render('show',array('type'=>$type,'model'=>$model,'fcount'=>$fcount,'scount'=>$scount,'srcount'=>$srcount,'rank'=>$mark,'F'=>$F,
            'bread'=>array(array('/shops.html'=>'Магазины'))));
    }
    
    public function actionBuy() {
        $id = (int)$_GET['item'];
        $model = Company_Item::getByPk($id);
        if($model === null) throw new X3_404();
        $item = Shop_Item::getByPk($model->item_id);
        $company = Company::getByPk($model->company_id);
        Company_Stat::log($model->company_id);
        $group = $item->getGroup();
        $group->prepareMeta($item);
        SeoHelper::setMeta('Заказать ' . $item->metatitle,'Заказать ' . $item->metakeywords,'Заказать ' . $item->metadescription);
        $this->template->render('buy',array('model'=>$model,'item'=>$item,'company'=>$company));
    }
    
    public function actionSort() {
        $name = $_GET['name'];
        if(in_array($name,array('popular','feedback','marks')))
            X3::user()->CompanySort = $name;
        else
            X3::user()->CompanySort = 'popular';
        echo 'OK';
        exit;
    }
    
    public function actionAsk() {
        $errors = array();
        if(isset($_POST['Ask']) && X3_Session::readOnce('ip') != $_SERVER['REMOTE_ADDR']){
            $ask = $_POST['Ask'];
            if(!isset($ask['shop']) || ($ask['shop']==0 && $ask['shop']!='common')){
                $errors['shop'] = 'Выберите магазин, которому хотите отправить вопрос';
                
            }
            if(!isset($ask['name']) || trim(strip_tags($ask['name']))==''){
                $errors['name'] = 'Введите пожалуста Ваше имя.';
            }
            if(!isset($ask['email']) || trim(strip_tags($ask['email']))==''){
                $errors['email'] = 'Введите пожалуста Email, на который нужно отправить ответ.';
            }
            elseif(preg_match("/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD", $ask['email']) == 0){
                $errors['email'] = 'Не верный формат адреса электронной почты. Проверте ввод E-mail адреса.';
            }
            $ask['question'] = nl2br(strip_tags($ask['question']));
            if(!isset($ask['question']) || trim($ask['question'])==''){
                $errors['question'] = 'Введите пожалуста текст вопроса.';
            }
            if(md5($ask['captcha'])!=X3::user()->captcha['text']){
                $errors['captcha'] = 'Код с картинки введен не верно.';
            }
            if(empty($errors)){
                if($ask['id']!='common'){
                    $item = Shop_Item::getByPk($ask['id']);
                    $item->title = "товара ".$item->title;
                    $link = $item->getLink();
                }else{
                    $item = (object)array('title'=>'компании');
                    $link = '#';
                }
                $company = Company::getByPk($ask['shop']);
                $data = array('item'=>$item->title,'url'=>$link,'company'=>$company->title,
                    'username'=>$ask['name'],'usermail'=>$ask['email'],'question'=>$ask['question']);
                $email = $company->getAddress()->email;
                X3_Session::writeOnce('ip',$_SERVER['REMOTE_ADDR']);
                if(($msg=Notify::sendMail('Company.Ask', $data,$email,null,"info@kansha.kz"))!==TRUE){
                    $errors['name'] = $msg;
                    Informer::add("Пользователю с IP '{$_SERVER['REMOTE_ADDR']}' не удалось отправить вопрос компании '$company->title'.<br/>\n$msg", 'errors', $data);
                    User_Storage::store($ask['email'], $ask['name'], $data, $msg);
                }else{
                    Notify::sendMail('Company.AskUser', $data,$ask['email']);
                    User_Storage::store($ask['email'], $ask['name'],$data, 'ok!');
                }
            }
        }else
            $errors['name'] = 'Ошибка в данных';
        if(IS_AJAX){
            echo json_encode($errors);
        }else
            $this->redirect($_SERVER['HTTP_REFERER']);
        exit;
    }    
    
    public function beforeSave() {
        if ($this->created_at == 0) {
            $this->table->setAttribute('created_at', time());
        }
        if($this->site !='')
            $this->site = X3_String::create($this->site)->check_protocol('http',false);
                
        if(!empty($_POST['Company']['password']))
            $this->password = $_POST['Company']['password'];
        return parent::beforeSave();
    }
    
    public function onValidate($name,$pass) {
        if($name == 'password'){
            $tmp = $this->table['password'];
            if(preg_match("/[^a-zA-Z0-9!_\-=@#\$%\^\&\*\(\)]/", $tmp)>0){
                $this->addError('password', 'Недопустимые символы. Только цифры, латинские буквы a-zA-Z и знаки !_-=@#$%^&*()');
                return $pass = false;
            }
        }
        return $pass = true;
    }

    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            Address::delete(array('company_id' => $model->id));
            Company_Item::delete(array('company_id' => $model->id));
            Company_Settings::delete(array('company_id' => $model->id));
            Company_Feedback::delete(array('company_id' => $model->id));
            Company_Stat::delete(array('company_id' => $model->id));
            Company_ItemStat::delete(array('company_id' => $model->id));
            Sale::delete(array('company_id' => $model->id));
        }
        return parent::onDelete($tables, $condition);
    }

}

?>
