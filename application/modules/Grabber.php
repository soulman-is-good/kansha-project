<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grabber
 *
 * @author Soul_man
 */
class Grabber extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'grabber';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'title'=>array('string[255]','default'=>'Unnamed'),
        'config'=>array('string[255]','default'=>'{}'),
        'isdefault'=>array('boolean','default'=>'0'),
        'updated_at'=>array('integer[10]','unsigned','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );
    
    public function _getConfig() {
        $data = '{}';
        if(is_file('uploads/Grabber/'.$this->table['config'])){
            $data = file_get_contents('uploads/Grabber/Grabber-'.$this->id.'.json');
        }
        return $this->table['config']=json_decode($data);
    }
    
    public function _setConfig($value) {
        $this->table['config'] = $value;
        @file_get_contents('uploads/Grabber/Grabber-'.$this->id.'.json',json_encode($value));
    }

    public function fieldNames() {
        return array(
            'title'=>'Название',
            'isdefault'=>'Текущий',
        );
    }
    
    public static function grep($params) {
        return X3_Thread::create('http://localhost:3435',$params,"GET")->run()->getBody();
    }
    
    public function beforeSave() {
        if($this->created_at == 0) $this->created_at = time();
        $this->updated_at = time();
        if($this->isdefault) {
            X3::db()->query("UPDATE `grabber` SET `isdefault`=0 WHERE `isdefault`=1");
        }
        parent::beforeSave();
    }
    
    public function afterSave() {
        if($this->isdefault) 
            X3::user()->grabber = $this->id;
    }
    
    public static function getGroupId($id) {
        $g = Grabber_Groups::get(array('value'=>$id,'grabber_id'=>array(' = '=>"(SELECT id FROM `grabber` WHERE isdefault=1)")),true);
        if($g!=null)
            return $g->group_id;
        return null;
    }
        
}

?>
