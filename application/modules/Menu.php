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
class Menu extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'data_menu';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
//        'parent_id'=>array('integer[10]','unsigned','index','default'=>'NULL','ref'=>array('Menu','id')),
        'role'=>array('string[255]','default'=>'*'),
        'title'=>array('string[255]','language'),
        'link'=>array('string[255]','default'=>'/'),
        'weight'=>array('integer[10]','default'=>'0'),
        'status'=>array('boolean','default'=>'1'),
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
            'title'=>'Заголовок',
            'link'=>'Ссылка',
            'role'=>'Видят только',
            'weight'=>'Порядок',
            'status'=>'Видимость',
        );
    } 
    public function moduleTitle() {
        return 'Меню';
    }
    public function actionInsert(){
        $model = $this->table->select('*')->where('id=1')->asObject(true);
        $this->template->render('insert',array('model'=>$model));
    }

    public function getDefaultScope() {
        return array(
            '@order'=>array('weight')
        );
    }
    
    public function afterSave() {
        if(is_file('sitemap.xml'))
            @unlink('sitemap.xml');
    }
}
?>
