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
class Meta extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_meta';
    
    public static $allow = array(
        'Shop_Group',
        'Shop_Category',
        'Company',
        'Site',
        'Page',
    );

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'table'=>array('string[255]'),        
        'key'=>array('string[64]','default'=>'id'),
        'value'=>array('integer[11]','unsigned'),
        'metatitle' => array('string', 'default' => '', 'language'),
        'metakeywords' => array('string', 'default' => '', 'language'),
        'metadescription' => array('string', 'default' => '', 'language'),
    );
    
    public function moduleTitle() {
        return 'СЕО';
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
    public function fieldNames() {
        return array(
            'table'=>'Модуль',
            'key'=>'Ключ',
            'value'=>'Привязка по ключу',
            'metatitle'=>'Мета-заголовок',
            'metakeywords'=>'Мета-ключевые слова',
            'metadescription'=>'Мета-описание',
        );
    }
    
    public function actionSave() {
        if(isset($_POST['Meta'])){
            $module = $_POST['module'];
            if(!class_exists($module))
                die(json_encode (array('status'=>'ERROR','message'=>'Модуль не существует')));
            $POST = $_POST['Meta'];
            $class = X3_Module_Table::getByPk($_GET['id'],$module);
            $class->metatitle = $POST['metatitle'];
            $class->metakeywords = $POST['metakeywords'];
            $class->metadescription = $POST['metadescription'];
            if(!$class->save()){
                $errs = $class->getTable()->getErrors();
                foreach($errs as &$er){
                    $er = $er[0];
                }
                die(json_encode (array('status'=>'ERROR','message'=>$errs)));
            }else
                die(json_encode (array('status'=>'OK','message'=>'Успех')));
                
        }
        exit;
    }

    public function beforeValidate() {
    }

}
?>
