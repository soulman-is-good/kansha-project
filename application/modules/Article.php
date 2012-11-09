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
class Article extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'data_article';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'image'=>array('file','default'=>'NULL','allowed'=>array('jpg','gif','png','jpeg'),'max_size'=>10240),
        'title'=>array('string[255]','language'),
        'content'=>array('content','language'),
        'text'=>array('text','language'),
        'status'=>array('boolean','default'=>'1'),
        'created_at'=>array('datetime','default'=>'0'),
        'metatitle'=>array('string','default'=>'','language'),
        'metakeywords'=>array('string','default'=>'','language'),
        'metadescription'=>array('string', 'default'=>'','language'),                  
    );
    public function fieldNames() {
        return array(
            'created_at'=>'Дата',
            'image'=>'Картинка',
            'title'=>'Заголовок',
            'content'=>'В кратце',
            'text'=>'Содержание',
            'status'=>'Видимость',
            'metatitle'=>'Metatitle',
            'metakeywords'=>'Metakeywords',
            'metadescription'=>'Metadescription',            
        );
    }
    public function moduleTitle() {
        return 'Обзоры';
    }
    
    public function cache(){
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 month'),
        );
    }
    public static function newInstance($class=__CLASS__) {
        return parent::newInstance($class);
    }
    public static function getInstance($class=__CLASS__) {
        return parent::getInstance($class);
    }

    public static function get($arr=array(), $single = false, $class = __CLASS__,$asArray=false) {
        return parent::get($arr, $single, $class,$asArray);
    }

    public static function getByPk($pk, $class = __CLASS__,$asArray=false) {
        return parent::getByPk($pk, $class,$asArray);
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
                array('content' => array('LIKE' => $this->like('content', $sql, true,$strict,$union))),
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
    
    public function actionIndex(){
        $models = $this->table->select('*')->where('status')->asObject();
        SeoHelper::setMeta();
        $this->template->render('index',array('novosti'=>$models));
    }
    
    public function actionShow(){
        if(!isset($_GET['id']))
            throw new X3_404;
        $id = (int)$_GET['id'];
        $model = self::getByPk($id);
        if($model === null)
            throw new X3_404;
        X3::cache()->filename .= '.'.$model->id;
        SeoHelper::setMeta($model->metatitle, $model->metakeywords, $model->metadescription);
        $this->template->render('show',array('model'=>$model,'bread'=>array(array('/articles.html'=>'Архив обзоров'))));
    }
    
    public function date() {
        return date('d',$this->created_at)." ".I18n::months((int)date('m',$this->created_at)-1,I18n::DATE_MONTH)." ".date('Y',$this->created_at);
    }
    
    public function beforeValidate() {
        if(strpos($this->created_at,'.')!==false){
            $this->created_at = strtotime($this->created_at);
            //$this->created_at += time() - $this->created_at; 
        }elseif($this->created_at == 0)
            $this->created_at = time();
    }
    
    public function afterSave() {
        if(is_file('application/cache/article.show.'.$this->id))
            @unlink ('application/cache/article.show.'.$this->id);
    }

}
?>
