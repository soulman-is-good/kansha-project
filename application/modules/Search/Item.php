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
class Search_Item extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'search_item';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'item_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Item'=>'id','default'=>'title')),
        'group_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Group'=>'id','default'=>'title')),
        'title'=>array('content','default'=>'NULL'),
    );
    
    public function fieldNames() {
        return array(
            'item_id'=>'Товар',
            'group_id'=>'Группа',
        );
    }
       
/**
 * 
 * DEFAULT OVERLOADED FUNCTIONS
 * 
 */
    
    public function getDefaultScope() {
        return array();
    }
        
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
        }
        parent::onDelete($tables,$condition);
    }    
    
    public function beforeValidate() {
        return parent::beforeValidate();
    }
}

?>
