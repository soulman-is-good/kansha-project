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
class ShopGroupTmp extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'tmp_shopgroup';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'title'=>array('string[255]'),
        'properties'=>array('content','default'=>'{}'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[5]','unsigned','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'image'=>'Фото',
            'status'=>'Видимость',
            'weight'=>'Порядок',
        );
    }

    public function cache(){
        return array(
        //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
    }
    public static function newInstance($class=__CLASS__) {
        return parent::newInstance($class);
    }
    public static function getInstance($class=__CLASS__) {
        return parent::getInstance($class);
    }
    public static function get($arr,$single=false,$class=__CLASS__) {
        return parent::get($arr,$single,$class);
    }
    public static function getByPk($pk,$class=__CLASS__) {
        return parent::getByPk($pk,$class);
    }
    
    
    
}

?>
