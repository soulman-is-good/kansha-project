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
class Company_Settings extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'company_settings';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company'=>'id','default'=>'title')),
        'value'=>array('content','default'=>'{}'),
        'update_props'=>array('content','default'=>'{"xls":{"id":"0","price":"1","url":"2","name":"3","group":"4","photo":"-1","desc":"-1"},"csv":{"id":"0","price":"1","url":"2","name":"3","group":"4","photo":"-1","desc":"-1","delimiter":";"},"xml":{"id":"id","price":"price","url":"url","name":"name","group":"","photo":"","desc":"","container":"item"}}'),
        'autoload_auto'=>array('boolean','default'=>'0'),
        //'autoload_autotype'=>array('boolean','default'=>'0'),
        'last_autoupdate_file'=>array('string[255]','default'=>''),
        'autoload_url'=>array('string[255]','default'=>''),
        'autoload_md5'=>array('boolean','default'=>'0'),
        'autoload_type'=>array('string[5]','default'=>''),
        'autoload_shopurl'=>array('string[255]','default'=>''),
        'autoload_koef'=>array('float','default'=>'1.00'),
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'category_id'=>'Категория',
            'text'=>'Описание',
            'status'=>'Видимость',
            'weight'=>'Порядок',            
        );
    }

    public function _getValue() {
        return json_decode($this->table->getAttribute('value'),1);
    }
    
    public function _setValue($value) {       
        $this->table->setAttribute('value', json_encode($value));
    }
    
    
    public function _getUpdate_props() {
        $props = json_decode($this->table['update_props'],1);
        return $props;
    }
    
    public function _setUpdate_props($value) {
        $this->table->setAttribute('update_props',json_encode($value));
    }    
    
    public function cache(){
        return array(
        //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
    }

    
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
//            $model = $this->table->select('*')->where($condition)->asObject(true);
        }
        parent::onDelete($tables,$condition);
    }
    
}
?>