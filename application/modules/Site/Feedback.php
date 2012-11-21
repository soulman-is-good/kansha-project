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
class Site_Feedback extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'data_feedback';
    const KEY = 'X3_Site_Feedback_id';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'ip'=>array('string[128]'),
        'name'=>array('string[255]'),
        'email'=>array('email'),
        'content'=>array('content'),
        'status'=>array('boolean','default'=>'0'),
        'created_at'=>array('datetime','default'=>'0'),
        //UNUSED
        'cookie'=>array('integer','default'=>'NULL','unused'),        
    );
    
    public function fieldNames() {
        return array(
            'ip'=>'IP',
            'name'=>'Имя',
            'email'=>'E-Mail',
            'content'=>'Отзыв',
            'status'=>'Утвержден'
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
        setcookie(self::KEY, json_encode($cookie), time()+86400, '/');//day
    }
    
    public static function log($mid) {
        $ip = $_SERVER['REMOTE_ADDR'];
        //cookie
        if(isset($_COOKIE[self::KEY]) && in_array($mid, json_decode($_COOKIE[self::KEY]))){
            return false;
        }
        if(NULL !== ($m=Shop_Stat::get(array('ip'=>$ip),1))){
            if(!isset($_COOKIE[self::KEY]) || $m->cookie == null){
                $m->cookie = $mid;
            }
            return false;
        }
        $m = new self;
        $m->ip = $ip;
        $m->cookie = $mid;
        return $m->save();
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
        return parent::beforeValidate();
    }
}

?>
