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
class Region extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_region';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'parent_id'=>array('integer[10]','unsigned','index','default'=>'NULL','ref'=>array('Region','id','default'=>'title')),
        'title'=>array('string[255]'),
        'name'=>array('string[255]','unique'),
        'status'=>array('boolean','default'=>'1'),
    );

    public static function newInstance($class=__CLASS__) {
        return parent::newInstance($class);
    }
    public static function getInstance($class=__CLASS__) {
        return parent::getInstance($class);
    }
    public static function get($arr,$single=false,$class=__CLASS__,$asArray=false) {
        return parent::get($arr,$single,$class,$asArray);
    }
    public static function getByPk($pk,$class=__CLASS__) {
        return parent::getByPk($pk,$class);
    }
    public function fieldNames() {
        return array(
            'parent_id'=>'Находится в:',
            'title'=>'Название',
            'status'=>'Видимость',
        );
    }
    
    public function moduleTitle() {
        return 'Регионы';
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }

    public function beforeValidate() {
        if($this->name == '')
            $this->name = $this->title;
        $name = new X3_String($this->name);
        $this->name = strtolower($name->translit());
        if(empty($this->parent_id) || $this->parent_id=="0") $this->parent_id = NULL;
    }

}
?>
