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
class User_Storage extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'user_storage';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'email'=>array('string[255]','orderable'),
        'name'=>array('string[255]','default'=>'-','orderable'),
        'action'=>array('string[255]','default'=>'','orderable'),
        'referer'=>array('string[255]','default'=>'','orderable'),
        'data'=>array('html','default'=>'[]'),
        'ip'=>array('string[32]','default'=>'NULL','orderable'),
        'time'=>array('datetime','default'=>'0','orderable'),
        'message'=>array('string[255]','default'=>'ok!','orderable'),
    );
    
    public function fieldNames() {
        return array(
            'ip'=>'IP',
            'name'=>'Имя',
            'email'=>'E-mail',
            'action'=>'Действие',
            'referer'=>'Ссылка',
            'time'=>'Время запроса',
            'message'=>'Статус',
        );
    }
    
    public function moduleTitle() {
        return 'Хранилище адресов';
    }

    public static function store($email,$name='-',$data=array(),$message = 'ok!') {
        $u = new self;
        $u->email = $email;
        $u->action = $_SERVER['REQUEST_URI'];
        $u->referer = $_SERVER['HTTP_REFERER'];
        $u->ip = $_SERVER['REMOTE_ADDR'];
        $u->time = time();
        $u->data = json_encode($data);
        $u->message = $message;
        $u->save();
    }
/**
 * 
 * DEFAULT OVERLOADED FUNCTIONS
 * 
 */
    
    public function getDefaultScope() {
        $scope = array('@order'=>'time DESC, id DESC');
        return $scope;
    }
    
    public function beforeValidate() {
        if($this->time==0){
            $this->time=time();
        } 
        if(is_array($this->data)){
            $this->data = json_encode($this->data);
        }
        return parent::beforeValidate();
    }
}

?>
