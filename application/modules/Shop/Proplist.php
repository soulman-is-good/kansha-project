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
class Shop_Proplist extends X3_Module_Table {
    public $encoding = 'UTF-8';
    public $tableName = 'shop_proplist';
    public static $_proplist = array();

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'group_id'=>array('integer[10]','unsigned','primary','ref'=>array('Shop_Group'=>'id','default'=>'title')),
        'property_id'=>array('string[255]'),
        'soundex'=>array('string[255]','default'=>'0'),
        'title'=>array('content','default'=>''),
        'value'=>array('content','default'=>''),
        'description'=>array('text','default'=>'NULL'),
        'synonymous'=>array('content','default'=>'NULL'),
        'weight'=>array('integer[10]','unsigned','default'=>'0'),
        'status'=>array('boolean','default'=>'1')
    );
   
    public static function update(&$props) {
        foreach ($props as $key=>$prop){
            if(in_array('skip', $prop))
                continue;
            if(empty($prop['value']) || trim($prop['value'])=='' || $prop['value']==null) {
                $props[$key]['value'] = NULL;
                continue;
            }
            if(NULL === ($p = Shop_Properties::get(array('name'=>$key,'group_id'=>$prop['group_id']),1))){
                continue;
            }
            if(is_null($prop['soundex']))
                $prop['soundex'] = md5($prop['value']);
            if(NULL === ($e = self::get(array('group_id'=>$prop['group_id'],'property_id'=>$p->id,array(array('soundex'=>$prop['soundex']),array('value'=>array('LIKE'=>"'{$prop['value']}'")))), 1)))
                $e = new self();
            $e->acquire($prop);
            if(empty($e->title)) $e->title = $e->value;
            $e->property_id = $p->id;
            if(!$e->save()){
                $err = print_r($e->table->getErrors(),1) . "db: ".X3::db()->getErrors();
                X3::log("Ошибка сохранения свойста {$prop['value']} для группы '{$prop['group_id']}'. [{$err}]",'kansha_error');
                X3_Session::writeOnce('error','Ошибка сохранения. Смотрите лог.');
            }else
                $props[$key]['value'] = $e->id;
        }
        return $props;
    }
    
    public static function getBySoundEx($value,$group_id) {
        if(is_numeric($value) || empty($value)) return NULL;
        $a = trim($value);
        $value = X3_String::create($value)->dmstring();
        if(!isset(self::$_proplist[$group_id])){
            self::$_proplist[$group_id] = Shop_Proplist::get(array('group_id'=>$group_id));
        }
        $props = self::$_proplist[$group_id];
        foreach ($props as $prop) {
            if($prop->soundex === $value || $prop->soundex == md5($prop['value']) || stripos($a,$prop->value)!==false || stripos($a,$prop->title)!==false)
                return $prop->id;
        }
        return NULL;
    }
    
    public static function getGently($id) {
        $m = X3::db()->fetch("SELECT `title`, `value` FROM `shop_proplist` WHERE `id`='$id'");
        if(!empty($m))
            return !empty($m['title'])?$m['title']:$m['value'];
        else 
            return false;
    }
    
    public static function getGentlyByName($name,$group_id) {
        $m = X3::db()->fetch("SELECT `value` FROM `shop_proplist` WHERE `id` IN (SELECT id FROM shop_properties WHERE `name`='$name' AND group_id='$group_id' LIMIT 1)");
        if(!empty($m))
            return $m['value'];
        else 
            return false;
    }
    
    public function actionJoin() {
        if(!X3::user()->isAdmin() || !IS_AJAX) throw new X3_404;
        $id = (int)$_POST['id'];
        $with = (int)$_POST['with'];
        if(!$id>0 || !$with>0) throw new X3_404;
        $prop2 = Shop_Proplist::getByPk($id);
        $prop1 = Shop_Proplist::getByPk($with);
        $syn = trim($prop1->synonymous,';');
        $syn = explode(';',$syn);
        $syn[] = $prop2->value;
        $prop1->synonymous = ';'.implode(';',$syn).';';
        $name = Shop_Properties::getByPk($prop1->property_id)->name;
        X3::db()->query("UPDATE prop_$prop1->group_id SET `$name`=$prop1->id WHERE `$name`=$prop2->id ");
        if($prop1->save()){
            X3::db()->query("DELETE FROM shop_proplist WHERE id=$prop2->id");
            echo 'OK';
        }else{
            echo print_r($prop1->table->getErrors(),1);
        }
        exit;
    }
    
    public static function parseKey($key) {
        $key = preg_replace("/(\(.*?\))|(\[.*?\])/","",X3_String::create($key)->translit());
        $key = preg_replace("/^[\s]+?|[^a-zA-Z_\-]+?|[\s]+?$/","",$key);
        $key = mb_strtolower($key,'UTF-8');
        return $key;
    }
    
    public function beforeSave() {
        //
    }
    
    //public static function getInstance($tableName) {
        //return new self($tableName);
    //}
    
}

?>
