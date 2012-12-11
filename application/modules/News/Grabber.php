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
class News_Grabber extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'news_grabber';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'data'=>array('enum["Новости","Обзоры"]','default'=>'Новости'),
        'type'=>array('enum["RSS","Content"]','default'=>'RSS'),
        'url'=>array('string[255]'),
        'tag'=>array('string[255]','default'=>'NULL'),
        'regtitle'=>array('string[255]','default'=>'NULL'),
        'regimage'=>array('string[255]','default'=>'NULL'),
        'regdate'=>array('string[255]','default'=>'NULL'),
        'regtext'=>array('string[255]','default'=>'NULL'),
        'status'=>array('boolean','default'=>'1'),
        'created_at'=>array('datetime','default'=>'0'),
    );
    public function moduleTitle() {
        return 'Грабберы';
    }
    public function fieldNames() {
        return array(
            'data'=>'К модулю',
            'type'=>'Тип',
            'url'=>'Ссылка списка новостей',
            'tag'=>'Правило для ссылки',
            'regtitle'=>'Правило для заголовка',
            'regimage'=>'Правило для картинки',
            'regdate'=>'Правило для даты',
            'regtext'=>'Правило для текста',
            'status'=>'Активность',
        );
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
