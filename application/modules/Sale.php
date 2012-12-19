<?php
/**
 * Description of Sale
 *
 * @author Soul_man
 */
class Sale extends X3_Module_Table {


    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_sale';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),        
        'company_id'=>array('integer[10]','unsigned','index','ref'=>array('Company','id','default'=>'title','query'=>array('@order'=>'title'))),
        'group_id'=>array('integer[10]','unsigned'),
        'property_id'=>array('integer[10]','unsigned','default'=>'NULL'),
        'image' => array('file', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'giftimage' => array('file', 'default' => 'NULL', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'title'=>array('string[255]'),
        'gifttitle'=>array('string[255]','default'=>'NULL'),
        'type'=>array("enum['Акция','Скидка','Подарок']",'default'=>'Акция'), //0-action, 1-sale, 2-present
        'oldprice'=>array('decimal[10,2]','default'=>'0.00'),
        'price'=>array('decimal[10,2]','default'=>'0.00'),
        'text'=>array('text','default'=>'NULL'),
        'gifttext'=>array('text','default'=>'NULL'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[10]','default'=>'0'),
        'starts_at'=>array('datetime'),
        'ends_at'=>array('datetime'),
        'metatitle' => array('string', 'default' => '', 'language'),
        'metakeywords' => array('string', 'default' => '', 'language'),
        'metadescription' => array('string', 'default' => '', 'language'),        
    );    
    
    public function moduleTitle() {
        return 'Распродажи';
    }
    
    public function fieldNames() {
        return array(
            'type'=>'Тип',
            'company_id'=>'Компания',
            'group_id'=>'Группа',
            'starts_at'=>'Начало',
            'ends_at'=>'Конец',
            'image'=>'Изображение',
            'title'=>'Заголовок',
            'oldprice'=>'Старая цена',
            'price'=>'Цена',
            'text'=>'Описание/Характеристики',
            'giftimage'=>'Изображение подарка',
            'gifttitle'=>'Название подарка',
            'gifttext'=>'Описание подарка',
            'weight'=>'Порядок',
            'status'=>'Видимость',
            'metatitle'=>'Metatitle',
            'metakeywords'=>'Metakeywords',
            'metadescription'=>'Metadescription',                        
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
                'c.status',
                'data_sale.status',
                'starts_at'=>array('<'=>"'".time()."'"),
                'ends_at'=>array('>'=>"'".time()."'"),array(
                array('title' => array('LIKE' => $this->like('title', $sql, true,$strict,$union))),
                array('gifttitle' => array('LIKE' => $this->like('gifttitle', $sql, true,$strict,$union))),
                array('text' => array('LIKE' => $this->like('text', $sql, true,$strict,$union))),
                array('gifttext' => array('LIKE' => $this->like('gifttext', $sql, true,$strict,$union))),
                    )
            );
            $scope['@join'] = "INNER JOIN data_company c ON c.id = data_sale.company_id";
            $scope['@order'] = 'c.isfree2, starts_at DESC, weight';
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
    
    public function actionIndex() {
        $q = array('@condition'=>array('data_sale.status','c.status','ends_at'=>array('>'=>"'".time()."'")),'@order'=>'c.isfree2, starts_at DESC, weight'
            ,'@join'=>"INNER JOIN data_company c ON c.id=data_sale.company_id");
        //$a = new X3_MySQL_Query('data_sale');echo $a->formQuery($q)->buildSQL();exit;
        $nc = Sale::num_rows($q);
        $pag = new Paginator('Sale', $nc);
        $q['@offset']=$pag->offset;
        $q['@limit']=$pag->limit;
        $models = Sale::get($q);
        SeoHelper::setMeta();
        $this->template->render('index',array('models'=>$models,'paginator'=>$pag,'saleCount'=>$nc,'bread'=>array(array('#'=>'Распродажа'))));
    }    
    
    public function actionShow() {
        if(!isset($_GET['id']) || !($id = (int)$_GET['id'])>0 || NULL==($model = Sale::getByPk($id)))
            throw new X3_404();
        $company = Company::getByPk($model->company_id);
        if($model->metatitle=='') $model->metatitle = 'Распродажа '.$model->title.' у '.$company->title;
        SeoHelper::setMeta($model->metatitle,$model->metakeywords,$model->metadescription);
        $this->template->render('show',array('model'=>$model,'company'=>$company,
            'bread'=>array(array('/sale.html'=>'Распродажи'),array('#'=>$model->title))));
    }
    
    public function getDefaultScope() {
        return array(
            '@order'=>"starts_at DESC, weight"
        );
    }
    
    public function beforeValidate() {
        if($this->property_id == 0){
            $this->property_id = null;
        }
        if(!$this->group_id>0)
            $this->addError('group_id','Не верно задана группа!');
        if(strpos($this->starts_at,'.')!==false){
            $this->starts_at = strtotime($this->starts_at);
        }elseif($this->starts_at === 0)
            $this->starts_at = time();
        if(strpos($this->ends_at,'.')!==false){
            $this->ends_at = strtotime($this->ends_at);
        }elseif($this->ends_at === 0)
            $this->ends_at = time();
    }
    public function date($date = false) {
        if($date === false)
            $date = $this->ends_at;
        return date('d', $date) . " " . I18n::months((int) date('m', $date)-1, I18n::DATE_MONTH) . " " . date('Y', $date);
    }    
        
}

?>
