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
class Company_Job extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'company_job';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company'=>'id','default'=>'title')),
        'value'=>array('string[255]','default'=>''),
        'created_at'=>array('datetime'),
        'updated_at'=>array('datetime'),
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'company_id'=>'Компания',
        );
    }
    
    public function beforeValidate() {
        if($this->created_at==0)
            $this->created_at = time();
        if($this->updated_at==0)
            $this->updated_at = time();
    }
    
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
//            $model = $this->table->select('*')->where($condition)->asObject(true);
        }
        parent::onDelete($tables,$condition);
    }
    
}
?>