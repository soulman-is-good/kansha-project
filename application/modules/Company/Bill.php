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
class Company_Bill extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'company_bill';
    
    public static $paytype = array(
        '0'=>'Услуги',
        '1'=>'Прайсы',
        '2'=>'Распродажи',
    );

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company'=>'id','default'=>'title')),
        'document'=>array('string[255]','default'=>'0'),
        'type'=>array('integer[1]','default'=>'0'),
        'paysum'=>array('decimal[12,2]','default'=>'0'),
        'ends_at'=>array('datetime','default'=>'0'),
        'created_at'=>array('datetime','default'=>'0'),
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'company_id'=>'Компания',
            'document'=>'Документ',
            'paysum'=>'Внесенная сумма',
            'ends_at'=>'Дата окончания платежа',
            'created_at'=>'Дата оплаты',
        );
    }
    
    public function actionUpdate() {
        if(X3::user()->isGuest() || !isset($_POST['Bill'])) throw new X3_404();
        $bill = $_POST['Bill'];
        $model = new self;
        $model->getTable()->acquire($bill);
        //$sum = X3::db()->fetch("SELECT SUM(paysum) AS `summ` FROM `company_bill` WHERE `type`=$model->type AND company_id=$model->company_id");
        //$sum = $sum['summ'];
        //$model->paysum+=$sum;
        $isfree = ($model->type>0)?"isfree$model->type":"isfree";
        if(0 && self::num_rows(array('company_id'=>$bill['company_id']))>0){
            $model->getTable()->setIsNewRecord(false);
        }
        if($model->save()){
            Company::update(array("$isfree"=>'0'),array('id'=>$model->company_id));
            echo 'OK';
        }else
            echo 'ERROR';
        exit;
    }
        
    public function beforeValidate() {
        if(strpos($this->created_at,'.') !== false)
            $this->created_at = strtotime($this->created_at);
        if(strpos($this->ends_at,'.') !== false)
            $this->ends_at = strtotime($this->ends_at);
        
        if($this->created_at == 0)
            $this->created_at = time();
        return parent::beforeSave();
    }
        
}
?>
