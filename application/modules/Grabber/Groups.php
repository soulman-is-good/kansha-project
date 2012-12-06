<?php

class Grabber_Groups extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'grabber_groups';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'group_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Group'=>'id','default'=>'title')),
        'grabber_id'=>array('integer[10]','unsigned','index','ref'=>array('Grabber'=>'id','default'=>'title')),
        'value'=>array('string[255]'),        
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
}