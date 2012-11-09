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
class Company_Service extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'company_service';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company'=>'id','default'=>'title')),
        'services'=>array('text','default'=>'[]'),
        'groups'=>array('text','default'=>'[]'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0'),
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'company_id'=>'Компания',
            'services'=>'Услуги',
            'groups'=>'по группам',
        );
    }
        
    public function beforeSave() {
        $this->created_at = time();
        if(is_array($this->services)){
            $this->services = json_encode($this->services);
        }
        if(is_array($this->groups)){
            $this->groups = json_encode($this->groups);
        }
        return parent::beforeSave();
    }
        
}
?>
