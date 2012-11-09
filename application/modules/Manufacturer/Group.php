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
class Manufacturer_Group extends X3_Module_Table {
    public $encoding = 'UTF-8';
    public $tableName = 'manufacturer_group';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'manufacturer_id'=>array('integer[10]','unsigned','index'),
        'gid'=>array('integer[10]'),
        'pid'=>array('integer[10]','default'=>'0'),
        'text'=>array('text')
    );
    
    public function actionUpdate() {
        if(isset($_POST[__CLASS__])){
            $mg = $_POST[__CLASS__];
            $manid = 0;
            foreach($mg as $mid=>$grps){
                $manid=$mid;
                foreach($grps as $gid=>$attr){
                    $gid = explode('|',$gid);
                    $gid = $gid[0];
                    if(trim($attr['text'])=='') continue;
                    $pid = (int)$attr['pid'];
                    if(NULL===($t = self::get(array('manufacturer_id'=>''.$mid,'gid'=>''.$gid,'pid'=>''.$pid),1))){
                        $t = new self;
                    }
                    $t->gid = $gid;
                    $t->pid = $pid;
                    $t->manufacturer_id = $mid;
                    $t->text = $attr['text'];
                    if(!$t->save()){
                        var_dump($t->getTable()->getErrors());die;
                    }
                }
            }
        }
        if(!IS_AJAX)
            $this->redirect("/admin/manufacturer/edit/".$manid."#text");
        exit;
    }
        
    public function beforeSave() {
        return parent::beforeSave();
    }
}

?>
