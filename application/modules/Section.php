<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Section is linked table to data_catalog. Groups catalog entities to a sections
 *
 * @author Soul_man
 */
class Section extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_section';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'title'=>array('string[255]'),
        'status'=>array('boolean','default'=>'1'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );

    public $backlink = array(
        'Catalog'=>array('section_id','DELETE')
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
            'title'=>'Название',
            'status'=>'Видимость',
        );
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }
    
    public function actionShow(){
        $id = (int)($_GET['id']);
        if(!$id>0) throw new X3_404();
        $model = self::getByPk($id);//$this->table->select('*')->where("`name`='$name'")->asObject(true);
        if($model==null) throw new X3_404();
        $this->template->render('show',array('model'=>$model));
    }

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
    }

}
?>
