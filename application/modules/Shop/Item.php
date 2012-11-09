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
class Shop_Item extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'shop_item';
    public static $_props = array();
    public static $_groups = array();
    private $_mans = array();
    public $_fields = array(
        'id' => array('integer[10]', 'unsigned', 'primary', 'auto_increment'),
        'group_id' => array('integer[10]', 'unsigned', 'index', 'ref' => array('Shop_Group' => 'id', 'default' => 'title')),
        'manufacturer_id' => array('integer[10]', 'unsigned', 'default' => 'NULL', 'index', 'ref' => array('Manufacturer' => 'id', 'default' => 'title')),
        //'articule_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Articule'=>'id','default'=>'articule')),
        'articule' => array('string[255]', 'unique', 'default' => 'NULL'),
        'image' => array('file', 'default' => 'NULL', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'title' => array('string[255]', 'orderable'),
        'suffix' => array('string[255]','default'=>'NULL'),
        'model_name' => array('string[255]','default'=>''),
        'properties_text' => array('content', 'default' => 'NULL'),
        'status' => array('boolean', 'default' => '1', 'orderable'),
        'isnew' => array('boolean', 'default' => '1', 'orderable'),
        'istop' => array('boolean', 'default' => '0', 'orderable'),
        'weight' => array('integer[5]', 'unsigned', 'default' => '0'),
        'rate' => array('integer[11]', 'unsigned', 'default' => '0', 'orderable'),
        'updated_at' => array('datetime', 'default' => '0'),
        'created_at' => array('integer[10]', 'unsigned', 'default' => '0'),
        'metatitle' => array('string', 'default' => '', 'language'),
        'metakeywords' => array('string', 'default' => '', 'language'),
        'metadescription' => array('string', 'default' => '', 'language'),
    );

    public function fieldNames() {
        return array(
            'group_id' => 'Группа',
            //'articule_id'=>'Артикул',
            'articule' => 'Артикул',
            'title' => 'Название',
            'suffix' => 'Суффикс',
            'image' => 'Фото',
            'status' => 'Статус',
            'weight' => 'Порядок',
            'created_at' => 'Дата добавления',
            'rate' => 'Рейтинг',
        );
    }

    public $relations = array(
        'WithGroup' => array(
            'Shop_Item.group_id' => 'Shop_Group.id',
            'map' => array(
                'Shop_Item.id' => 'iid',
                'Shop_Group.id' => 'gid',
                'Shop_Group.title' => 'group',
                'Shop_Item.title' => 'title',
            ),
        /* many-to-many relation realization
          '@mediator'=>array(
          'Shop_Tags.item_id'=>'Shop_Item.id',
          'Shop_Tags.tag_id'=>'Tag.id'
          ) */
        )
    );

    public function getProperties() {
        if ($this->id == null)
            return null;
        if (isset(self::$_props[$this->id]))
            return self::$_props[$this->id];
        if ($this->group_id > 0)
            return (self::$_props[$this->id] = Shop_Properties::getProp('prop_' . $this->group_id, array('id' => $this->id), 1));
    }

    public function getPropstext($desc = '') {
        static $modelsi = array();
        if ($this->properties_text == '' && empty($desc)) {
            $props = $this->getProperties();
            if(empty($props)){
                $msg = "Empty properties for #$this->id\n";
                if(IS_CONSOLE)
                    echo $msg."\n";
                X3::log($msg,'kansha_error');
                return '';
            }
            $result = array();
            if(!isset($modelsi[$this->group_id]))
                $modelsi[$this->group_id] = $models = X3::db()->query("SELECT * FROM shop_proplist WHERE group_id=$this->group_id AND status");//Shop_Proplist::get(array('group_id' => $this->group_id,'status'));
            else
                $models = $modelsi[$this->group_id];
            if(!$models) return '';
            mysql_data_seek($models, 0);
            foreach ($props->_fields as $name => $f) {
                if ($name == 'id')
                    continue;
                if (stripos($f[0], 'string') !== false) {
                    $a = '';
                    while ($model = mysql_fetch_assoc($models))
                        if((int)$model['id'] == (int)$props->$name){
                            $a = $model['title']!=''?$model['title']:trim($model['value']);
                            //$a = preg_replace("/[иы]$/", "",$a);
                            break;
                        }
                    if ($a != '')
                        $result[] = $a;
                }
            }
            $this->properties_text = implode(', ', $result);
            $this->save();
        }elseif(!empty($desc))
            $this->prepareDescription($desc);
        $m = array();
        if(preg_match_all("/\{([^\}]+)\}/",$this->properties_text,$m)>0){
            foreach($m[0] as $i=>$m1){
                $condition = explode('|',$m[1][$i]);
                $cond = trim($condition[0]);
                if($cond!='')
                    $val = $condition[1];
                else
                    $val = $condition[2];
                $this->properties_text = str_replace($m1, $val, $this->properties_text);
            }
        }
        return $this->properties_text;
    }

    public function getGroup($id = null) {
        if (isset(self::$_groups[$this->id]))
            return self::$_groups[$this->id];
        else {
            return self::$_groups[$this->id] = Shop_Group::getByPk($this->group_id);
        }
    }

    public function groupTitle($id = null) {
        $g = false;
        if ($id == null)
            $g = isset(self::$_groups[$this->id]) ? self::$_groups[$this->id] : $this->getGroup();
        else {
            $g = Shop_Group::getByPk($id);
        }
        return ($g!=null)?$g->title:'';
    }

    public function withArticule() {
        if ($this->articule != '') {
            return " ($this->articule)";
        }
        return "";
    }
    
    public function getLink() {
        return '/'.strtolower(X3_String::create($this->title)->translit(false,"'")).($this->articule!=''?"_($this->articule)-":'-').$this->id;
    }

    public function manufacturerTitle($id = null) {
        $g = false;
        if ($id == null)
            $g = isset($this->_mans[$this->id]) ? $this->_mans[$this->id] : $this->getManufacturer();
        else {
            $g = $this->_mans[$this->id] = Manufacturer::getByPk($id);
        }
        return ($g!=null)?$g->title:'';
    }

    public function getManufacturer() {
        $man = null;
        if (isset($this->_mans[$this->id]))
            return $this->_mans[$this->id];
        else {
            if($this->manufacturer_id>0)
                $man = Manufacturer::getByPk($this->manufacturer_id);
            else{
                $mans = Manufacturer::get();
                foreach ($mans as $i=>$m){
                    if(stripos($this->title,$m->title)){
                        $man = $mans[$i];
                        break;
                    }
                }
            }
            if($man == null){
                Informer::add('У товара <a href="/admin/shop/edit/'.$this->id.'">'.$this->title.'</a> отсутствует производитель!', 'error');
            }
            return $this->_mans[$this->id] = $man;
        }
    }

    public function formSuffix($regexp=false,$suf = null) {
        $props = $this->getProperties();
        $pnames = X3::db()->query("SELECT * FROM shop_properties WHERE group_id=$this->group_id");//Shop_Properties::get(array('group_id'=>$this->group_id));
        if($regexp)
            $suf = '';
        elseif($suf === null)
            $suf = $this->getGroup()->suffix;
        while($prop = mysql_fetch_assoc($pnames)){
            $m = array();
            if(preg_match("/\[([@!]{0,2}){$prop['id']}\]/",$suf,$m)>0){
                if($prop['type'] == 'string' || $prop['type'] == 'content'){
                    $val = Shop_Proplist::getGently($prop['id']);
                }elseif($prop['type']=='decimal'){
                    $mm = mb_substr($prop['label'],$i=mb_strrpos($prop['label'], '('),mb_strrpos($prop['label'], ')')-$i);
                    $val = preg_replace("/[0]+$/", "",$props->{$prop['name']})."0 $mm";
                }elseif($prop['type'] == 'boolean'){
                    $val = ($props->{$prop['name']}==1)?$prop['label']:'';
                }else{
                    $mm = mb_substr($prop['label'],$i=mb_strrpos($prop['label'], '('),mb_strrpos($prop['label'], ')')-$i);
                    $val = $props->{$prop['name']} . " $mm";
                }
                if(!empty($m) && $m[1]!=''){
                    if(strpos($m[1], '!')!==false){
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
                    if(strpos($m[1], '@')!==false){
                        $val = X3_String::create($val)->toLowerCase();
                    }
                }
                $suf = str_replace($m[0], $val, $suf);
            }
        }
        $m = array();
        if(preg_match_all("/\[([@!]{0,2})(group|manufacturer|name|articule)\]/",$suf,$m)>0){
            foreach($m[0] as $i=>$mx){
                if(isset($m[2][$i]) && $m[2][$i] == 'group'){
                    $val = $this->groupTitle();
                }elseif(isset($m[2][$i]) && $m[2][$i] == 'manufacturer'){
                    $val = $this->manufacturerTitle();
                }elseif(isset($m[2][$i]) && $m[2][$i] == 'name'){
                    $val = $this->title;
                }elseif(isset($m[2][$i]) && $m[2][$i] == 'articule'){
                    $val = $this->articule!=''?'('.$this->articule.')':'';
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
                $suf = str_replace($mx, $val, $suf);
            }
            $m = array();
            if(preg_match_all("/\{([^\}]+)\}/",$suf,$m)>0){
                foreach($m[0] as $i=>$m1){
                    $condition = explode('|',$m[1][$i]);
                    if(trim($condition[0])!='')
                        $val = $condition[1];
                    else
                        $val = $condition[2];
                    $suf = str_replace($m1, $val, $suf);
                }
            }            
        }
        return trim($suf,'|');
    }
    
    public function uniqueModel($model = null, $props = null, $gid = null) {
        if ($model == null)
            $model = $this->title;
        if ($props == null)
            $props = $this->getProperties();
        if ($gid == null)
            $gid = $this->group_id;
        if ($props == null)
            return $model;
        foreach ($props->_fields as $n => $f) {
            //We search only repeatable strings, no need to replace a integer type
            // model = Canon L100, Pages per minute = 100; we get Canon L <- not right!
            if (strpos($f[0], 'string') === 0 || strpos($f[0], 'content') === 0) {
                $val = Shop_Proplist::getGentlyByName($n, $gid);
                $model = str_replace($val, "", $model);
            }
        }
        return preg_replace("/[\s]+/", " ", $model);
    }
    
    private function like($a, $s, $no = false,$strict = false,$union = 'AND',$sx = 0) {
        $c = '';
        if(!$strict)
            $c='%';
        $arr = explode(' ', $s);
        if($sx==1)
            $arr = array_map(function($item){return soundex($item);},$arr);
        if ($no)
            return "'$c" . implode("%' $union $a LIKE '%", $arr) . "$c'";
        else
            return "$a LIKE '$c" . implode("%' $union $a LIKE '%", $arr) . "$c'";
    }
    
    public function searchItem($search=null,$return=false,$strict=false,$union='AND') {
        $sql = X3::db()->validateSQL(trim($search));
        $sql = str_replace(array("для ","на ","в ","из ","от ","к "), " ", $sql);
        $sql = preg_replace("/[\s]+/", " ", $sql);        
        //$q = X3::db()->query("SELECT item_id FROM search_item WHERE (".$this->like('title', $sql, 0,false,'AND').") OR (".$this->like('title', str_replace("_"," ",X3_String::create($sql)->translit()), 0,false,'AND',1).")");
        $q = X3::db()->query("SELECT item_id FROM search_item WHERE (".$this->like('title', $sql, 0,false,'AND').")");
        $ids = array();
        $scope = array();
        if($q){
            while($id = mysql_fetch_assoc($q)){
                $ids[] = $id['item_id'];
            }
            //var_dump($ids);exit;
            //$q = X3::db()->query("SELECT item_id FROM search_item WHERE ".$this->like('title', $sql, 0,false,'OR'));
            //while($id = mysql_fetch_assoc($q)){
                //$ids[] = $id['item_id'];
            //}
            $scope['@condition']=array('shop_item.id'=>array('IN'=>"(".implode(',',$ids).")"),
                'company_item.price'=>array('@@'=>'(SELECT COUNT(0) FROM company_item WHERE company_item.item_id=shop_item.id)>0'));
        }
        return $scope;
    }

    public function search($search=null,$return=false,$strict=false,$union='AND') {
        if($search == null && isset($_POST['search'])){
            $search = $_POST['search'];
        }
        if (!empty($search)) {
            $sql = X3::db()->validateSQL(trim($search));
            //$sql = str_replace(array("для ","на ","в ","из ","от ","к "), "", $sql);
            //$sql = preg_replace("/[а-яА-Я]+/", "", $sql);
            $sql = trim(preg_replace("/[\s]+/", " ", $sql));
            $rsql = str_replace(" ", "|", $sql);
            $art = array();
            //$articule = false;
            /*if(preg_match("/\((.+?)\)/", $sql,$art)>0){
                $articule = $art[1];
                $sql = trim(str_replace($art[0], "", $sql));
            }*/
            
            $sql = preg_replace("/[\(\)\[\]\{\}]/",'',$sql);
            $add = '';
            //var_dump($sql);exit;
            if(X3::user()->adminGroup > 0)
                $add = ' AND sp.group_id='.X3::user()->adminGroup;
            $proplist = X3::db()->fetchAll("SELECT pl.id AS id, name, pl.group_id AS group_id  FROM shop_properties sp INNER JOIN shop_proplist pl ON sp.id=pl.property_id WHERE (" . $this->like('pl.value', $sql,false,$strict,$union) . " OR " . $this->like('pl.title', $sql,false,$strict,$union) . ") AND sp.group_id=pl.group_id{$add}");
//Shop_Properties::get(array('@join'=>array('table'=>'shop_proplist','on'=>array('value'=>array('REGEXP'=>$rsql)))));
            $scope['@condition'][0] = array(
                array('shop_item.title' => array('LIKE' => $this->like('shop_item.title', $sql, true,$strict,$union))),
                array('shop_item.suffix' => array('LIKE' => $this->like('shop_item.suffix', $sql, true,$strict))),
                array('shop_item.model_name' => array('LIKE' => $this->like('shop_item.model_name', $sql, true,$strict,$union))),
                array('shop_item.manufacturer_id' => array('IN' => "(SELECT id FROM manufacturer WHERE " . $this->like('title', $sql,false,$strict) . ")",'OR')),
                array('shop_item.articule' => array('LIKE' => $this->like('shop_item.articule', $sql, true,$strict,$union)))
            );
            if (count($proplist) > 0) {
                $ps=array();
                $_SPROS = array();
                foreach ($proplist as $key => $value) {
                    if(X3::user()->adminGroup > 0 && X3::user()->adminGroup != $value['group_id']) continue;
                    if(!isset($_SPROS[$value['group_id']]))
                        $_SPROS[$value['group_id']] = Shop_Properties::getProp("prop_{$value['group_id']}", array(),1);
                    if(isset($_SPROS[$value['group_id']]->_fields[$value['name']]))
                        $ps[$value['group_id']][] = "`{$value['name']}`='{$value['id']}'";
                }
                unset($_SPROS);
                foreach ($ps as $gid => $prs) {
                    $propnames = X3::db()->fetchAll("SELECT name FROM shop_properties WHERE group_id={$gid} AND `type`<>'string' AND `type`<>'content'");
                    $q = array();
                    foreach($propnames as $name)
                        $q[] = $this->like($name['name'],$sql,false,false,'OR');
                    $q = implode(') OR (', $q);
                    if($q!='')
                        $q = ' OR ((' . $q . '))';
                    $scope['@condition'][0][] = array('shop_item.id' => array('IN' => "(SELECT id FROM prop_$gid WHERE (" . implode(" AND ", $prs) . ")$q)"));
                }
            }
            if(X3::user()->adminGroup > 0)
                $scope['@condition']['shop_item.group_id'] = X3::user()->adminGroup;
            else
                $scope['@order']='shop_item.group_id';
            
            if($return === 'query')
                return $scope;
            $models = self::get($scope);
            if($return === true || $return == 'json'){
                if($return === 'json')
                    return json_encode ($models->toArray());
                return $models;
            }
            X3::profile()->begin('Shop-Search', true);
            if ($models->count() > 0) {
                $html = '<div title="Товары в базе">';
                $group = '';
                foreach ($models as $model) {
                    if(X3::user()->adminGroup > 0 && $model->group_id != X3::user()->adminGroup) continue;
                    if($model->groupTitle()!=$group){
                        $group = $model->groupTitle();
                        $html .= "<p style=\"clear:right\"><a style=\"font:bold 16px Verdana, sans-serif;color:#a5a5f6;\" href=\"/admin/shopgroup/edit/$model->group_id\" target=\"_blank\">$group</a></p>";
                    }
                    $html .= "<p style=\"clear:right;margin-left:15px;\"><a href=\"/admin/shop/edit/$model->id\">" . $model->title . $model->withArticule() . " </a>
                        <a style=\"float:right;margin-right:300px\" href=\"/admin/shop/clone/$model->id\"><img title=\"Клонировать\" src=\"/application/modules/Admin/images/buttons/clone.png\" /></a></p>
                    ";
                }
                $html .= "</div>";
                echo $html=='<div title="Товары в базе"></div>'?'':$html;
            }
            X3::profile()->end('Shop-Search');
        }
        exit;
    }

    public function actionShopsave() {
        if (isset($_POST['Shop']) && !X3::user()->isGuest()) {
            $shop = $_POST['Shop'];
            $gr = $_POST['Group'];
            $props = $_POST['prop'];
            $shop['articule'] = trim($shop['articule']);
            if ($shop['id'] > 0) {
                if (NULL === ($model = Shop_Item::getByPk($shop['id'])))
                    $model = new Shop_Item();
                unset($shop['id']);
            }else
                $model = new Shop_Item();
            //check if new record or not
            $next_id = null;
            if(isset($_POST['continue']) && $model->id>0){
                $qqq = array('@order' => 'created_at DESC, id DESC, weight',
                    '@condition'=>array('id'=>array('<'=>$model->id)));
                if(X3::user()->adminGroup != null)
                    $qqq['@condition']['group_id']=X3::user()->adminGroup;
                
                $ms = Shop_Item::get($qqq,1);
                if($ms != null)
                    $next_id = $ms->id;
            }
            $shopurl = $shop['imageurl'];
            unset($shop['imageurl']);
            $model->acquire($shop);
            $model->isnew = (isset($shop['isnew'])?1:0);
            $model->istop = (isset($shop['istop'])?1:0);
            if (isset($_POST['imagedel']) && isset($shop['image_source'])) {
                Uploads::cleanUp($model, $shop['image_source']);
                $model->image = NULL;
            } else {
                if (!empty($shopurl)) {
                    $url = preg_replace("/\&.+$/", "", $shopurl);
                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                    if($ext=='') $ext = 'jpg';
                    $filename = 'Shop_Item-' . time() . "." . $ext;
                    @file_put_contents('uploads/Shop_Item/' . $filename, file_get_contents($url));
                    $model->image = $filename;
                } else {
                    $h = new Upload($model, 'image');
                    if (!$h->source && !$h->save())
                        $model->image = NULL;
                }
            }
            $model->properties_text = '';
            if ($shop['articule'] == '')
                $shop['articule'] = null;
            if (isset($_POST['manufacturer_name'])) {
                $man = Manufacturer::get(array('name' => $_POST['manufacturer_name']), 1);
                if ($man != null)
                    $model->manufacturer_id = $man->id;
            }
            $found = false;
            //group properties to store
            $gprops = array();
            //fields to generate properties table
            $fields = array('id' => array('integer[10]', 'unsigned', 'primary', 'ref' => array('Shop_Item' => 'id')));
            //values of item
            $values = array();
            //shop_properties data
            $properties = array();
            foreach ($props as $key => $value) {
                $key = Shop_Properties::parseKey($value['original']);
                $atr="";
                if($value['type']=='decimal'){
                    $n = 7;//strlen(pathinfo($value['value'],PATHINFO_EXTENSION));
                    $m = 9 + $n;
                    $atr = "[$m,$n]";
                }
                $gprops[$key] = $value['label'];
                $fields[$key] = array($value['type'].$atr, 'default' => 'NULL');
                $properties[$key] = array('name' => $key,'group_id'=>((int)$gr['id']>0?$gr['id']:null) ,'label' => $value['label'], 'type' => $value['type'], 'weight' => $value['weight'], 'status' => (int) isset($value['status']));
                if ($value['type'] == 'boolean')
                    $values[$key] = array('value' => $value['value'], 'skip');
                elseif ($value['type'] == 'integer')
                    $values[$key] = array('value' => $value['value'], 'skip');
                elseif ($value['type'] == 'decimal')
                    $values[$key] = array('value' => $value['value'], 'skip');
                elseif ($value['type'] == 'string' || $value['type'] == 'content')
                    $values[$key] = array('value' => X3::db()->validateSQL($value['value']), 'soundex' => md5($value['value']),'group_id'=>((int)$gr['id']>0?$gr['id']:null));
            }
            try {
                $group = null;
                $group_id = (int) $gr['id'];
                if (!$group_id > 0) {
                    $group = new Shop_Group();
                    $group->title = $gr['title'];
                    //$group->properties = $gprops;
                    if (!$group->save())
                        throw new X3_Exception('Group error:' . print_r($group->table->getErrors(), 1) . " db:" . X3::db()->getErrors(), 500);
                    $group_id = $group->id;
                    $grabberg = new Grabber_Groups();
                    $grabberg->group_id = $group_id;
                    $grabberg->grabber_id = X3::user()->grabber == null ? 1 : X3::user()->grabber;
                    $grabberg->value = $_POST['Grabber']['hid'];
                    $grabberg->save();
                }else {
                    $group = Shop_Group::getByPk($group_id);
                    if ($group->title != $gr['title']) {
                        $group->title = $gr['title'];
                        $group->save();
                    }
                }
                if (!($ggid = Grabber::getGroupId($_POST['Grabber']['hid'])) > 0 || $ggid != $group_id) {
                    $grabberg = new Grabber_Groups();
                    $grabberg->group_id = $group_id;
                    $grabberg->grabber_id = X3::user()->grabber == null ? 1 : X3::user()->grabber;
                    $grabberg->value = $_POST['Grabber']['hid'];
                    $grabberg->save();
                }
                $model->group_id = $group_id;
                if ($model->save()) {
                   if(isset($_POST['gensuffix'])){
                        $model->suffix = $model->formSuffix();
                        $model->save();
                    }
                   exec("php run.php shop cacheprop id=$model->id > /dev/null 2>&1 &");
                    //Lets store grabber link about model
                    if(isset($_POST['Grabber']['modelid']) && NULL === Grabber_Items::get(array('item_id'=>$model->id,'grabber_id'=>(X3::user()->grabber == null ? 1 : X3::user()->grabber)))){
                        $gitems = new Grabber_Items();
                        $gitems->grabber_id = X3::user()->grabber == null ? 1 : X3::user()->grabber;
                        $gitems->item_id = $model->id;
                        $gitems->value = $_POST['Grabber']['modelid'];
                        $gitems->save();
                    }
                    //storing group id in shop properties table
                    foreach ($properties as $key => &$p) {
                        $p['group_id'] = $group_id;
                        $values[$key]['group_id'] = $group_id;
                    }
                    Shop_Properties::update($properties,$values);
                    $values = Shop_Proplist::update($values);
                    if (FALSE !== ($_sp = Shop_Properties::getProp('prop_' . $group_id)))
                        $fields = array_extend($_sp->_fields, $fields);
                    $Tprops = new Shop_Properties('prop_' . $group_id, $fields);
                    if (NULL !== ($T = $Tprops->table->where('id=' . $model->id)->asObject(1))) {
                        $Tprops = $T;
                    }
                    foreach ($fields as $key => $value) {
                        if (isset($values[$key]))
                            $Tprops->$key = $values[$key]['value'];
                    }
                    $Tprops->id = $model->id;
                    if (!$Tprops->save())
                        throw new X3_Exception('Properties error:' . print_r($Tprops->table->getErrors(), 1) . " db:" . X3::db()->getErrors(), 500);
                }else
                    throw new X3_Exception('Items error:' . print_r($model->table->getErrors(), 1) . " db:" . X3::db()->getErrors(), 500);
            } catch (Exception $e) {
                //TODO: Store grab data for further update!!!
                //if($model->id>0)
                //    $model->delete()->where('id='.$model->id)->execute();
                X3::log("Ошибка сохранения таблицы свойств для группы '$group->title'. Произведен откат. [{$e->getMessage()}]", 'kansha_error');
                print_r($e);
                exit;
                //notify admin about error in panel
                //X3_Session::writeOnce('error','Ошибка сохранения. Смотрите лог.');
                //$this->redirect('/admin/shop');                        
            }
        }
        if (isset($_POST['continue'])){
            if($next_id>0)
                $this->redirect('/admin/shop/edit/'.$next_id);
            else
                $this->redirect('/admin/shop/add');
        }else{
            if(isset($_POST['stay'])){
                $this->redirect('/admin/shop');
            }else
                $this->redirect("/admin/shop/edit/$model->id");
        }
    }

    public function actionValidateshop() {
        if (!isset($_POST['Shop']) || !isset($_POST['prop'])) {
            echo json_encode(array('status' => 'ERROR', 'message' => $_POST)); //array('Ошибка')));
            exit;
        }
        $gid = isset($_POST['Group']['id']) ? (int) $_POST['Group']['id'] : 0;
        if(!empty($_POST['Shop']['articule']))
            $_POST['Shop']['articule'] = trim($_POST['Shop']['articule']);
        
        //editing
        if(isset($_POST['Shop']['id']) && $_POST['Shop']['id']>0){
            if(!empty($_POST['Shop']['articule'])){
                if(NULL !== ($m = Shop_Item::get(array('articule' => $_POST['Shop']['articule'], 'id' => array('<>' => "'{$_POST['Shop']['id']}'")), 1))){
                    $html = '<div title="Товары в базе">';
                    $group = '';
                    if ($m->groupTitle() != $group) {
                        $group = $m->groupTitle();
                        $html .= "<p style=\"clear:right\"><a style=\"font:bold 16px Verdana, sans-serif;color:#a5a5f6;\" href=\"/admin/shopgroup/edit/$model->group_id\" target=\"_blank\">$group</a></p>";
                    }
                    $html .= "<p style=\"clear:right;margin-left:15px;\"><a href=\"/admin/shop/edit/$m->id\">" . $m->title . $m->withArticule() . " </a>
                            <a style=\"float:right;margin-right:300px\" href=\"/admin/shop/clone/$m->id\"><img title=\"Клонировать\" src=\"/application/modules/Admin/images/buttons/clone.png\" /></a></p>
                        ";
                    $html .= "</div>";
                    $html = $html == '<div title="Товары в базе"></div>' ? '' : $html;                
                    echo json_encode(array('status' => 'ERROR', 'message' => array('articule' => 'Товар с таким артикулем уже существует ' . $m->title),'result'=>$html));
                    exit;
                }
            }
        }
        //new item with articule
        if(!isset($_POST['Shop']['id'])){
            if (!empty($_POST['Shop']['articule']) && NULL !== ($m=Shop_Item::get(array('articule' => $_POST['Shop']['articule']), 1))) {
                    $html = '<div title="Товары в базе">';
                    $group = '';
                    if ($m->groupTitle() != $group) {
                        $group = $m->groupTitle();
                        $html .= "<p style=\"clear:right\"><a style=\"font:bold 16px Verdana, sans-serif;color:#a5a5f6;\" href=\"/admin/shopgroup/edit/$model->group_id\" target=\"_blank\">$group</a></p>";
                    }
                    $html .= "<p style=\"clear:right;margin-left:15px;\"><a href=\"/admin/shop/edit/$m->id\">" . $m->title . $m->withArticule() . " </a>
                            <a style=\"float:right;margin-right:300px\" href=\"/admin/shop/clone/$m->id\"><img title=\"Клонировать\" src=\"/application/modules/Admin/images/buttons/clone.png\" /></a></p>
                        ";
                    $html .= "</div>";
                    $html = $html == '<div title="Товары в базе"></div>' ? '' : $html;                
                echo json_encode(array('status' => 'ERROR', 'message' => array('articule' => 'Товар с таким артикулем уже существует ' . $m->title),'result'=>$html));
                exit;
            }
            //checking props
            $props = $_POST['prop'];
            $title = trim($_POST['Shop']['title'],' \\/|,.?:;!@#$%^&*-_=+');
            $title = strip_tags($title);
            //$title = mysql_real_escape_string($title);
            $models = $this->search($title, true,false);
            //new item same title...
            if ($gid>0 && $models->count()>0){//NULL !== ($i = Shop_Item::get(array('title' => array('LIKE' => "'{$_POST['Shop']['title']}'")), 1))) {
                foreach ($models as $model){
                    $j = Shop_Properties::getProp("prop_$gid", array('id'=>$model->id),1);
                    $counter = count($props)-1; //countdown counter without `id`
                    //echo $counter."\n";
                    foreach ($props as $key => $value) {
                        $key = Shop_Properties::parseKey($value['original']);
                        if(!isset($j->_fields[$key])) {
                            //echo "$key DOES NOT EXISTS!!!! \n";
                            continue;
                        }else{
                            //echo "$key ->".$j->$key . '=' . $value['value'];
                        }
                        if ($value['type'] == 'string' || $value['type'] == 'content'){
                            $X = Shop_Proplist::get(array(array(array('value' => $value['value']),array('synonymous'=>array('LIKE'=>"'%;{$value['value']};%'"))),'group_id'=>$gid), 1);
                            $query[$key] = ($X===NULL)?NULL:$X->id;
                            if(($X != null && $j->$key == $X->id) || ($X===null && $j->$key === NULL)){
                                $counter--;
                                //echo " === OK\n";
                            }
                            //else echo " === FAILc!!!!!!!!\n";
                        }
                        elseif ($value['type'] == 'boolean' || $value['type'] == 'integer'){
                            $query[$key] = (int)$value['value'];
                            if((int)$j->$key == (int)$value['value']){
                                $counter--;
                                //echo " === OK\n";
                            }
                            //else echo " === FAIL!!!!!!!!\n";
                        }elseif ($value['type'] == 'decimal'){
                            $query[$key] = array('LIKE'=>"'{$value['value']}'");
                            if((double)$j->$key == (double)$value['value']){
                                $counter--;
                                //echo " === OK\n";
                            }
                            //else echo " === FAIL!!!!!!!!\n";
                        }else{
                            $query[$key] = "" . $value['value'];
                            if($j->$key == $value['value']){
                                $counter--;
                                //echo " === OK\n";
                            }
                            //else echo " === FAIL!!!!!!!!\n";
                        }
                    }
                    //echo $counter."\n";
                    if($counter <=0){
                        $html = '<div title="Товары в базе">';
                        $group = '';
                        $status = 'WARNING';
                        foreach ($models as $model) {
                            if(str_ireplace($model->title, "",$title)=="" && $_POST['Shop']['articule']==$model->articule) $status = 'ERROR';
                            if(X3::user()->adminGroup > 0 && $model->group_id != X3::user()->adminGroup) continue;
                            if($model->groupTitle()!=$group){
                                $group = $model->groupTitle();
                                $html .= "<p style=\"clear:right\"><a style=\"font:bold 16px Verdana, sans-serif;color:#a5a5f6;\" href=\"/admin/shopgroup/edit/$model->group_id\" target=\"_blank\">$group</a></p>";
                            }
                            $html .= "<p style=\"clear:right;margin-left:15px;\"><a href=\"/admin/shop/edit/$model->id\">" . $model->title . $model->withArticule() . " </a>
                                <a style=\"float:right;margin-right:300px\" href=\"/admin/shop/clone/$model->id\"><img title=\"Клонировать\" src=\"/application/modules/Admin/images/buttons/clone.png\" /></a></p>
                            ";
                        }
                        $html .= "</div>";
                        $html = $html=='<div title="Товары в базе"></div>'?'':$html;                        
                        echo json_encode(array('status' => $status, 'message' => array('title' => 'Такой товар уже существует!'),'result'=>$html));
                        exit;
                    }
                }
            }
            $a = new Shop_Item();
            $a->acquire($_POST['Shop']);
            if ($_POST['Shop']['articule'] == '')
                $a->table->setAttribute('articule', NULL);
            if($a->group_id==null) $a->group_id = 1;
            if (!$a->validate()) {
                $err = array();
                $E = $a->table->getErrors();
                foreach ($E as $f => $e) {
                    $err[$f] = $e[0];
                }
                echo json_encode(array('status' => 'ERROR', 'message' => $err));
            }
        }
        echo json_encode(array('status' => 'OK', 'message' => ''));
        exit;
    }

    /**
     * USER SIDE FUNCTIONAL
     * 
     * @throws X3_404
     */
    public function actionShow() {
        if (!isset($_GET['id'])) throw new X3_404();
        $id = (int)$_GET['id'];
        $model = Shop_Item::getByPk($id);
        if ($model === NULL)
            throw new X3_404();
        Shop_Stat::log($id);
        //prepare
        X3::user()->group_id = $model->group_id;
        $prop = $model->getProperties();
        $pids = X3::db()->query("SELECT id,name,type,label FROM shop_properties sp WHERE isgroup AND group_id=$model->group_id");
        if(is_resource($pids) && mysql_num_rows($pids)>0){
            while($pid = mysql_fetch_assoc($pids)){
                if(isset($prop->{$pid['name']}) && $prop->{$pid['name']}>0){
                    if($pid['type'] == 'string'){
                        X3::user()->property_id = X3::app()->property = $prop->{$pid['name']};
                        break;
                    }
                    if($pid['type'] == 'boolean'){
                        X3::user()->property_id = X3::app()->property = $pid['id'];
                        break;
                    }
                }
            }
        }else
            X3::user()->property_id = null;
        
        $image = $model->image;
        if(strpos($image, 'http://')!==0){
            $image = '/uploads/Shop_Item/300x300xf/'.$image;
        }else
            $image = preg_replace ("/\&.+$/", "", $image);
        $modelTitle=$model->title . $model->withArticule();//." ".$model->formSuffix();
        $mm = X3::db()->fetch("SELECT MIN(price) as `min`, MAX(price) as `max`, COUNT(0) as `cnt` FROM company_item INNER JOIN data_company dc ON dc.id=company_item.company_id WHERE dc.status AND price>0 AND item_id='$model->id'");
        $fcount = X3::db()->fetch("SELECT COUNT(0) AS `cnt`, SUM(`rank`) as `summ` FROM shop_feedback WHERE status AND item_id=$model->id");
        $scount = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_service WHERE groups LIKE '%\"$model->group_id\"%'");
        $group = $model->getGroup();
        $bread = Breadcrumbs::items($model);
        
        $gtitle = X3::app()->groupTitle;
        $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
        $ml = explode("\n",$ml);
        foreach($ml as $Mm){
            $Mm = trim($Mm,"\r\n\ ");
            $Mm = array_map(function($item){return $item;},explode('=',$Mm));
            $gtitle = str_replace($Mm[0],$Mm[1],$gtitle);
        }
        if(preg_match("/[иы]$/", $gtitle)>0){
            $gtitle = mb_substr($gtitle, 0,-1,X3::app()->encoding);
        }        
        $title = $gtitle . " " . ($group->usename==='1'?($model->title.$model->withArticule()):$model->suffix);
        $group->prepareMeta($model);
        $bread[] = array('/'.X3::app()->request->url . '.html'=>$modelTitle);
        //statistics
        $cs = X3::db()->query("SELECT * FROM company_item WHERE price>0 AND item_id=$model->id GROUP BY company_id");
        while($c = mysql_fetch_assoc($cs)){
            Company_ItemStat::log($model->id, $c['company_id']);
        }
        SeoHelper::setMeta($model->metatitle, $model->metakeywords, $model->metadescription);
        $this->template->render('show', array('model' => $model, 'bread' => $bread,'image'=>$image,'modelTitle'=>$modelTitle,'mm'=>$mm,'title'=>$title,'type'=>'chars','fcount'=>(int)$fcount['cnt'],'rank'=>$fcount['summ'],'scount'=>(int)$scount['cnt']));
    }
    
    public function actionPrices() {
        if(IS_AJAX){
            if(isset($_POST['city'])){
                X3::user()->city = (int)$_POST['city'];
            }
            echo 'OK';
            exit;
        }
        $id = (int) $_GET['id'];
        if (!$id > 0)
            throw new X3_404();
        $model = Shop_Item::getByPk($id);
        if ($model === NULL)
            throw new X3_404();
        Shop_Stat::log($id);
        //prepare
        X3::user()->group_id = $model->group_id;
        $prop = $model->getProperties();
        $pids = X3::db()->query("SELECT id,name,type,label FROM shop_properties sp WHERE isgroup AND group_id=$model->group_id");
        if(is_resource($pids) && mysql_num_rows($pids)>0){
            while($pid = mysql_fetch_assoc($pids)){
                if(isset($prop->{$pid['name']}) && $prop->{$pid['name']}>0){
                    if($pid['type'] == 'string'){
                        X3::user()->property_id = X3::app()->property = $prop->{$pid['name']};
                        break;
                    }
                    if($pid['type'] == 'boolean'){
                        X3::user()->property_id = X3::app()->property = $pid['id'];
                        break;
                    }
                }
            }
        }else
            X3::user()->property_id = null;

        $image = $model->image;
        if(strpos($image, 'http://')!==0){
            $image = '/uploads/Shop_Item/300x300xf/'.$image;
            X3::app()->image = X3::app()->baseUrl . $image;
        }else
            $image = preg_replace ("/\&.+$/", "", $image);
        $modelTitle=$model->title . $model->withArticule();//." ".$model->formSuffix();
        $mm = X3::db()->fetch("SELECT MIN(price) as `min`, MAX(price) as `max`, COUNT(0) as `cnt` FROM company_item INNER JOIN data_company dc ON dc.id=company_item.company_id WHERE dc.status AND price>0 AND item_id='$model->id'");
        $fcount = X3::db()->fetch("SELECT COUNT(0) AS `cnt`, SUM(`rank`) as `summ` FROM shop_feedback WHERE status AND item_id=$model->id");
        $scount = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_service WHERE groups LIKE '%\"$model->group_id\"%'");        
        $group = $model->getGroup();
        //statistics
        $bread = Breadcrumbs::items($model);
        $cs = X3::db()->query("SELECT * FROM company_item WHERE price>0 AND item_id=$model->id GROUP BY company_id");
        while($c = mysql_fetch_assoc($cs)){
            Company_ItemStat::log($model->id, $c['company_id']);
        }        
        $title = "Купить " . ($group->usename==='1'?($model->title.$model->withArticule()):$model->suffix);                
        $group->prepareMeta($model,'price');
        $bread[] = array('/'.X3::app()->request->url . '.html'=>$modelTitle);
        SeoHelper::setMeta($model->metatitle, $model->metakeywords, $model->metadescription);
        X3::app()->ogdescription = $model->metadescription;
        $this->template->render('show', array('model' => $model, 'bread' => $bread,'image'=>$image,'modelTitle'=>$modelTitle,'mm'=>$mm,'title'=>$title,'type'=>'prices','fcount'=>(int)$fcount['cnt'],'scount'=>(int)$scount['cnt'],'rank'=>$fcount['summ']));
    }
    
    public function actionServices() {
        $id = (int) $_GET['id'];
        if (!$id > 0)
            throw new X3_404();
        $model = Shop_Item::getByPk($id);
        if ($model === NULL)
            throw new X3_404();
        Shop_Stat::log($id);
        //prepare
        X3::user()->group_id = $model->group_id;
        $prop = $model->getProperties();
        $pids = X3::db()->query("SELECT id,name,type,label FROM shop_properties sp WHERE isgroup AND group_id=$model->group_id");
        if(is_resource($pids) && mysql_num_rows($pids)>0){
            while($pid = mysql_fetch_assoc($pids)){
                if(isset($prop->{$pid['name']}) && $prop->{$pid['name']}>0){
                    if($pid['type'] == 'string'){
                        X3::user()->property_id = X3::app()->property = $prop->{$pid['name']};
                        break;
                    }
                    if($pid['type'] == 'boolean'){
                        X3::user()->property_id = X3::app()->property = $pid['id'];
                        break;
                    }
                }
            }
        }else
            X3::user()->property_id = null;

        $image = $model->image;
        if(strpos($image, 'http://')!==0){
            $image = '/uploads/Shop_Item/300x300xf/'.$image;
        }else
            $image = preg_replace ("/\&.+$/", "", $image);
        $modelTitle=$model->title . $model->withArticule();//." ".$model->formSuffix();
        $mm = X3::db()->fetch("SELECT MIN(price) as `min`, MAX(price) as `max`, COUNT(0) as `cnt` FROM company_item INNER JOIN data_company dc ON dc.id=company_item.company_id WHERE dc.status AND price>0 AND item_id='$model->id'");
        $fcount = X3::db()->fetch("SELECT COUNT(0) AS `cnt`, SUM(`rank`) as `summ` FROM shop_feedback WHERE status AND item_id=$model->id");
        $scount = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_service WHERE groups LIKE '%\"$model->group_id\"%'");        
        $group = $model->getGroup();
        //statistics
        $cs = X3::db()->query("SELECT * FROM company_item WHERE price>0 AND item_id=$model->id GROUP BY company_id");
        while($c = mysql_fetch_assoc($cs)){
            Company_ItemStat::log($model->id, $c['company_id']);
        }        
        $group->prepareMeta($model,'serv');
        SeoHelper::setMeta($model->metatitle, $model->metakeywords, $model->metadescription);
        $bread = Breadcrumbs::items($model);

        $bread[] = array('/'.X3::app()->request->url . '.html'=>$modelTitle);
        $title = ($group->usename==='1'?($model->title.$model->withArticule()):$model->suffix);                
        $this->template->render('show', array('model' => $model, 'bread' => $bread,'image'=>$image,'modelTitle'=>$modelTitle,'mm'=>$mm,'title'=>$title,'type'=>'services','fcount'=>(int)$fcount['cnt'],'scount'=>(int)$scount['cnt'],'rank'=>$fcount['summ']));
    }
    
    public function actionFeedback() {
        $id = (int) $_GET['id'];
        if (!$id > 0)
            throw new X3_404();
        $model = Shop_Item::getByPk($id);
        if ($model === NULL)
            throw new X3_404();
        
        Shop_Stat::log($id);
        $feedback = new Shop_Feedback;
        $feedback->rank = -1;
        $nofeed = true;
        $errors = array();
        if(isset($_POST['realname']) && (!isset($_COOKIE[Shop_Feedback::KEY]) || $_COOKIE[Shop_Feedback::KEY] != $id) && (NULL===($xfeed = Shop_Feedback::get(array('ip'=>$_SERVER['REMOTE_ADDR'],'item_id'=>$id),1)))){
            $feedback->item_id = $id;
            $feedback->ip = $_SERVER['REMOTE_ADDR'];
            $feedback->name = $_POST['realname'];
            $feedback->plus = $_POST['plus'];
            $feedback->minus = $_POST['minus'];
            $feedback->long = $_POST['long'];
            $feedback->exp = $_POST['exp'];
            $feedback->rank = $_POST['rank'];
            $feedback->created_at = time();
            if($feedback->save()){
                $feedback->cookie = $id;
                $this->controller->refresh();
            }  else {
                $errors=$feedback->table->getErrors();
            }
        }elseif($xfeed !== null){
            $xfeed->cookie = $id;
        }elseif(isset($_POST['name'])){
            $nofeed = false;
        }
        //prepare
        X3::user()->group_id = $model->group_id;
        $prop = $model->getProperties();
        $pids = X3::db()->query("SELECT id,name,type,label FROM shop_properties sp WHERE isgroup AND group_id=$model->group_id");
        if(is_resource($pids) && mysql_num_rows($pids)>0){
            while($pid = mysql_fetch_assoc($pids)){
                if(isset($prop->{$pid['name']}) && $prop->{$pid['name']}>0){
                    if($pid['type'] == 'string'){
                        X3::user()->property_id = X3::app()->property = $prop->{$pid['name']};
                        break;
                    }
                    if($pid['type'] == 'boolean'){
                        X3::user()->property_id = X3::app()->property = $pid['id'];
                        break;
                    }
                }
            }
        }else
            X3::user()->property_id = null;

        $image = $model->image;
        if(strpos($image, 'http://')!==0){
            $image = '/uploads/Shop_Item/300x300xf/'.$image;
        }else
            $image = preg_replace ("/\&.+$/", "", $image);
        $modelTitle=$model->title . $model->withArticule();//." ".$model->formSuffix();
        $mm = X3::db()->fetch("SELECT MIN(price) as `min`, MAX(price) as `max`, COUNT(0) as `cnt` FROM company_item INNER JOIN data_company dc ON dc.id=company_item.company_id WHERE dc.status AND price>0 AND item_id='$model->id'");
        $fcount = X3::db()->fetch("SELECT COUNT(0) AS `cnt`, SUM(`rank`) as `summ` FROM shop_feedback WHERE status AND item_id=$model->id");
        $scount = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_service WHERE groups LIKE '%\"$model->group_id\"%'");        
        $group = $model->getGroup();
        //statistics
        $cs = X3::db()->query("SELECT * FROM company_item WHERE price>0 AND item_id=$model->id GROUP BY company_id");
        while($c = mysql_fetch_assoc($cs)){
            Company_ItemStat::log($model->id, $c['company_id']);
        }
        $group->prepareMeta($model,'feed');
        $bread = Breadcrumbs::items($model);
        
        $title = ($group->usename==='1'?($model->title.$model->withArticule()):$model->suffix);
        SeoHelper::setMeta($model->metatitle, $model->metakeywords, $model->metadescription);        
        $bread[] = array('/'.X3::app()->request->url . '.html'=>$modelTitle);
        $this->template->render('show', array('model' => $model, 'bread' => $bread,'image'=>$image,'modelTitle'=>$modelTitle,'mm'=>$mm,'title'=>$title,'type'=>'feedback','fcount'=>(int)$fcount['cnt'],'scount'=>(int)$scount['cnt'],'rank'=>$fcount['summ']
            ,'nofeed'=>$nofeed,'feedback'=>$feedback,'errors'=>$errors));
    }

    /**
     * 
     * DEFAULT OVERLOADED FUNCTIONS
     * 
     */
    public function getDefaultScope() {
        $scope = array('@order' => 'created_at DESC, id DESC, weight');
        if (isset($_GET['groups']) && $_GET['groups'] > 0) {
            if(X3::user()->adminGroup != $_GET['groups'])
                X3::user()->{"shop-page"} = 0;
            X3::user()->adminGroup = $_GET['groups'];
            $scope['@condition'] = array('group_id' => $_GET['groups']);
        }
        if(isset($_GET['groups']) && $_GET['groups'] == 0){
            X3::user()->adminGroup = null;
            unset(X3::user()->adminGroup);
            unset($_GET['groups']);
        }
        if(!isset($_GET['groups']) && X3::user()->adminGroup>0){
            $_GET['groups'] = X3::user()->adminGroup;
            $scope['@condition'] = array('group_id' => $_GET['groups']);
        }
        return $scope;
    }

    public function execClone() {
        $this->id = NULL;
        $this->table->setIsNewRecord(true);
    }
    
    public function prepareDescription($desc) {
        $item = $this->getProperties();
        $group = $this->getGroup();
        $props = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id=$this->group_id");
        $result = $desc;
        foreach($props as $prop){
            $m = array();
            if(preg_match("/\[([@!]{0,2}){$prop['id']}\]/",$result,$m)>0){
                if($prop['type'] == 'string' || $prop['type'] == 'content'){
                    $val = X3::db()->fetch("SELECT value, title FROM shop_proplist WHERE id={$item->{$prop['name']}}");
                    $val = $val['title']!=''?$val['title']:$val['value'];
                }elseif($prop['type']=='decimal'){
                    $i=mb_strrpos($prop['label'], '(')+1;
                    $mm = mb_substr($prop['label'],$i,mb_strrpos($prop['label'], ')')-$i);
                    $val = preg_replace("/[0]+$/", "",$item->{$prop['name']})."0 $mm";
                }elseif($prop['type'] == 'boolean'){
                    $val = ($item->{$prop['name']}==1)?$prop['label']:'';
                }else{
                    $i=mb_strrpos($prop['label'], '(');
                    $mm = trim(mb_substr($prop['label'],$i,mb_strrpos($prop['label'], ')')-$i),'() ');
                    $val = $item->{$prop['name']} . " $mm";
                }
                if(!empty($m) && $m[1]!=''){
                    if(strpos($m[1], '!')!==false){
                        if(preg_match("/[иы]$/", $val)>0){
                            $val = mb_substr($val, 0,-1,X3::app()->encoding);
                        }
                    }
                    if(strpos($m[1], '@')!==false){
                        $val = X3_String::create($val)->toLowerCase();
                    }
                }
                $result = str_replace($m[0], $val, $result);
            }
        }
        $m = array();
        if(preg_match_all("/\[([@!]{0,2})(group|manufacturer|name|articule)\]/",$result,$m)>0){
            foreach($m[0] as $i=>$mx){
                if(isset($m[2][$i]) && $m[2][$i] == 'group'){
                    $val = $group->title;
                }elseif(isset($m[2][$i]) && $m[2][$i] == 'manufacturer'){
                    $val = $this->manufacturerTitle();
                }elseif(isset($m[2][$i]) && $m[2][$i] == 'name'){
                    $val = $this->title;
                }elseif(isset($m[2][$i]) && $m[2][$i] == 'articule'){
                    $val = $this->articule;
                }else
                    $val = '';
                if(!empty($m) && $m[1][$i]!=''){
                    if(strpos($m[1][$i], '!')!==false){
                        if(preg_match("/[иы]$/", $val)>0){
                            $val = mb_substr($val, 0,-1,X3::app()->encoding);
                        }
                    }
                    if(strpos($m[1][$i], '@')!==false){
                        $val = X3_String::create($val)->toLowerCase();
                    }
                }
                $result = str_replace($mx, $val, $result);
            }
            $m = array();
            if(preg_match_all("/\{([^\}]+)\}/",$result,$m)>0){
                foreach($m[0] as $i=>$m1){
                    $condition = explode('|',$m[1][$i]);
                    if(trim($condition[0])!='')
                        $val = $condition[1];
                    else
                        $val = $condition[2];
                    $result = str_replace($m1, $val, $result);
                }
            }            
        }
        return $this->properties_text = $result;
    }
    
    
    public static function getCache($query=array(),$single=false,$asArray=false) {
        return self::get($query);//,$single,__CLASS__,true);
        $q = new X3_MySQL_Query(self::getInstance()->tableName);
        $sql = md5($q->formQuery($query)->buildSQL());
        $p = (int)X3::user()->Shop_GroupPage;
        $file = "$sql.$p.cache";
        $cache = new X3_Cache_File(array('filename'=>$file,'directory'=>'application/cache/items','noTriggers'=>true));
        $output = $cache->readCache();
        if(!$output){
            $items = self::get($query,$single,__CLASS__,true);
            $output = json_encode($items);
            $cache->writeCache($output);
        }else
            $items = json_decode($output,true);
        $models = new self;
        foreach($items as $item){
            $table = new X3_MySQL_Model($models->tableName,$models);
            $table->acquire($item);
            $models->push($table);
        }
        return $models;
    }
    
    public static function countByProperty($pid,$criteria = 'withPrice') {
        $prop = X3::db()->fetch("SELECT name,group_id FROM shop_properties sp INNER JOIN shop_proplist spl ON spl.property_id=sp.id WHERE spl.id=$pid LIMIT 1");
        if($prop == null)
            $prop = X3::db()->fetch("SELECT name,group_id FROM shop_properties sp WHERE sp.id=$pid LIMIT 1");
        if($prop == null)
            return false;
        $q1 = '';
        $q2 = '';
        if($criteria == 'withPrice'){
            $q1 = "INNER JOIN company_item ci ON ci.item_id=p.id";
            $q2 = " AND ci.price>0";
        }
        $c = X3::db()->fetch("SELECT COUNT(0) cnt FROM prop_{$prop['group_id']} p $q1 WHERE p.{$prop['name']}='$pid' $q2");
        return (int)$c['cnt'];
    }

    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            if (in_array("prop_" . $model->group_id, X3::db()->getSchema('tables')))
                Shop_Properties::deleteProp("prop_" . $model->group_id, array('id' => $model->id));
            Company_Item::delete(array('item_id' => $model->id));
            Shop_Stat::delete(array('item_id' => $model->id));
            Shop_Feedback::delete(array('item_id' => $model->id));
            Grabber_Items::delete(array('item_id'=>$model->id));
            Search_Item::delete(array('item_id'=>$model->id));
        }
        parent::onDelete($tables, $condition);
    }

    public function beforeValidate() {
        if ($this->created_at == 0) {
            $this->created_at = time();
        }
        $this->updated_at = time();
        if ($this->articule == '')
            $this->table->setAttribute('articule', NULL);
        return parent::beforeValidate();
    }

}

?>
