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
class Clients extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_clients';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'title'=>array('string[255]'),
        'image'=>array('file','default'=>'NULL','allowed'=>array('jpg','gif','png','jpeg'),'max_size'=>10240),
        'url'=>array('string[255]'),
        'content'=>array('content'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[5]','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );

    public function fieldNames() {
        return array(
            'url'=>'Ссылка',
            'title'=>'Название',
            'image'=>'Изображение',
            'content'=>'Содержание',
            'status'=>'Видимость',
            'weight'=>'Порядок',
        );
    }
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
    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }
    
    public function actionIndex(){
        $models = self::get(array('status'));
        if(empty($models)) throw new X3_404();
        if(isset($_GET['id'])){
            $id = (int)($_GET['id']);
            if(!$id>0) throw new X3_404();
            $model = self::getByPk($id);//$this->table->select('*')->where("`name`='$name'")->asObject(true);
            if($model==null) throw new X3_404();
        }else
            $model = $models[0];
        $this->template->render('index',array('models'=>$models,'client'=>$model));
    }

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
    }

}
?>
