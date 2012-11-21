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
class Shop_Feedback extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'shop_feedback';
    private static $_items = array();
    const KEY = 'X3_Shop_Feedback_id';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'item_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Item'=>'id','default'=>'title')),
        'ip'=>array('string[128]'),
        'name'=>array('string[255]'),
        'plus'=>array('string[255]'),
        'minus'=>array('string[255]'),
        'long'=>array('string[255]'),
        'exp'=>array('content'),
        'rank'=>array('integer[1]','default'=>'0'),
        'status'=>array('boolean','default'=>'0'),
        'created_at'=>array('datetime','default'=>'0'),
        //UNUSED
        'cookie'=>array('integer','default'=>'NULL','unused'),        
    );
    
    public function fieldNames() {
        return array(
            'ip'=>'IP',
            'item_id'=>'Товар',
            'name'=>'Имя',
            'plus'=>'Плюсы',
            'minus'=>'Минусы',
            'long'=>'Продолжительность использования',
            'exp'=>'Опыт использования',
            'rank'=>'Оценка',
        );
    }
       
    public function _getCookie() {
        if(isset($_COOKIE[self::KEY]))
            return json_decode($_COOKIE[self::KEY]);
        return null;
    }
    
    public function _setCookie($value) {
        if(isset($_COOKIE[self::KEY]))
            $cookie = json_decode($_COOKIE[self::KEY]);
        else $cookie = array();
        if(!is_array($cookie)) $cookie = array();
        $cookie[] = $value;
        setcookie(self::KEY, json_encode($cookie), time()+84600, '/');//day
    }
    
    public function log($mid) {
        $ip = $_SERVER['REMOTE_ADDR'];
        //cookie
        if(isset($_COOKIE[self::KEY]) && in_array($mid, json_decode($_COOKIE[self::KEY]))){
            return false;
        }
        if(NULL !== ($m=Shop_Stat::get(array('ip'=>$ip,'item_id'=>$mid),1))){
            if(!isset($_COOKIE[self::KEY]) || $m->cookie == null){
                $m->cookie = $mid;
            }
            return false;
        }
        $m = new self;
        $m->ip = $ip;
        $m->item_id = $mid;
        $m->cookie = $mid;
        return $m->save();
    }
    
    public function getItem() {
        if(isset(self::$_items[$this->item_id]))
            return self::$_items[$this->item_id];
        return self::$_items[$this->item_id] = Shop_Item::getByPk($this->item_id);
    }    
    
/**
 * 
 * DEFAULT OVERLOADED FUNCTIONS
 * 
 */
    
    public function getDefaultScope() {
        $scope = array('@order'=>'created_at DESC, id DESC');
        return $scope;
    }
        
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
        }
        parent::onDelete($tables,$condition);
    }    
    
    public function beforeValidate() {
        if($this->created_at==0){
            $this->created_at=time();
        }
        if($this->rank<0 || $this->rank>4)
            $this->addError ('rank', 'Поставте пожалуйста рейтинг.');
        return parent::beforeValidate();
    }
}

?>
