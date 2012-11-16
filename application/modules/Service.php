<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Section is linked table to data_catalog. Groups catalog entities to a sections
 *
 * @author Soul_man
 */
class Service extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_service';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'title'=>array('string[255]'),
        'name'=>array('string[255]','unique'),
        'text'=>array('text','default'=>'NULL'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[10]','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );
    
    public function moduleTitle() {
        return 'Услуги';
    }

    public static function newInstance($class=__CLASS__) {
        return parent::newInstance($class);
    }
    public static function getInstance($class=__CLASS__) {
        return parent::getInstance($class);
    }
    public static function get($arr=array(),$single=false,$class=__CLASS__) {
        return parent::get($arr,$single,$class);
    }
    public static function getByPk($pk,$class=__CLASS__) {
        return parent::getByPk($pk,$class);
    }
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'text'=>'Описание',
            'weight'=>'Порядок',
            'status'=>'Видимость',
        );
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }
    
    
    private function like($a, $s, $no = false,$strict = false,$union = 'AND') {
        $c = '';
        if(!$strict)
            $c='%';
        if ($no)
            return "'$c" . implode("%' $union $a LIKE '%", explode(' ', $s)) . "$c'";
        else
            return "$a LIKE '$c" . implode("%' $union $a LIKE '%", explode(' ', $s)) . "$c'";
    }        

    public function search($search = null, $return = true, $strict = false, $union = 'AND') {
        if (!empty($search)) {
            $sql = X3::db()->validateSQL(trim($search));
            $sql = preg_replace("/[\s]+/", " ", $sql);
            $rsql = str_replace(" ", "|", $sql);
            $sql = preg_replace("/[\(\)\[\]\{\}]/", '', $sql);
            $scope = array();
            $scope['@condition'] = array(
                array('title' => array('LIKE' => $this->like('title', $sql, true,$strict,$union))),
                array('text' => array('LIKE' => $this->like('text', $sql, true,$strict,$union))),
            );
            if($return === 'query')
                return $scope;
            if($return === 'json')
                return json_encode (self::get ($scope, 0, __CLASS__,1));
            if($return === 'array')
                return self::get ($scope, 0, __CLASS__,1);
            if($return == true)
                return self::get($scope);
        }
        if($return)
            return false;
        else
            exit;
    }    
    
    public function actionInsert() {
        if(!X3::user()->isAdmin()) exit;
        $title = $_POST['title'];
        $m = new self;
        $m->title = $title;
        if($m->save())
            echo $m->id;
        else
            echo json_encode($m->table->getErrors());
        exit;
    }
    
    public function actionIndex() {
        $bread = Breadcrumbs::service();
        SeoHelper::setMeta();
        $this->template->render('index',array('bread'=>$bread));
    }
    
    public function actionShow(){
        $query = '';
        if(isset($_GET['id'])){
            $id = (int)($_GET['id']);
            if(!$id>0) throw new X3_404();
            $query = "AND id IN (SELECT company_id FROM company_service WHERE groups REGEXP '\"$id\"')";
            $description = '';
        }elseif(isset($_GET['name'])){
            $name = mysql_real_escape_string($_GET['name']);
            $service = self::get(array('@condition'=>array('name'=>$name,'status')),1);
            if($service===NULL) throw new X3_404();
            $description = '';
            if($service!==null){
                $query = "AND id IN (SELECT company_id FROM company_service WHERE services REGEXP '\"$service->id\"')";
                $description = $service->text;
            }
        }
        $bread = Breadcrumbs::service();
        $this->template->render('index',array('query'=>$query,'description'=>$description, 'bread' => $bread));
    }

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
        $this->name = strtolower(X3_String::create($this->title)->translit());
    }

}
?>
