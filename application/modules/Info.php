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
class Info extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_info';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'ip'=>array('string[16]'),
        'referer'=>array('string[255]','null'),
        'request'=>array('string[255]'),
        'agent'=>array('string[255]','default'=>'none'),
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
    //TODO: Статистика
    //  по браузерам - общая(только Mozilla или IE), подробная(Mozilla v3 Mozilla v5), по движкам(Gecko, IE)
    //  по IP адресам,
    //  по времени,
    //  по ссылке
    public function actionIndex(){
        if(X3::app()->user->isGuest()) return Main::error404();
        //Общий список.
        $this->render('index');
    }

}
?>
