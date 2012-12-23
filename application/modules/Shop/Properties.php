<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Properties
 *
 * @author Soul_man
 */
class Shop_Properties extends X3_Module_Table {
    public $encoding = 'UTF-8';
    public $tableName = 'shop_properties';
    
    private static $_props = array();

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'group_id'=>array('integer[10]','unsigned','primary','ref'=>array('Shop_Group'=>'id','default'=>'title')),
        'isgroup'=>array('boolean','default'=>'0'),
        'name'=>array('string[70]','index'),
        'label'=>array('string[255]'),
        'type'=>array('string[255]'),
        'synonymous'=>array('text','default'=>'NULL'),
        'weight'=>array('integer[10]','unsigned','default'=>'0'),
        'showinurl'=>array('boolean','default'=>'0'),
        'multifilter'=>array('boolean','default'=>'0'),
        'status'=>array('boolean','default'=>'1'),
    );
    
    public function __construct($tableName=null,$fields=null) {        
        if(($tableName == null && is_null($fields)) || $tableName=='updateprops'){
            return parent::__construct($tableName);
        }else{
            $this->tableName = $tableName;
            if($fields == null && in_array($tableName, X3::db()->getSchema('tables'))){
                $cols = X3::db()->getSchema('columns',$tableName);
                foreach($cols as $col){
                    if($col['Field']=="") continue;
                    $name = $col['Field'];
                    $type = $this->parseType($col['Type']);
                    $other = $type['other'];
                    $type = $type['type'];
                    $default = false;
                    if($col['Null'] == 'YES'){
                        $default = 'NULL';
                    }else if($col['Default']!=''){
                        $default = $col['Default'];
                    }
                    $fields[$name][] = $type;
                    if($other)
                        $fields[$name][] = $other;
                    if($default!==false)
                        $fields[$name]['default'] = $default;
                    if($col['Extra']=='auto_increment')
                        $fields[$name][] = 'auto_increment';
                    if($col['Key']=='PRI')
                        $fields[$name][] = 'primary';
                    if($col['Key']=='MUL')
                        $fields[$name][] = 'index';
                    if($col['Key']=='UNI')
                        $fields[$name][] = 'unique';
                }
                $this->_fields = $fields;
            }elseif(is_array($fields)){
                $this->_fields = $fields;
            }else 
                return false;
        }
        return parent::__construct(null);
    }
    
    public function compare($fields,$class) {
            echo '<table width="100%"><tr><td>';
            echo '<pre>'.print_r($fields,1).'</pre>';
            echo '</td><td>';
            echo '<pre>'.print_r(X3_Module_Table::getInstance($class)->_fields,1).'</pre>';
            echo '</td></tr></table>';
            exit;        
    }
    
    public function parseType($type) {
        $count = NULL;
        $other = NULL;
        $reg = "/^([^\(]+?)\(([^\)]+?)\)(.*)$/";
        $m = array();
        if(preg_match($reg, $type,$m)>0){
            $type = $m[1];
            $count = $m[2];
            if(isset($m[3]) && $m[3]!="") $other = trim($m[3]);
        }else{
            $type = 'content';
        }
        switch ($type) {
            case "varchar":
                $type="string";
                if($count!==null) $type = "{$type}[{$count}]";
                break;
            case "boolean":
            case "tinyint":
                $type="boolean";
                break;
            case "int":
                $type="integer";
                if($count!==null) $type = "{$type}[{$count}]";
                break;
            case "decimal":
            case "real":
            case "double":
            case "float":
                if($count!==null) $type = "{$type}[{$count}]";
                break;
            default:
                $type = "string";
                if($count!==null) $type = "{$type}[{$count}]";
                break;
        }
        return array('type'=>$type,'other'=>$other);
    }    
    
    public static function update($props,&$values=false) {
        //X3::db()->startTransaction();
        foreach ($props as $key=>$prop){
            $new = false;
            if(NULL === ($e = self::get(array(array(array('name'=>$prop['name']),array('synonymous'=>array('LIKE'=>"'%{$prop['name']};%'"))),'group_id'=>$prop['group_id']),1))){
                $e = new self();
                $new = true;
            }  else {
                unset($prop['id']);
                unset($prop['name']);
                if($values!==false && $prop['type']!=$e->type){
                    //typecasting
                    //TODO: background. Write as is(unset($prop['type'])), and then run script;
                    //to string
                    if($prop['type']=='string'){
                        $values[$e->name] = array('value' => X3::db()->validateSQL($values[$e->name]['value']), 'soundex' => md5($values[$e->name]['value']));
                        $vals = X3::db()->query("SELECT `id`, `$e->name` FROM prop_{$prop['group_id']}");
                        if(is_resource($vals) && mysql_num_rows($vals)>0){
                            $plist = array();
                            while($val = mysql_fetch_assoc($vals)){
                                if($val[$e->name]!=0){
                                    $soex = X3_String::create($val[$e->name])->dmstring();
                                    if(!isset($plist[$val[$e->name]])){
                                        X3::db()->query("INSERT INTO shop_proplist (`group_id`,`property_id`,`soundex`,`title`,`value`) VALUES 
                                            ('{$prop['group_id']}','$e->id','$soex','{$val[$e->name]}','{$val[$e->name]}')");
                                        $plist["p".$val[$e->name]] = mysql_insert_id();
                                    }
                                }else
                                    $plist["p".$val[$e->name]] = 'NULL';
                                X3::db()->query("UPDATE TABLE prop_{$prop['group_id']} SET `$e->name`={$plist[$val[$e->name]]} WHERE id={$val['id']}");
                            }
                        }
                    }elseif($e->type=='string'){ //from string
                        $values[$e->name] = array('value' => $values[$e->name]['value'], 'skip');
                        $vals = X3::db()->fetchAll("SELECT `id`,`value` FROM shop_proplist WHERE group_id='$e->group_id' AND property_id='$e->id'");
                        X3::db()->startTransaction();
                        foreach($vals as $val)
                            X3::db()->addTransaction("UPDATE prop_$e->group_id SET `$e->name`='{$val['value']}' WHERE `$e->name`='{$val['id']}'");
                        if(!X3::db()->commit()){
                            X3_Session::writeOnce('error', "Ошибка смены типа данных");
                            X3::log(X3::db()->getErrors(),'kansha_error');
                        }else
                            X3::db()->query("DELETE FROM `shop_proplist` WHERE `property_id`='$e->id'");
                    }
                }
            }
            $e->getTable()->acquire($prop);
            if(!$e->save()){
                $err = print_r($e->table->getErrors(),1);
                X3::log("Ошибка сохранения свойста {$prop['name']} для группы '{$prop['group_id']}'. [{$err}]",'kansha_error');
                X3_Session::writeOnce('error','Ошибка сохранения. Смотрите лог.');                
            }else{
                $props[$key] = $e->id;
                if($new)
                    Informer::add("Добавлено новое свойство: <a title=\"Перейти к редактированию\" href=\"/admin/group/edit/$e->group_id\">'$e->label'</a>",'new_property',array('id'=>$e->id));
            }
        }
        //X3::db()->commit();
        return $props;
    }
    
    public function actionUpdateprops() {
        $sp = Shop_Properties::getByPk($_POST['Props']['id']);
        if($sp == null){
            echo json_encode(array('status'=>'ERROR','message'=>array('Нет свойства №'.$_POST['Props']['id'])));
            exit;
        }
        $errors = array();
        $sp->label = $_POST['Props']['value'];
        $sp->isgroup = isset($_POST['Props']['isgroup'])?1:0;
        $sp->status = isset($_POST['Props']['status'])?1:0;
        $sp->multifilter = isset($_POST['Props']['multifilter'])?1:0;
        if(!$sp->save())
            foreach($sp->table->getErrors() as $err){
                $errors[] = $err[0];
            }
        $ids = array();
        if(!empty($_POST['Plist']))
        foreach ($_POST['Plist'] as $pl){
            $spl = Shop_Proplist::getByPk($pl['id']);
            if($spl!=null){
                $spl->title = $pl['title'];
                $spl->value = $pl['value'];
                $spl->weight = $pl['weight'];
                $spl->status = isset($pl['status'])?1:0;
                $spl->description = $pl['description'];
                if(!$spl->save())
                    foreach($spl->table->getErrors() as $err){
                        $errors[] = $err[0];
                    }
            }
        }
        if(empty($errors))
            echo json_encode(array('status'=>'OK','message'=>$_POST['Props']['value']));
        else
            echo json_encode(array('status'=>'ERROR','message'=>$errors));
        exit;
    }
    
    public function fetchValues($group_id=null) {
        if($this->tableName == 'shop_properties') return $this->toArray();
        $values = array();
        if($group_id == null)
            $group_id = $this->group_id;
        foreach($this->_fields as $key=>$value){
            if($key == 'id') continue;
            //$prop = Shop_Properties::get(array('group_id'=>$group_id,'name'=>$key),1);
            $type = trim(preg_replace("/\[.+\]/", "",$value[0]));
            if($type=='string' || $type == 'content'){
                if($this->$key>0){
                    $val = $this->$key;
                    $val = X3::db()->fetch("SELECT value FROM shop_proplist WHERE id='$val' LIMIT 1");
                    $values[$key] = $val['value'];//Shop_Proplist::getByPk($this->$key)->value;
                }else
                    $values[$key] = NULL;
            }else{
                $values[$key] = $this->$key;
            }
        }
        return $values;
    }
    
    public static function parseKey($key) {
        $key = preg_replace("/[\(\)\[\]]/","",X3_String::create($key)->translit());
        $key = preg_replace("/^[\s]+?|[^0-9a-zA-Z_\-!@#$%\^\&\*\-\s=\\|\/\?\<\>\{\}\+]+?|[\s]+?$/","",$key);
        $key = str_replace(array(
            '!', '@','#','$','%','^','&','*','-',' ','=','\\','|','/','?','<','>',']','[','{','}','+'
        ), array(
            '_es','_et','_r','_d','_p','_pp','_am','_s','_m','__','_e','_s','_vs','_bs','_q','_bl','_br','_sl','_sr','_fl','_fr','_pl'
        ), $key);
        $key = strtolower($key);
        if(($c=strlen($key))>64){
            $sm = 0;
            for($i=0;$i<$c;$i++){
                $sm +=  ord(substr($key, $i,1));
            }
            $key = substr($key, 0, 60) . dechex($sm);
        }
        return $key;
    }
    
    public static function getInstance($tableName) {
        if(isset(self::$_props[$tableName]))
            return self::$_props[$tableName];
        return self::$_props[$tableName]=new self($tableName);
    }
    
    public static function getProp($tableName,$query=array(),$single = false) {
        if(!in_array($tableName,X3::db()->getSchema('tables'))) return false;
        $i = self::getInstance($tableName);
        return $i->table->formQuery($query)->asObject($single);
    }
    
    public static function deleteProp($tableName,$query=array()) {
        if(!in_array($tableName,X3::db()->getSchema('tables'))) return false;
        $i = self::getInstance($tableName);
        return $i->table->delete()->formQuery($query)->execute();
    }
    
    public static function drop($tableName) {
        if(!in_array($tableName,X3::db()->getSchema('tables'))) return false;
        return X3::db()->query("DROP TABLE `$tableName`");
    }
    
    public function afterSave($bNew = false) {
        $group_id = 0;
        if($this->tableName != 'shop_properties'){
            $group = Shop_Item::getByPk($this->id);
            if(isset($group)){
                $group = $group->getGroup();
                $group_id = $group->id;
            }
        }else{
            $group = Shop_Group::getByPk($this->group_id);
            $group_id = $this->group_id;
        }
        //X3::db()->query("UPDATE shop_item SET properties_text='' WHERE group_id=$group_id");
        //exec("php run.php shop regentitle group_id=$group_id > /dev/null 2>&1 &");
        $file = X3::app()->basePath . '/application/cache/'.  X3_String::create($group->title)->translit().".cache";
        if(is_file($file)){
            @unlink($file);
        }
        parent::afterSave($bNew);
    }
    
}

?>
