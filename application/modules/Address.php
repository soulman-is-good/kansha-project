<?php
/**
 * Description of Items
 *
 * @author Soul_man
 */
class Address extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'data_address';
    public static $_city = array();

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'company_id'=>array('integer[10]','unsigned','index'),
        'type'=>array('integer[1]','default'=>'0'),
        'city'=>array('integer[11]','unsigned','index','default'=>'1','ref'=>array('Region'=>'id','default'=>'title')),
        'phones'=>array('string[255]','default'=>'NULL'),
        'fax'=>array('string[255]','default'=>'NULL'),
        'skype'=>array('string[255]','default'=>'NULL'),
        'icq'=>array('string[255]','default'=>'NULL'),
        'delivery'=>array('string[255]','default'=>'NULL'),
        'address'=>array('string[255]','default'=>'NULL'),
        'worktime'=>array('string[255]','default'=>'{"days":{"Monday":"0","Tuesday":"0","Wednesday":"0","Thursday":"0","Friday":"0","Saturday":"0","Sunday":"1"},"times":["8:30-18:30","-"]}'),
        'email'=>array('email','default'=>'NULL'),        
        'ismain'=>array('boolean','default'=>'0'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[5]','unsigned','default'=>'0'),
    );
    
    
    public function _getPhones() {
        return explode(';;', $this->table->getAttribute('phones'));
    }
    
    public function _setPhones($value) {
        $this->table->setAttribute('phones',implode(';;',$value));
    }
    
    public function _getWorktime() {
        $time = json_decode($this->table->getAttribute('worktime'));
        if($time==null)
            $time = array('days'=>array('Monday'=>'0',"Tuesday"=>"0","Wednesday"=>"0","Thursday"=>"0","Friday"=>"0","Saturday"=>"1","Sunday"=>"2")
                ,'times'=>array("8:30-18:30","8:30-17:30","-"));
        return $time;
    }
    
    public function _setWorktime($value) {
        if(!is_array($value['days'])) $value['days'] = array();
        $diff = array_diff_key($value['days'], array('Monday'=>'0',"Tuesday"=>"0","Wednesday"=>"0","Thursday"=>"0","Friday"=>"0","Saturday"=>"1","Sunday"=>"2"));
        if(!is_array($value) || !isset($value['days']) || !isset($value['times']) || !empty($diff))
                $value = array('days'=>array('Monday'=>'0',"Tuesday"=>"0","Wednesday"=>"0","Thursday"=>"0","Friday"=>"0","Saturday"=>"1","Sunday"=>"2")
                ,'times'=>array("8:30-18:30","8:30-17:30","-"));
        $this->table->setAttribute('worktime',  json_encode($value));
    }
    
    public function getRegion() {
        if(!isset(self::$_city[$this->city]))
            self::$_city[$this->city] = Region::getByPk ($this->city);
        return self::$_city[$this->city];
    }
    
    public $referencies = array(
        'ShopTmp.group_id'=>'ShopGroupTmp.id',
        'map'=>array(
            'ShopGroupTmp.title'=>'title'
        )
    );
    
    public function fieldNames() {
        return array(
            'company_id'=>'Группа',
            'city_id'=>'Группа',
            'phones'=>'Телефоны',
            'fax'=>'Факс',
            'skype'=>'Skype',
            'address'=>'Адрес',
            'worktime'=>'Время работы',
            'email'=>'E-mail',
            'ismain'=>'Головной',
            'status'=>'Видимость',
            'weight'=>'Порядок',
        );
    }

    public function cache(){
        return array(
        //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
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
    
    public static function verb($verb) {
        if(!isset($verb)) return '';
        $last = substr($verb, -1,1);
        if(trim($last,'айоыья')==''){
            return substr($verb, 0,-2) . "е";
        }
        if(trim($last,'бвгджзклмнпрстфхцчшщ')==''){
            return $verb . "е";
        }
        return $verb;
    }
    
}

?>
