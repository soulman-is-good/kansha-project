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
class Catalog extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_catalog';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'section_id'=>array('integer[10]','unsigned','index'),
        'title'=>array('string[255]'),
        'image'=>array('file','default'=>'NULL','allowed'=>array('jpg','gif','png','jpeg'),'max_size'=>10240),
        'text'=>array('text'),
        'status'=>array('boolean','default'=>'1'),
        'isnew'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[5]','default'=>'0'),
        'bmain'=>array('integer[2]','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );

    public function fieldNames() {
        return array(
            'title'=>'Название',
            'image'=>'Картинка',
            'content'=>'Краткое сожержание',
            'text'=>'Полное содержание',
            'status'=>'Видимость',
            'weight'=>'Порядок',
            'isnew'=>'Новинка',
            'bmain'=>'На главную',
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
        $sections = Section::newInstance()->table->select('*')->where('status')->asArray();
        $section = null;
        if(isset($_GET['id'])){
            $id = (int)$_GET['id'];
            foreach($sections as $s)
                if($s['id'] == $id) {$section = $s;break;}
        }elseif(!empty($sections))
            $section = $sections[0];
        if($section == null) throw new X3_404();
        $models = $this->table->select('data_catalog.*')->join('INNER JOIN data_section ON data_section.id=data_catalog.section_id')->
                where('data_catalog.status AND data_section.status AND data_catalog.section_id='.$section['id'])->asObject();
        $this->template->render('index',array('models'=>$models,'sections'=>$sections,'section'=>$section));
    }

    public function actionShow(){
        if(!isset($_GET['id'])) throw new X3_404();
        $id = (int)($_GET['id']);
        $sid = (int)($_GET['sid']);
        if(!$id>0) throw new X3_404();
        $model = self::getByPk($id);//$this->table->select('*')->where("`name`='$name'")->asObject(true);
        if($sid==0) $sid = $model->section_id;
        $sections = Section::newInstance()->table->select('*')->where('status')->asArray();
        foreach($sections as $s)
            if($s['id'] == $sid) {$section = $s;break;}
        if($model==null) throw new X3_404();
        $this->template->render('show',array('model'=>$model,'sections'=>$sections,'section'=>$section));
    }

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
    }

}
?>
