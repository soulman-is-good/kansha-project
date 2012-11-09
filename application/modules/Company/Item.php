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
class Company_Item extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'company_item';
    public static $_coms = array();

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company'=>'id','default'=>'title')),
        'item_id'=>array('integer[10]','unsigned','index','ref'=>array('Shop_Item'=>'id','default'=>'title')),
        'client_id'=>array('string','default'=>'NULL'),
        'price'=>array('decimal[10,2]','default'=>'0'),
        'url'=>array('string','default'=>'NULL'),
        'url_checked'=>array('boolean','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0'),
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'company_id'=>'Компания',
            'item_id'=>'Товар',
            'price'=>'Цена',
            'url'=>'Ссылка',
        );
    }
    
    public function cache(){
        return array(
            'cache'=>array('actions'=>'stat','role'=>'*','expire'=>'+1 day'),
        );
    }
    
    public function getCompany() {
        if(isset(self::$_coms[$this->company_id]))
            return self::$_coms[$this->company_id];
        return self::$_coms[$this->company_id] = Company::getByPk($this->company_id);
    }
    
    public function actionStat() {
        $a = X3::db()->fetch("SELECT MIN(price) `min`, MAX(price) `max` FROM $this->tableName");
        echo json_encode($a);
        exit;
    }
    
    public function actionDelete() {
        if(!X3::user()->isAdmin()) throw new X3_404;
        $id = (int)$_GET['id'];
        $q = X3::db()->fetch("SELECT company_id FROM `$this->tableName` WHERE id=$id");
        X3::db()->query("DELETE FROM `$this->tableName` WHERE id=$id");
        if(IS_AJAX) exit;
        $this->redirect('/admin/company/content/'.$q['company_id']);
    }
    
    public function actionDeleteall() {
        if(!X3::user()->isAdmin()) throw new X3_404;
        $id = (int)$_GET['id'];
        X3::db()->query("DELETE FROM `$this->tableName` WHERE company_id=$id");
        $this->redirect('/admin/company/content/'.$id);
    }
    
    public function actionAdd() {
        if(!X3::user()->isAdmin()) throw new X3_404;
        $cid = (int)$_POST['cid'];
        $iid = (int)$_POST['iid'];
        $com = Company::getByPk($cid);
        if($com===null || !$iid>0){
            echo json_encode(array('error'=>'Не верные данные'));
            exit;
        }
        if(self::num_rows(array('item_id'=>$iid,'company_id'=>$cid))>0){
            echo json_encode(array('error'=>'Этот товар уже привязан к этой компании'));
            exit;
        }
        $item = new self;
        $item->item_id = $iid;
        $item->company_id = $cid;
        $item->price = $_POST['price'];
        $item->client_id = $_POST['clientid'];
        $item->url = $_POST['url'];
        if(X3_String::create($_POST['url'])->check_protocol() && X3_Thread::create($_POST['url'])->run(X3_Thread::CHECK_CONNECTION)){
            $item->url_checked=1;
        }
        if(!$item->save()){
            echo json_encode(array('error'=>'Ошибка сохранения'));
        }else{
            echo json_encode(array(
                'id'=>$item->id,
                'idc'=>$com->id,
                'title'=>$com->title,
                'price'=>$item->price,
                'url'=>$item->url,
                'cid'=>$item->client_id,
            ));
        }
        exit;
    }
    
    public function beforeValidate() {
        if($this->getTable()->getIsNewRecord() && Company_Item::get(array('company_id'=>$this->company_id,'item_id'=>$this->item_id),1)!==NULL){
            $this->addError('item_id', "Такой товар (#$this->item_id) уже привязан как '$this->client_id'");
            return false;
        }
        return true;
    }
    
    public function beforeSave() {
        $this->created_at = time();
        return parent::beforeSave();
    }
        
}
?>
