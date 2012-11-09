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
class Manufacturer extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'manufacturer';
    public static $pages = 'ABC';
    public $_fields = array(
        'id' => array('integer[10]', 'unsigned', 'primary', 'auto_increment'),
        'image' => array('file', 'default' => 'NULL', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'name' => array('string[255]', 'unique'),
        'title' => array('string[255]', 'orderable'),
        'link' => array('string[255]', 'default' => 'NULL'),
        'text' => array('text', 'default' => 'NULL'),
        'rules' => array('html', 'default' => 'NULL', 'orderable'),
        'bskip' => array('boolean', 'default' => '0'),
        'status' => array('boolean', 'default' => '1'),
        'weight' => array('integer[5]', 'unsigned', 'default' => '0', 'orderable'),
        'metatitle'=>array('string','default'=>'','language'),
        'metakeywords'=>array('string','default'=>'','language'),
        'metadescription'=>array('string', 'default'=>'','language'),        
        'created_at' => array('integer[10]', 'unsigned', 'default' => '0')
    );

    public function fieldNames() {
        return array(
            'image' => 'Лого',
            'name' => 'Уник. имя',
            'title' => 'Название',
            'link' => 'Ссылка на сайт',
            'text' => 'Описание',
            'bskip' => 'Не генерировать уникальную модель',
            'rules' => 'Правила генерации',
            'status' => 'Видимость',
            'weight' => 'Порядок',
            'metatitle'=>'Metatitle',
            'metakeywords'=>'Metakeywords',
            'metadescription'=>'Metadescription',            
        );
    }

    public function moduleTitle() {
        return 'Производители';
    }

    public function cache() {
        return array(
                //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
    }

    public function actionSavelist() {
        if (isset($_POST['list'])) {
            $list = explode("\n", $_POST['list']);
            $objs = array();
            $errs = array();
            foreach ($list as $w => $elem) {
                $elem = trim($elem);
                $elem = preg_replace("#[\t\r\s]#", "", $elem);
                if ($elem == '')
                    continue;
                $E = self::get(array(array('name' => array('LIKE' => "'$elem'")), array('title' => array('LIKE' => "'$elem'"))), 1); //name LIKE '$elem' OR title LIKE '$elem'
                if ($E == null) {
                    $E = new self();
                    $name = strtolower($elem);
                    $E->name = $name;
                    $E->title = $elem;
                    $E->image = null;
                    $E->status = 1;
                    $E->weight = $w;
                    $E->created_at = time();
                    //$logo = X3_Thread::create("http://www.brandsoftheworld.com/search/logo/$elem")->run()->getBody();
                    $m = array();
                    /* if (preg_match("/<img.+class=\"image\".+src=\"([^\"]+?)\".+>/", $logo, $m) > 0) {
                      $path = array_pop($m);
                      $E->image = $path;
                      } */
                    if (!$E->save()) {
                        $errs[] = "Не удалось загрузить '$elem'. " . (X3_DEBUG) ? print_r($E->table->getErrors(), 1) : '';
                        continue;
                    }
                }
                X3::db()->query("UPDATE shop_item SET `manufacturer_id`=$E->id WHERE `title` LIKE '$elem %'");

                $objs[] = array(
                    'id' => $E->id,
                    'image' => $E->image,
                    'title' => $E->title,
                    'name' => $E->name,
                    'weight' => $E->weight,
                );
            }
            echo json_encode(array('objs' => $objs, 'errors' => $errs));
            exit;
        }
    }

    public function actionLogotypes() {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $filename = "uploads/Manufacturer/$name.$ext";
        $src = fopen($path, 'rb');
        $dst = fopen($filename, 'wb');
        if ($src === false || $dst === false) {
            if (is_file($filename)) {
                @unlink($filename);
                fclose($dst);
            }else
                X3::log('Ошибка в создании файла ' . $filename . ' при загрузке компаний списком. Возможно доступ запрещен к папке uploads/Manufacturer/', 'kansha_error');
            if ($src !== false)
                fclose($src);
            //no sweet
            $E->image = null;
        }else {
            //transfering...
            while ($c = fread($src, 1) && !feof($src)) {
                fwrite($dst, $c);
            }
            fclose($src);
            fclose($dst);
            $E->image = "$name.$ext";
        }
    }
    
    
    public function prepareMeta($group='') {
        $mt = $this->metatitle;
        $mk = $this->metakeywords;
        $md = $this->metadescription;
        $manuf = '';
        $ms = array(&$mt,&$mk,&$md);
        foreach ($ms as &$mz){
            $m = array();
            if(preg_match_all("/\[([@!]{0,2})(group|manufacturer)\]/",$mz,$m)>0){
                foreach($m[0] as $i=>$mx){
                    if(isset($m[2][$i]) && $m[2][$i] == 'group'){
                        $val = $group;
                    }elseif(isset($m[2][$i]) && $m[2][$i] == 'manufacturer'){
                        $val = $this->title;
                    }else
                        $val = '';
                    if(!empty($m) && $m[1][$i]!=''){
                        if(strpos($m[1][$i], '!')!==false){                            
                            $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
                            $ml = explode("\n",$ml);
                            foreach($ml as $Mm){
                                $Mm = trim($Mm,"\r\n\ ");
                                $Mm = array_map(function($item){return $item;},explode('=',$Mm));
                                $val = str_replace($Mm[0],$Mm[1],$val);
                            }                            
                            if(preg_match("/[иы]$/", $val)>0){
                                $val = mb_substr($val, 0,-1,X3::app()->encoding);
                            }
                        }
                        if(strpos($m[1][$i], '@')!==false){
                            $val = X3_String::create($val)->toLowerCase();
                        }
                    }
                    $mz = str_replace($mx, $val, $mz);
                }
            }
            $m = array();
            if(preg_match_all("/\{([^\}]+)\}/",$mz,$m)>0){
                foreach($m[0] as $i=>$m1){
                    $condition = explode('|',$m[1][$i]);
                    if(trim($condition[0])!='')
                        $val = $condition[1];
                    else
                        $val = $condition[2];
                    $mz = str_replace($m1, $val, $mz);
                }
            }            
        }
        $this->metatitle = $mt;
        $this->metakeywords = $mk;
        $this->metadescription = $md;
    }    

    public function actionGetlist() {
        if (!isset($_GET['as']))
            $_GET['as'] = 'id';
        $as = $_GET['as'];
        $groups = array('0' => 'Нет');
        $models = self::get(array('@order' => 'title'));
        foreach ($models as $model) {
            $groups[$model->$as] = $model->title;
        }
        echo json_encode($groups);
        exit;
    }
    
    public static function countByGroup($pid,$criteria = 'withPrice',$param=false) {
        $prop = X3::db()->fetch("SELECT sp.name,sp.group_id FROM shop_properties sp INNER JOIN shop_proplist spl ON spl.property_id=sp.id WHERE spl.id=$pid LIMIT 1");
        if($prop == null){ //if `isgroup` for boolean
            $prop = X3::db()->fetch("SELECT name,group_id FROM shop_properties sp WHERE sp.id=$pid LIMIT 1");
            $pid = '1';
        }
        if($prop == null)
            return false;
        $q1 = '';
        $q2 = '';
        if($criteria == 'withPrice'){
            $q1 = "INNER JOIN company_item ci ON ci.item_id=p.id";
            $q2 = " AND ci.price>0 GROUP BY m.id";
        }
        if($criteria == 'PriceAndManufacturer'){
            $q1 = "INNER JOIN company_item ci ON ci.item_id=p.id";
            $q2 = " AND ci.price>0 AND m.id=$param";
        }
        $c = X3::db()->count("SELECT `m`.`id` FROM manufacturer m INNER JOIN shop_item si ON m.id=si.manufacturer_id INNER JOIN prop_{$prop['group_id']} p ON si.id=p.id $q1 WHERE m.status AND p.{$prop['name']}='$pid' $q2");
        return $c;
    }

    
    //USER SIDE

    public function actionIndex() {
        $query = array('@condition'=>array('manufacturer.status','ci.price'=>array('>'=>' 0')),'@join'=>"INNER JOIN shop_item si ON si.manufacturer_id=manufacturer.id INNER JOIN company_item ci ON ci.item_id=si.id",'@order'=>'manufacturer.title','@group'=>'manufacturer.id');
        $ccond = array('join'=>'','where'=>'');
        $link = false;
        $bread = 'Каталог производителей';
        if(isset($_GET['gid']) && $_GET['gid']>0){
            $gid = (int)$_GET['gid'];
            if(isset($_GET['pid']) && $_GET['pid']>0){
                $pid = (int)$_GET['pid'];
                $link = Shop_Group::getLink($gid,$pid);
                $prop = X3::db()->fetch("SELECT sp.name,sp.group_id,spl.title FROM shop_properties sp INNER JOIN shop_proplist spl ON spl.property_id=sp.id WHERE spl.id=$pid LIMIT 1");
                if($prop == null){
                    $prop = X3::db()->fetch("SELECT name,group_id,label FROM shop_properties sp WHERE sp.id=$pid LIMIT 1");
                    $bread = array(array('/manufacturers.html'=>$bread));
                    $pid = '1';
                }else
                    $bread = array(array('/manufacturers.html'=>$bread));
                $ccond['join']="INNER JOIN prop_{$gid} p ON p.id=si.id";
                $ccond['where']=" AND p.{$prop['name']}=$pid";
                $query = array(
                    '@condition'=>array("manufacturer.status","p.{$prop['name']}"=>$pid,"ci.price"=>array('>'=>" 0")),
                    '@join'=>"INNER JOIN shop_item si ON si.manufacturer_id=manufacturer.id INNER JOIN company_item ci ON ci.item_id=si.id INNER JOIN prop_{$gid} p ON p.id=si.id",
                    '@order'=>'manufacturer.title',
                    '@group'=>'manufacturer.id'
                );
            }else{
                $ccond['where']=" AND si.group_id=$gid";
                $link = Shop_Group::getLink($gid);
                $bread = array(array('/manufacturers.html'=>$bread));
                $query = array(
                    '@condition'=>array("manufacturer.status","si.group_id"=>$gid,"ci.price"=>array('>'=>" 0")),
                    '@join'=>"INNER JOIN shop_item si ON si.manufacturer_id=manufacturer.id INNER JOIN company_item ci ON ci.item_id=si.id",
                    '@order'=>'manufacturer.title',
                    '@group'=>'manufacturer.id'
                );
            }
        }
        //$groups = X3_Widget::run('@views:manufacturer:_groups.php',array('type'=>0),array('cache'=>true,'cacheConfig'=>array('filename'=>'manufacturer0.cache')));
        $q = new X3_MySQL_Query('manufacturer');
        $query['@select']='manufacturer.*';
        $sql = $q->formQuery($query)->buildSQL();
        $count = 0;
        $list = X3_Widget::run('@views:manufacturer:_list.php',array('sql'=>$sql,'ccond'=>$ccond),array('cache'=>true,'cacheConf'=>array('filename'=>'manufacturer.list')));
        if(preg_match("/\[%([0-9]+)%\]/",$list,$m)>0){
            $count = $m[1];
            $list = str_replace($m[0],"",$list);
        }
        $metatitle = SysSettings::getValue("SeoTitleManufacturer");
        $metakeywords = SysSettings::getValue("SeoKeywordsManufacturer");
        $metadescription= SysSettings::getValue("SeoDescriptionManufacturer");
        $metatitle = preg_replace("/\[[^\]]+\]/", "", $metatitle);
        $metadescription = preg_replace("/\[[^\]]+\]/", "", $metadescription);
        $metakeywords = preg_replace("/\[[^\]]+\]/", "", $metakeywords);
        SeoHelper::setMeta($metatitle,$metakeywords,$metadescription);
        $this->template->render('index', array('models' => $cats,'count'=>$count,'manus'=>$models,'ccond'=>$ccond,'link'=>$link?"/$link":'',
            'bread'=>$bread,'groups'=>$groups,'list'=>$list));
    }

    public function actionShow() {
        $name = $_GET['name'];
        $model = Manufacturer::get(array('status', 'name' => $name), 1);
        if ($model == NULL)
            throw new X3_404();
        $image = "<h2>" . $model->title . "</h2>";
        if (is_file('uploads/Manufacturer/' . $model->image)) {
            $image = '<img title="' . $model->title . '" src="/uploads/Manufacturer/150x45xf/' . $model->image . '">';
        }
        $groups = X3_Widget::run('@views:manufacturer:_groups.php',array('type'=>1,'model'=>$model),array('cache'=>true,'cacheConfig'=>array('filename'=>'manufacturer.'.$model->name.'.cache')));
        if(empty($model->metatitle)) $model->metatitle = SysSettings::getValue("SeoTitleManufacturer");
        if(empty($model->metakeywords)) $model->metakeywords = SysSettings::getValue("SeoKeywordsManufacturer");
        if(empty($model->metadescription)) $model->metadescription= SysSettings::getValue("SeoDescriptionManufacturer");        
        $model->prepareMeta();
        SeoHelper::setMeta($model->metatitle, $model->metakeywords, $model->metadescription);
        $this->template->render('show', array('model' => $model, 'groups' => $groups,'bread'=>array(array('/manufacturers.html'=>'Каталог производителей'))));
    }
    
    public function actionGetimages() {
        echo file_get_contents('http://go.mail.ru/search_images?q='.urlencode($_GET['q']));
        exit;
    }
    
    public function actionLinkimg() {
        $name = $_POST['name'];
        $src = $_POST['src'];
        $model = Manufacturer::get(array('name'=>$name),1);
        $fname = "Manufacturer-".time().".jpg";
        if(@file_put_contents(X3::app()->basePath.'/uploads/Manufacturer/'.$fname, file_get_contents($src))){
            $model->image = $fname;
            $model->save();
            echo $fname;
        }
        exit;
    }

    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            Manufacturer_Group::delete(array('manufacturer_id' => $model->id));
            X3::db()->query("UPDATE shop_item SET manufacturer_id=NULL WHERE manufacturer_id=$model->id");
            if (is_file("uploads/Manufacturer/$model->image"))
                @unlink("uploads/Manufacturer/$model->image");
        }
    }

    public function getDefaultScope() {
        return array(
            '@order' => 'title ASC'
        );
    }

    public function beforeValidate() {
        $this->title = trim($this->title);
        if ($this->name == '') {
            $this->name = strtolower(X3_String::create($this->title)->translit());
        }
        if ($this->created_at == 0) {
            $this->created_at = time();
        }
        //$this->rules = mysql_real_escape_string($this->rules);
        return parent::beforeSave();
    }

}

?>
