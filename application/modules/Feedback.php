<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of News
 *
 * @author Soul_man
 */
class Feedback extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_feedback';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'ip'=>array('string[16]','default'=>''),
        'class'=>array('string[255]'),
        'class_id'=>array('integer[10]','unsigned','index'),
        'mark'=>array('integer[1]','default'=>'0'),
        'text'=>array('text'),
        'status'=>array('boolean','default'=>'0'),
        'created_at'=>array('datetime','unsigned','default'=>'0')
    );
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
    public function fieldNames() {
        return array(
            'ip'=>'IP адрес',
            'mark'=>'Оценка',
            'text'=>'Содержание',
            'status'=>'Видимость',
            'created_at'=>'Создан',
        );
    }
    
    public function moduleTitle() {
        return 'Отзывы';
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
        if($this->table->getIsNewRecord()){
            $this->ip = $_SERVER['REMOTE_ADDR'];
        }
        
    }

}
?>
