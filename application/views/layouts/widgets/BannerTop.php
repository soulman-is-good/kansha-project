<?php
$date1 = time();
$banner = Banner::get(array('@condition'=>array('status','type'=>'0','starts_at'=>array('<'=>$date1),'ends_at'=>array('>'=>$date1)),'@order'=>'RAND()','@limit'=>1),1);
if($banner!=NULL):
?>
<div class="header_banner" style="position:relative">
    <?if(!empty($banner->url)):?>
    <a target="_blank" href="/banner/go.html?url=<?=  base64_encode($banner->id."||".urlencode($banner->url))?>" style="position:absolute;left:0;top:0;width:240px;height:100px;z-index:99">
        <img src="/images/_zero.gif" width="240" height="100" />
    </a>
    <?endif;?>
    <?=$banner->printFile()?>
</div>
<?endif;?>