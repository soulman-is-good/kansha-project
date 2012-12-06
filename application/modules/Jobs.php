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
class Jobs extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_jobs';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'title'=>array('string[255]'),        
        'text'=>array('text','language'),
        'status'=>array('boolean','default'=>'1'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
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
            'name'=>'Имя в URL',
            'text'=>'Содержание',
            'status'=>'Видимость',
        );
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }

    public function actionIndex(){
        $models = self::get(array('status'));
        $this->template->render('index',array('models'=>$models));
    }

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
    }

}
?>
