<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of I18n
 *
 * @author Soul_man
 */
class I18n extends X3_Component {
    
    const FULL_MONTH = 0;
    const DATE_MONTH = 1;
    
    static private $_translated = array();
    
    public function __construct() {
        $this->addTrigger('onTranslate');
        $this->addTrigger('onStartApp');
        $this->addTrigger('afterGet');
    }
    
    public function onTranslate($message) {
        $lang = X3::app()->user->lang;
        if($lang == null)
            $lang = X3::app()->locale;
        if(isset(self::$_translated[$message]) && isset(self::$_translated[$message][$lang]))
            return self::$_translated[$message][$lang];
        $message = Lang::getValue($message,$lang);
        self::$_translated[$message][$lang] = $message;
    }
    
    public function onStartApp($c,$a) {
        if (X3::app()->user->lang == null){
            $lang = UserIdentity::parseDefaultLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            if(!in_array($lang, X3::app()->languages) || $lang != X3::app()->locale)
               $lang = X3::app()->locale;
            X3::app()->user->lang = $lang;
        }        
        
        if(isset($_GET['lang']) && (in_array($_GET['lang'], X3::app()->languages) || $_GET['lang'] == X3::app()->locale)){
            X3::app()->user->lang = strtolower($_GET['lang']);
        }
    }
    
    public function afterGet($module) {
        if(is_object(X3::app()->module) && X3::app()->module->controller->id=='admin') return 1;
        if(X3::app()->user->lang == 'ru') return 1;
        foreach($module->_fields as $name=>$field){
            foreach(X3::app()->languages as $lang)
            if(($i = strpos($name,"_$lang"))!==false){
                $attr = substr($name, 0, $i);               
                $module->$attr = $module->$name;
                break;
            }
        }
    }
    
    public static function months($number=null,$type=self::FULL_MONTH) {
        static $fullMonths = array(
            'ru'=>array(
                'Январь',
                'Февраль',
                'Март',
                'Апрель',
                'Май',
                'Июнь',
                'Июль',
                'Август',
                'Сентябрь',
                'Октябрь',
                'Ноябрь',
                'Декабрь',
            )
        );
        static $dateMonths = array(
            'ru'=>array(
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря',
            )
        );
        if(!isset(X3::user()->lang)) X3::user()->lang = X3::app()->locale;
        $number = $number<0?$number*-1:$number;
        switch ($type){
            case self::FULL_MONTH:
                return (is_null($number)||$number>11?$fullMonths[X3::user()->lang]:$fullMonths[X3::user()->lang][$number]);
            break;
            case self::DATE_MONTH:
                return (is_null($number)||$number>11?$dateMonths[X3::user()->lang]:$dateMonths[X3::user()->lang][$number]);
            break;
            default:
                return (is_null($number)||$number>11?$fullMonths[X3::user()->lang]:$fullMonths[X3::user()->lang][$number]);
        }
    }
    
    public static function single($val) {
        $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
        $ml = explode("\n",$ml);
        foreach($ml as $Mm){
            $Mm = trim($Mm,"\r\n\ ");
            $Mm = array_map(function($item){return $item;},explode('=',$Mm));
            $val = str_replace($Mm[0],$Mm[1],$val);
        }
        if(preg_match("/[иы]$/", $val)>0){
            $val = mb_substr($val, 0,-1,X3::app()->encoding);
        }
        return $val;
    }
}

?>
