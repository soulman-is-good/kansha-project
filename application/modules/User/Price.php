<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Items
 *
 * @author Soul_man
 */
class User_Price extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'user_price';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'item_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Item'=>'id','default'=>'title')),
        'email'=>array('email'),
        'price'=>array('decimal[10,2]','default'=>'NULL'),
        'time'=>array('datetime','default'=>'0'),
        'updated_at'=>array('datetime','default'=>'0'),
        'status'=>array('boolean','default'=>'0'),
    );
    
    public function fieldNames() {
        return array(
            'item_id'=>'Товар',
            'email'=>'E-mail',
            'price'=>'Цена',
            'time'=>'Время запроса',
            'status'=>'Удовлетворен',
        );
    }
       
    public function actionSet() {
        if(!isset($_POST['item_id']) || !isset($_POST['price']) || !isset($_POST['email'])){
            echo json_encode(array('status'=>'ERROR','message'=>'Некорректно введены данные!'));
            exit;
        }
        $id = (int)$_POST['item_id'];
        $price = doubleval($_POST['price']);
        if($price <= 0){
            echo json_encode(array('status'=>'ERROR','message'=>'Ну что Вы, такого никогда не случится!'));
            exit;
        }
        $email = trim($_POST['email']);
        if(NULL === ($m = self::get(array('email'=>$email,'item_id'=>$id),1))){
            $m = new self;
            $m->item_id = $id;
            $m->email = $email;
        }
        $m->price = $price;
        $m->time = time();
        if(!$m->save()){
            $err = $m->getErrors();
            $err = implode(', ', $err['email']);
            echo json_encode(array('status'=>'ERROR','message'=>$err));
        }else{
            echo json_encode(array('status'=>'OK','message'=>'Спасибо! Мы обязательно Вас уведомим!'));
        }
        if(!IS_AJAX)
            $this->redirect('/'.$id);
        exit;
    }
/**
 * 
 * DEFAULT OVERLOADED FUNCTIONS
 * 
 */
    
    public function getDefaultScope() {
        $scope = array('@order'=>'time DESC, id DESC');
        return $scope;
    }
        
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
        }
        parent::onDelete($tables,$condition);
    }
    
    public function beforeValidate() {
        if($this->time==0){
            $this->time=time();
        } 
        return parent::beforeValidate();
    }
}

?>
