<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Articule
 *
 * @author Soul_man
 */
class Shop_Articule extends X3_Module_Table {
    public $encoding = 'UTF-8';
    public $tableName = 'shop_articule';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'articule'=>array('string[255]','unique'),
        'model'=>array('string[255]','index'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );
    public function beforeSave() {
        $this->created_at = time();
        return parent::beforeSave();
    }
}

?>
