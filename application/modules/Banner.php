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
class Banner extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'banner';

    public $types = array(
        0=>'Верхний баннер (240х100)',
        1=>'Средний баннер (240х400)',
        2=>'Нижний баннер (728х89)',
    );
    
    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'url'=>array('string[255]','default'=>''),
        'file'=>array('file','default'=>'NULL','allowed'=>array('jpg','gif','png','jpeg','swf'),'max_size'=>10240),
        'code'=>array('html','default'=>'NULL'),
        'type'=>array('integer[2]','default'=>'0','orderable'),
        'title'=>array('string[255]','default'=>'noname'),
        'starts_at'=>array('datetime','default'=>'0','orderable'),
        'ends_at'=>array('datetime','default'=>'0','orderable'),
        'status'=>array('boolean','default'=>'1'),
        //UNUSED
        'clicks'=>array('integer[5]','default'=>'0','orderable')
    );

    public function fieldNames() {
        return array(
            'url'=>'Ссылка',
            'title'=>'Название',
            'file'=>'Файл (jpg,png,gif,swf)',
            'type'=>'Тип баннера',
            'code'=>'или Код ролика',
            'starts_at'=>'Начало показа',
            'ends_at'=>'Конец показа',
            'status'=>'Видимость',
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
    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }
    
    public function printFile() {
        if(!empty($this->file) && is_file('uploads/Banner/'.$this->file)){
            $ext = pathinfo($this->file,PATHINFO_EXTENSION);
            if($ext == 'swf'){
                $size = getimagesize('uploads/Banner/'.$this->file);
                return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$size[0].'" height="'.$size[1].'" id="movie_name" align="middle">
                        <param name="movie" value="/uploads/Banner/'.$this->file.'"/>
                        <param name="wmode" value="transparent"/>
                        <!--[if !IE]>-->
                        <object type="application/x-shockwave-flash" data="/uploads/Banner/'.$this->file.'" width="'.$size[0].'" height="'.$size[1].'">
                            <param name="movie" value="/uploads/Banner/'.$this->file.'"/>
                            <param name="wmode" value="transparent"/>
                        <!--<![endif]-->
                            <a href="http://www.adobe.com/go/getflash">
                                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/>
                            </a>
                        <!--[if !IE]>-->
                        </object>
                        <!--<![endif]-->
                    </object>';
            }else{
                $size = getimagesize('uploads/Banner/'.$this->file);
                return "<img src=\"/uploads/Banner/$this->file\" width=\"$size[0]\" height=\"$size[1]\" alt=\"$this->title\" />";
            }
        }elseif(!empty($this->code)){
            return $this->code;
        }
    }
    
    public function _getClicks() {
        return Banner_Stat::num_rows(array('banner_id'=>$this->id));
    }
    
//////////////////////////////////
//ACTIONS    
//////////////////////////////////    
    public function actionGo() {
        $url = base64_decode($_GET['url']);
        $url = explode("||", $url);
        $id = (int)$url[0];
        $url = trim($url[1]);
        if(empty($url) || (NULL===($banner=Banner::getByPk($id))))
            throw new X3_404;
        Banner_Stat::log($id);
        $url = urldecode($url);
        header("Location: $url");
        exit;
    }
    
    
    public function getDefaultScope() {
        //$scope = array('@join'=>'LEFT JOIN banner_stat ON banner_stat.banner_id=banner.id');
        if(isset($_GET['order']) && strpos($_GET['order'],'clicks')===0){
            $scope['@order'] = str_replace('@',' ',$_GET['order']);
            unset($_GET['order']);
        }
        return $scope;
    }
    
    public function beforeValidate() {
        if(strpos($this->starts_at,'.')!==false)
                $this->starts_at = strtotime ($this->starts_at);
        if(strpos($this->ends_at,'.')!==false)
                $this->ends_at = strtotime ($this->ends_at) + 86399;
    }

}
?>
