<?php
/**
 * Description of Sale
 *
 * @author Soul_man
 */
class Sale extends X3_Module {


    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_sale';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'title'=>array('string[255]'),
        'name'=>array('string[255]','unique'),
        'text'=>array('text','default'=>'NULL'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[10]','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );    
    
    public function actionIndex() {
        $this->template->render('index',array('bread'=>array('#'=>'Распродажа')));
    }
}

?>
