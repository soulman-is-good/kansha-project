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
class Company_ItemStat extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'company_itemstat';
    const KEY = 'Company_ItemStat_id';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company'=>'id','default'=>'title')),
        'item_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Item'=>'id','default'=>'title')),
        'ip'=>array('string[128]','default'=>'NULL'),
        'user_agent'=>array('text','default'=>'NULL'),
        'key'=>array('string[255]','default'=>'NULL'),
        'time'=>array('datetime','default'=>'0'),
        //UNUSED
        'cookie'=>array('integer','default'=>'NULL','unused'),
    );
    
    public function fieldNames() {
        return array(
            'company_id'=>'Компания',
            'item_id'=>'Товар',
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
        setcookie(self::KEY, json_encode($cookie), time()+86400, '/');//day
    }
    
    public static function log($mid,$cid) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if(stripos($ua,'bot')!==false) return false;
        //cookie
        if(isset($_COOKIE[self::KEY]) && in_array($mid.'|'.$cid, json_decode($_COOKIE[self::KEY]))){
            return false;
        }
        if(NULL !== ($m=  Company_ItemStat::get(array('ip'=>$ip,'item_id'=>$mid,'company_id'=>$cid),1))){
            if(!isset($_COOKIE[self::KEY]) || $m->cookie == null){
                $m->cookie = $mid.'|'.$cid;
            }
            return false;
        }
        $m = new self;
        $m->ip = $ip;
        $m->user_agent = $ua;
        $m->company_id = $cid;
        $m->item_id = $mid;
        $m->cookie = $mid.'|'.$cid;
        return $m->save();
    }
    
/**
 * 
 * DEFAULT OVERLOADED FUNCTIONS
 * 
 */
    
    public function getDefaultScope() {
        $scope = array('@order'=>'time DESC, id DESC, weight');
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
