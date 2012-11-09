<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Company
 *
 * @author Soul_man
 */
class CompanyCommand extends X3_Command {

    public function init() {
        
    }

    public function runTest() {
        $filename = X3::app()->global['f'];
        $finfo = finfo_open(FILEINFO_MIME);
        echo finfo_file($finfo, $filename);
        finfo_close($finfo);        
        /*$th = X3_Thread::create('http://www.qpi.kz');
        if(!$th->run(true)){
            echo $th->getError();
        }
        exit;*/
    }

    public function runUpload() {
        $G = X3::app()->global;
        if (!isset($G['cid']) || !isset($G['gid']))
            return;
        $cid = (int) $G['cid'];
        $gid = (int) $G['gid'];
        $file = X3::app()->basePath."/uploads/Company/upload-$gid-$cid";
        if (!is_file("$file.json"))
            return false;
        $data = json_decode(file_get_contents("$file.json"), 1);
        $stat = json_decode(file_get_contents("$file.status"), 1);
        $notFound = 0;
        $position = $stat['position'];
        $Total = count($data);
        $found = array(); //found positions;
        $stat['total'] = $Total;
        if ($position == -1) {
            echo "Начинаем разбор прайса компании #$cid группы #$gid\r\n";
            echo "------------------------------------------------------";
            echo "Всего записей: $Total";
            echo "------------------------------------------------------";
        }

        foreach ($data as $i => $value) {
            if ($i <= $position)
                continue;
            $bFound = false;
            $condition = array('group_id' => $gid);
            $try = false;
            if (isset($value['articule']) && $value['articule'] != '') {
                $condition['articule'] = $value['articule'];
                $try = true;
            }
            if (isset($value['manufacturer']) && $value['manufacturer'] != '') {
                $value['manufacturer'] = trim($value['manufacturer']);
                $condition['manufacturer_id'] = array('IN' => "(SELECT id FROM manufacturer WHERE title LIKE '{$value['manufacturer']}' OR name LIKE '{$value['manufacturer']}')");
            }
            if (isset($value['title']) && $value['title'] != '') {
                $value['title'] = trim($value['title']);
                $model = preg_replace("/(\([^\)]+\))|([а-яА-Я])|(\s[0-9\.,'\"x]+?\s)|,/", "", $value['title']);
                $model = preg_replace("/[\s]+/", " ", $model);
                $M = X3::db()->fetchAll("SELECT name FROM manufacturer");
                if (!empty($M)) {
                    $mans = array();
                    foreach ($M as $m)
                        $mans[] = "\s{$m['name']}\s";
                    $mans = implode('|', $mans);
                    $model = preg_replace("/$mans/i", " ", $model);
                }
                $regexp = str_replace(" ", "|", $model);
                $condition['title'] = array('REGEXP' => "'{$regexp}'");
                $try = true;
            }
            //if not found, then we move further, else link prices
            $models = Shop_Item::get($condition);

            if (!$try || ($c = $models->count()) != 1) {
                if ($try == false) {
                    echo "По позиции #$i не было критериев выбора \r\n";
                }if ($c > 1) {
                    echo "Найдено $c {$value['title']}\r\n";
                } else {
                    $grabber_list = json_decode(Grabber::grep(array('fetch' => '1', 'q' => $model)), 1);
                    if (empty($grabber_list)) {
                        $notFound++;
                    } else {
                        
                    }
                }
            } else {
                $bFound = true;
            }

            if ($bFound) {
                $found[] = $i;
            }
            //writing stats
            $stat['percent'] = (int) (($i + 1) / $Total) * 100;
            $stat['found'] = $found;
            $stat['position'] = $i;
            @file_put_contents("$file.status", json_encode($stat));
        }
        $stat['status'] = 'finish';
        $stat['percent'] = '100';
        $stat['found'] = $found;
        $stat['notfound'] = $notFound;
        @file_put_contents("$file.status", json_encode($stat));
    }

    public function runAutoupdate() {
        try {
            set_time_limit(0);
            $cid = (int) X3::app()->global['cid'];
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Загрузка настроек...')));
            $st = Company_Settings::get(array('company_id' => $cid), 1);
            $company = Company::getByPk($cid);
            $md5 = $st->autoload_md5;
            $type = (empty($st->autoload_type)) ? 'xls' : $st->autoload_type;
            if(!isset(X3::app()->global['filename'])){
                $url = $st->autoload_url;
                $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                if(!in_array($ext,array('xls','xml','xlsx','csv','zip')))
                    $ext = 'xls';
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => "Скачивание файла '$url'.")));
                $unlink = $filename = 'uploads/Company/autoupdate-' . time() . '.' . $ext;
                if(FALSE === ($fp = fopen($url,'r'))){//X3_Thread::create($url,array(),"GET",3600)->run(X3_Thread::GET_STREAM))){
                    $msg = array('status' => 'error', 'message' => "Файл по адресу '{$url}' не доступен.");
                    //Notify::sendMail('autoupdateError01',array('message'=>$msg));
                    @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                    exit;
                }
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => "Скачивание файла '$url'..")));
                if($fp === FALSE || FALSE===($nf = fopen(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename, 'w'))){
                    $msg = array('status' => 'error', 'message' => "Не возможно создать файл на локальной машине.");
                    //Notify::sendMail('autoupdateError01',array('message'=>$msg));
                    @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                    //@unlink($filename);
                    exit;
                }
                $byte=0;
                //skip the headers
                $counter = 0;
                $headers = '';
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => "Скачивание файла '$url'...")));
                $c = '';
                $x = 0;
                //var_dump(file_get_contents($url));exit;

                while(!feof($fp)){
                    fwrite($nf,fread($fp, 10240));
                    $byte+=10240;
                    $fbyte = ($byte>1024)?(round($byte/1024)."Kb"):($byte>1048576?round($byte/1048576)."Mb":$byte."B");
                    @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => "Скачивание файла '$url' ($fbyte)")));
                }
                fclose($nf);
                fclose($fp);       
            }else{
                $filename = trim(X3::app()->global['filename'],"'");
                $filename = str_replace(X3::app()->baseUrl."/", "", $filename);
                $unlink = $filename;
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => "Файл загружен '$filename'")));
            }
            /*if (FALSE === file_put_contents($filename, file_get_contents($url))) {
                $msg = array('status' => 'error', 'message' => "Ошибка при получении файла '{$url}'.");
                //Notify::sendMail('autoupdateError01',array('message'=>$msg));
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                @unlink($filename);
                exit;
            }*/
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, X3::app()->basePath.DIRECTORY_SEPARATOR.$filename);
            finfo_close($finfo);
            $mimetype = array_shift(explode(';',$mimetype));
            $ext = 'xls';
            if(FALSE !== ($aext = array_search($mimetype, Loader::$mime_types)))
                $ext = $aext;
            if($ext == 'txt') $ext = 'xml';
            $rext = pathinfo($filename,PATHINFO_EXTENSION);
            $filename = str_replace(".$rext", ".$ext", $filename);
            rename(X3::app()->basePath.DIRECTORY_SEPARATOR.$unlink,X3::app()->basePath.DIRECTORY_SEPARATOR.$filename);
            $unlink = $filename;
            if ($ext == 'zip') {
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Распаковка архива...')));
                $zip = zip_open(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename);
                if (!zip_entry_open($zip, $zip_entry = zip_read($zip))) {
                    $msg = array('status' => 'error', 'message' => 'Невозможно распаковать архив');
                    file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                    @unlink(X3::app()->basePath.DIRECTORY_SEPARATOR.$unlink);
                    exit;
                }

                $ext = explode('.', zip_entry_name($zip_entry));
                $ext = array_pop($ext);
                $filename = 'uploads/Company/autoupdate-' . time() . '.' . $ext;
                $f = fopen(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename, 'w');
                if (!$f) {
                    $msg = array('status' => 'error', 'message' => 'Ошибка при распаковке файла ' . zip_entry_name($zip_entry));
                    file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                    zip_close($zip);
                    @unlink(X3::app()->basePath.DIRECTORY_SEPARATOR.$unlink);
                    exit;
                } else {
                    while ($b = zip_entry_read($zip_entry, 10240)) {
                        fputs($f, $b);
                    }
                    fclose($f);
                    zip_close($zip);
                    unset($b);
                    unset($zip_entry);
                    unset($zip);
                    @unlink(X3::app()->basePath.DIRECTORY_SEPARATOR.$unlink);
                    $unlink = $filename;
                }
            } //finish unpacking
            $base = basename($filename);
            $props = $st->_getUpdate_props();
            $params = array(
                'id' => isset($props[$type]['id']) ? $props[$type]['id'] : '',
                'articule' => isset($props[$type]['articule']) ? $props[$type]['articule'] : '',
                'price' => isset($props[$type]['price']) ? $props[$type]['price'] : '',
                'url' => isset($props[$type]['url']) ? $props[$type]['url'] : '',
                'name' => isset($props[$type]['name']) ? $props[$type]['name'] : '',
                'group' => isset($props[$type]['group']) ? $props[$type]['group'] : '',
                'photo' => isset($props[$type]['photo']) ? $props[$type]['photo'] : '',
                'desc' => isset($props[$type]['desc']) ? $props[$type]['desc'] : '',
                'delimiter' => isset($props[$type]['delimiter']) ? $props[$type]['delimiter'] : '',
                'get_data' => isset($props[$type]['get_data']) ? $props[$type]['get_data'] : false,
            );
            if ($type == 'xml')
                $params['container'] = $props[$type]['item'];
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Инитиализация обработчика...')));
            try {
                $loader = Loader::load(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename, $params, $type);
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Подготовка')));
            } catch (Exception $e) {
                echo $e->getMessage();
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'error', 'message' => 'Ошибка инитиализации!!!')));
                exit;
            }
            if ($loader === false) {
                $msg = array('status' => 'error', 'message' => "Обновление прайса компании '$company->title'. Не удалось распознать файл '$base'.");
                file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                if (is_file(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename))
                    @unlink(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename);
                var_dump('Ошибка');
                exit;
            }
            $n = 0;
            $price = array();
            $gr_props = array();
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Подготовка данных.')));
            $query = "SELECT item_id, client_id FROM company_item WHERE company_id='" . $company->id . "'";
            $result = X3::db()->query($query);
            if (!is_resource($result))
                $result = array();
            else
                while($row = mysql_fetch_assoc($result)) {
                    if (trim($row['client_id'])!='') {
                        $price[trim($row['client_id'])] = (int)$row['item_id'];
                    }
                }
            //Generating resulting xls
            require_once X3::app()->basePath . '/application/extensions/PHPExcel.php';
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Подготовка данных..')));
            $retxls = new PHPExcel();
            $retxls->setActiveSheetIndex(0);
            $rs = $retxls->getActiveSheet();
            $retxls->getProperties()->setCreator("kansha.kz")
                    ->setLastModifiedBy("kansha.kz")
                    ->setTitle("Обновление товаров компании")
                    ->setSubject("Компания '" . $company->title . "'")
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
            //Generating search Articule and unique model array
            /////////////////
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'prepare', 'message' => 'Подготовка данных...')));
            $s_array = array();
            $_items = X3::db()->query("SELECT articule, model_name, shop_item.id, manu.title manufacturer FROM shop_item INNER JOIN manufacturer manu ON shop_item.manufacturer_id=manu.id");
            $k = 0;
            while ($it = mysql_fetch_assoc($_items)) {
                $s_array[$it['id']] = array(trim($it['articule']), trim($it['model_name']), $it);
                $k++;
            }
            /////////////////
            $xx = 0;
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'loading', 'message' => '0')));
            //$allcount = $loader->count();
            //if($allcount<=0) $allcount = 1;
            try {
            $vs = array();
            try {
                while ($v = $loader->next()){$vs[]=$v;}
            }catch(Exception $e){
                echo $e->getMessage();
                X3::log($e->getMessage(),'kansha_error');
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'error', 'message' => 'Ошибка загрузки данных!')));
                exit;                
            }
            $loader->free();
            $allcount = count($vs);
            //price koefficient
            $koef = (double)str_replace(',', '.', $st->autoload_koef);
            if($koef==0) $koef = 1;           
            $mult = $params['articule']==''?1:2;
            $commonRules = str_replace("\r\n","",SysSettings::getValue('Company.AutoupdateRules'));
            $commonRules = explode(',',$commonRules);
            foreach($commonRules as $cri=>$cr) {$commonRules[$cri] = trim($cr);if(empty($commonRules[$cri])) unset($commonRules[$cri]);}
            $style_header = array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FFCEBF'),
                                ),
                                'font' => array(
                                    'bold' => false,
                                )
                            );
            //First we search for articules then secondly for unique model. No other way. Very specific model names.
            for($parse_type=0;$parse_type<2;$parse_type++){
            if(($parse_type == 0 && $params['articule']!=='') || $parse_type == 1)
                foreach($vs as $idx=>$v) {
                    if($xx%500 == 0){
                        if($xx>0){
                            X3::db()->commit();
                        }
                        X3::db()->startTransaction();
                    }
                    $serr = '';
                    $procent = ceil((++$xx)/($mult*$allcount)*100);
                    if($procent>100)
                        $procent = 100;
                    @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'loading', 'message' => $procent)));
                    $_vc = $v;
                    $v = array_extend(array('id' => '', 'url' => '', 'price' => '', 'name' => '', 'category' => '', 'photo' => '', 'articule' => ''), $v);
                    $articule = trim($v['articule']);
                    $name = strip_tags($v['name']);
                    $name = trim($name,' !@#$%^&*_=`\'"/|?,.');
                    $v = array(
                        $md5?md5($v['id']):trim($v['id']),
                        str_replace(' ', '', $v['price']),
                        $v['url'],
                        $v['name'],
                        $v['category'],
                        $v['photo'],
                        $v['articule']
                    ); // HATERESS
                    //$v[0] = id_func($v[0], $c['id_function']);
                    //$v[0] = preg_replace('~[";]+~','',$v[0]);
                    $v[1] = preg_replace('~[";\s]+~', '', $v[1]);
                    $rus1 = false;//preg_match('~[а-яА-Я]{4,}~', $v[0])>0;
                    $rus2 = preg_match('~[а-яА-Я]{4,}~', $v[1])>0;
                    $v[1] = str_replace(',', '.', $v[1]);
                    $v[1] *= 1;
                    if(empty($name) || ((!isset($price[$v[0]]) || empty($articule)) && TRUE === array_reduce($commonRules,function($res,$item)use($name){return $res || mb_stripos($name,$item,null,'UTF-8')!==false;},false))){
                        $rs->setCellValueExplicit('A' . $rc, $v[3], PHPExcel_Cell_DataType::TYPE_STRING);
                        $rs->setCellValue('B' . $rc, $v[1]);
                        $rs->setCellValueExplicit('C' . $rc, (string) $v[0], PHPExcel_Cell_DataType::TYPE_STRING);
                        if(!empty($v[2]))
                            $rs->setCellValue('D' . $rc, $v[2]);
                        if (isset($v[4])) {
                            $rs->setCellValueExplicit('E' . $rc, (string) $v[4], PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                        if (preg_match('/\[(.+?)\]/', $params['photo'], $m)) {
                            $v[5] = str_replace($m[0], $v[5], $params['photo']);
                        }
                        if(!empty($v[5]) && $v[5])
                            $rs->setCellValue('G' . $rc, $v[5]);
                        if(!empty($_vc['desc']))
                            $rs->setCellValue('H' . $rc, $_vc['desc']);
                        $rs->setCellValueExplicit('I' . $rc, $articule, PHPExcel_Cell_DataType::TYPE_STRING);
                        $rs->getStyle("A$rc:I$rc")->applyFromArray( $style_header );
                        $rc++;
                        continue;
                    }
                    if(!isset($price[$v[0]])){
                        $si = array();
                        $si = $this->searchMeItem($name, $articule, $s_array,$parse_type);
                        if ($parse_type == 1 && !$rus1 && !$rus2 && $v[1]>0 && (!isset($si) || count($si) != 1)) {
                            $found = false;
                            if (!isset($price[$v[0]]))
                                $serr .= 'ID клиента #' . $v[0] . ' не найден. ';
                            $rs->setCellValueExplicit('A' . $rc, $v[3], PHPExcel_Cell_DataType::TYPE_STRING);
                            $rs->setCellValue('B' . $rc, $v[1]);
                            $rs->setCellValueExplicit('C' . $rc, (string) $v[0], PHPExcel_Cell_DataType::TYPE_STRING);
                            if(!empty($v[2]))
                                $rs->setCellValue('D' . $rc, $v[2]);
                            if (isset($v[4])) {
                                $rs->setCellValueExplicit('E' . $rc, (string) $v[4], PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                            if (preg_match('/\[(.+?)\]/', $params['photo'], $m)) {
                                $v[5] = str_replace($m[0], $v[5], $params['photo']);
                            }
                            if(!empty($v[5]) && $v[5])
                                $rs->setCellValue('G' . $rc, $v[5]);
                            if(!empty($_vc['desc']))
                                $rs->setCellValue('H' . $rc, $_vc['desc']);
                            $rs->setCellValueExplicit('I' . $rc, $articule, PHPExcel_Cell_DataType::TYPE_STRING);
                            $rc++;
                            //$res .= 'Не найден ID клиента «'.$id.'»'.(($c['id_function'])?' id в базе '.$v[0]:'')."\r\n";
                            continue;
                        }
                        if ($rus1 or $rus2 or empty($si) or count($si)!=1){
                            if($rus2)
                                echo "Russian letters in the price #$idx $v[3] '$v[1]'\n";
                            elseif($parse_type == 1 && count($si)!=1)
                                echo "Found ".count($si)." items on $v[3]\n";
                            continue;
                        }
                    }else{
                        $si = X3::db()->fetch("SELECT * FROM shop_item WHERE id='{$price[$v[0]]}'");
                        if(empty($si)){
                            continue;
                        }
                        $si = array($si);
                    }
                    $url = $v[2];
                    if (empty($url) and $st->autoload_shopurl)
                        $url = str_replace('[ID]', $v[0], $st->autoload_shopurl);
                    $url = str_replace('&amp;', '&', $url);

                    if (empty($url) || !X3_String::create($url)->check_protocol() || !X3_Thread::create($url)->run(X3_Thread::CHECK_CONNECTION)) {
                        $url = '';
                    }
                    $item = NULL;
                    if($v[1]==0){
                        if (isset($price[$v[0]])) {
                            X3::db()->addTransaction("DELETE FROM company_item WHERE company_id=$company->id AND item_id={$price[$v[0]]}");
                            //Company_Item::delete(array('company_id' => $company->id, 'item_id' => $price[$v[0]]));
                        }
                        unset($vs[$idx]);
                    }else{
                        $iid = 0;
                        if(isset($price[$v[0]])){
                            $iid = $price[$v[0]];
                        }elseif (!empty($si) and count($si) == 1){
                            $iid = $si[0]['id'];
                        }
                        if (!$iid>0 || NULL === ($item = Company_Item::get(array('company_id' => $company->id, 'item_id' => $iid), 1))) {
                            $item = new Company_Item();
                            $item->company_id = $company->id;
                            $item->item_id = $iid;
                        }
                        $pr = $v[1] * $koef;
                        $item->client_id = $v[0];
                        $item->price = $pr;
                        $item->url = $url;
                        $item->url_checked = 1;
                        if (!$item->save()) {
                            $serr .= 'Ошибка сохранения позиции ' . $v[3];
                            //echo "-={$price[$v[0]]}=-";
                            print_r($item->table->getErrors());
                            X3::log('Ошибка сохранения позиции ' . $v[3] . ' "' . print_r($item->table->getErrors(), 1) . '"', 'kansha_error');
                        }else
                            unset($vs[$idx]);
                    }
                    //$query = "UPDATE price SET price='" . $pr . "', price_opt='0', url='" . sql($url) . "', url_checked=0 WHERE company_id='" . $cid . "' AND shop_id='" . $price[$v[0]] . "' LIMIT 1";
                    unset($price[$v[0]],$item);
                    $n++;
                }
                }
            } catch (Exception $e) {
                //Notify::sendMail('autoupdateError01',array('message'=>$msg));
                $msg = array('status' => 'error', 'message' => "Обновление прайса компании '$company->title'. Ошибка при сохранении.");
                file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode($msg));
                if (is_file(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename))
                    @unlink(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename);
                exit;
                return;
            }
            if(!X3::db()->commit())
                echo X3::db()->getErrors();
            //$loader->free();
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'end', 'message' => 'Загрузка завершена')));
            //if (count($price)) {
                //$p = "('" . implode("', '", $price) . "')";
                //X3::db()->query("UPDATE company_item SET price=0 WHERE item_id IN $p AND company_id=$company->id");
            //}
            X3::db()->query("DELETE FROM company_item WHERE company_id=$cid AND price=0");
            $company->updated_at = time();//strtotime('15.10.2012 03:'.rand(10,55));
            $company->update_props($props);
            if(!$company->save()){
                echo "#$company->id $company->title\n";
                echo X3::db()->getErrors()."\n";
                print_r($company->getTable()->getErrors());
                echo "\n-----------------------------\n\n";
            }
            $notfound = '';
            $title = false;
            $rfname = '';
            $rc -= 2;
            if ($rc > 0) {
                @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'end', 'message' => 'Сохранение результатов')));
                $xls = PHPExcel_IOFactory::createWriter($retxls, 'Excel2007');
                $title = strtolower(X3_String::create($company->title)->translit());
                $title = preg_replace("/[^a-zA-Z0-9_\.\-]/", "", $title);
                $rfname = 'uploads/Company/autoupdate_' . $company->id . '_' . $title . '.xlsx'; //. date('_d-m-Y-H-i') . '.xlsx';
                $xls->save(X3::app()->basePath . DIRECTORY_SEPARATOR . $rfname);
                $notfound = "Не найдено $rc id клиента, ссылка на <a href=\"/$rfname\">excel файл</a>";
                if(isset(X3::app()->global['accumulate'])){
                    $mailer = array();
                    if(is_file(X3::app()->basePath.'/uploads/company.mailer')){
                        $mailer = json_decode(file_get_contents(X3::app()->basePath.'/uploads/company.mailer'));
                    }
                    $mailer[] = array('company'=>$company->title,'notfound'=>$rc,'found'=>$n,'url'=>'/'.$rfname);
                    @file_put_contents(X3::app()->basePath.'/uploads/company.mailer',json_encode($mailer));
                }else
                    Notify::sendMail('autoupdateNotify01',array('message'=>$notfound));
                //}else
                    //die($company->id.', Ошибка при сохранении XLS отчета!!!');
            } else {
                if(isset(X3::app()->global['accumulate'])){
                    $mailer = array();
                    if(!is_file(X3::app()->basePath.'/uploads/company.mailer')){
                        $mailer = json_decode(file_get_contents(X3::app()->basePath.'/uploads/company.mailer'));
                    }
                    $mailer[] = array('company'=>$company->title,'notfound'=>$rc,'found'=>$n,'url'=>'/'.$rfname);
                    @file_put_contents(X3::app()->basePath.'/uploads/company.mailer',json_encode($mailer));
                }else
                    Notify::sendMail('autoupdateNotify01',array('message'=>'Все товары найдены'));
            }
            unset($company,$xls,$retxls);
            if (is_file(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename)){
                $ext = pathinfo($filename,PATHINFO_EXTENSION);
                if($title){
                    if(is_file(X3::app()->basePath."/uploads/Company/$title.$ext"))
                        @unlink(X3::app()->basePath."/uploads/Company/$title.$ext");
                    if(@rename(X3::app()->basePath.DIRECTORY_SEPARATOR.$filename, X3::app()->basePath."/uploads/Company/$title.$ext")){
                        $st->last_autoupdate_file = "uploads/Company/$title.$ext";
                        $st->save();
                    }
                }
                
            }
            unset($f,$st);
            $html = '    <div class="success" style="width:300px;position:absolute;right:10px;top:120px">';
            if($rc==0 && $n==0) $html.='Ничего не найдено.';
            elseif($rc==0) $html.='Все ';
            $html.=$n.' '.X3_String::create('товар')->numeral($n,array('товар','товара','товаров')).' '.X3_String::create('найдено')->numeral($n,array('найден','найдено','найдено'));
            if($rc>0) $html.='<br/>'.$rc.' '.X3_String::create('товар')->numeral($rc,array('товар','товара','товаров')).' не'.X3_String::create('найдено')->numeral($rc,array('найден','найдено','найдено'));
            $html.='</div>';
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'success', 'message' => (string)$rfname,'additional'=>$html)));
        } catch (Exception $e) {
            @file_put_contents(X3::app()->basePath."/uploads/autoload-$cid.stat", json_encode(array('status' => 'error', 'message' => $e->getMessage())));
        }
    }

    private function searchMeItem($name, $articule, &$se,$type = 0) {
        $res = array();
        $unset = array();
        foreach ($se as $id => $s) {
            if (empty($s[0]) && empty($s[1]))
                continue;
            $found = false;
            if($type === 0){
                //echo "Comparing `$articule` AND `$s[0]` \n";
                $articule = trim($articule);
                if (!empty($articule) && !empty($s[0])) {
                    if (stripos($articule, $s[0]) !== false) {
                        $found = true;
                        $res[] = $se[$id][2];
                        unset($se[$id]);
                        break;
                    }
                }
            }elseif($type === 1){
                if (!empty($name)) {
                    $name .= " ";
                    //$name has articule in it
                    if (!empty($s[0]) && strlen($s[0])>2 && stripos($name, $s[0]) !== false) {
                        $res[] = $se[$id][2];
                        unset($se[$id]);
                        break;
                    } elseif (!empty($s[1])) {
                        $test = trim($se[$id][2]['manufacturer']);
                        if (stripos($name, trim($s[1])." ") !== false && stripos($name,$test)!==false){
                            $res[] = $se[$id][2];
                            unset($se[$id]);
                            break;
                        }
                        /*$a1 = explode(' ', $s[1]);
                        $counter = count($a1);
                        foreach ($a1 as $value) {
                            $value = trim($value);
                            $i = stripos($name, $value);
                            //$j = explode(strtolower($value)," ".strtolower($name));
                            //$j = count($j)>1 && $j[1]=='';
                            if ($i!==false)
                                $counter--;
                        }
                        if ($counter === 0 && $i) {
                            $res[] = $se[$id][2];
                            unset($se[$id]);
                            break;
                        }*/
                    }
                }
            }
        }
        return $res;
    }
    
    public function runTest2() {
        echo date('d.m.Y',time());
    }

}

?>
