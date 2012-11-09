<?php
$date1 = time();
$banner = Banner::get(array('@condition'=>array('status','type'=>'2','starts_at'=>array('<'=>$date1),'ends_at'=>array('>'=>$date1)),'@order'=>'RAND()','@limit'=>1),1);
if($banner != null):
$attrs = (isset($attrs)?$attrs:array('style'=>'margin-top:38px'));
$attrs['class'] = 'lower_banner';
echo X3_Html::open_tag('div', $attrs);
?>
    <?if(!empty($banner->url)):?>
    <a target="_blank" href="/banner/go.html?url=<?=  base64_encode($banner->id."||".urlencode($banner->url))?>" style="position:absolute;left:0;top:0;width:728px;height:80px;z-index:99">
        <img src="/images/_zero.gif" width="728" height="89" />
    </a>
    <?endif;?>
    <?=$banner->printFile()?>
<?=X3_Html::close_tag('div');endif;?>