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
class Banner_Stat extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'banner_stat';
    const KEY = 'X3_Banner_Stat_id';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'banner_id'=>array('integer[10]','unsigned','index','ref'=>array('Banner'=>'id','default'=>'title')),
        'ip'=>array('string[128]','default'=>'NULL'),
        'user_agent'=>array('text','default'=>'NULL'),
        'key'=>array('string[255]','default'=>'NULL'),
        'time'=>array('datetime','default'=>'0'),
        //UNUSED
        'cookie'=>array('integer','default'=>'NULL','unused'),
    );
    
    public function fieldNames() {
        return array(
            'banner_id'=>'Баннер',
            'ip'=>'IP',
            'user_agent'=>'Характеристики',
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
        setcookie(self::KEY, json_encode($cookie), time()+31536000, '/');//year
    }
    
    public static function log($mid) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if(stripos($ua,'bot')!==false) return false;
        //cookie
        if(isset($_COOKIE[self::KEY]) && in_array($mid, json_decode($_COOKIE[self::KEY]))){
            return false;
        }
        if(NULL !== ($m=Banner_Stat::get(array('ip'=>$ip,'banner_id'=>$mid),1))){
            if(!isset($_COOKIE[self::KEY]) || $m->cookie == null){
                $m->cookie = $mid;
            }
            return false;
        }
        $m = new self;
        $m->ip = $ip;
        $m->user_agent = $ua;
        $m->banner_id = $mid;
        $m->cookie = $mid;
        return $m->save();
    }
    
/**
 * 
 * DEFAULT OVERLOADED FUNCTIONS
 * 
 */
    
    public function getDefaultScope() {
        $scope = array('@order'=>'created_at DESC, id DESC, weight');
        if(isset($_GET['groups']) && $_GET['groups']>0){
            $scope['@condition'] = array('group_id'=>$_GET['groups']);
        }
        return $scope;
    }
        
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
        }
        parent::onDelete($tables,$condition);
    }    
    
    public function beforeValidate() {
        if($this->time==0){
            $this->time=time();
        } 
        if(empty($this->key) && !empty($this->ip) && !empty($this->user_agent))
            $this->key = md5($this->ip.strtolower ($this->user_agent));
        return parent::beforeValidate();
    }
}

?>
