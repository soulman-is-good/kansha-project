<?php
$date1 = time();
$banners = Banner::get(array('@condition'=>array('status','type'=>'1','starts_at'=>array('<'=>$date1),'ends_at'=>array('>'=>$date1)),'@order'=>'RAND()','@limit'=>15));
?>
<?if($banners->count()>0):?>
<div class="right_banner" style="position: relative;">
<? foreach ($banners as $i=>$banner): ?>
    <?if(!empty($banner->url)):?>
    <a target="_blank" id="banner<?=$banner->id?>" href="/banner/go.html?url=<?=  base64_encode($banner->id."||".urlencode($banner->url))?>" style="<?=($i>0)?'display:none;':''?>position:absolute;left:0;top:0;width:240px;height:400px;z-index:99">
        <img src="/images/_zero.gif" width="240" height="400" />
    </a>
    <?endif;?>
    <div <?=($i>0)?'style="display:none"':''?> bid="banner<?=$banner->id?>"><?=$banner->printFile()?></div>
<? endforeach; ?>    
</div>
<div class="banner_links">
    <? foreach ($banners as $i=>$banner): ?>
    <a href="#banner<?=$banner->id?>"<?=$i==0?' class="active"':''?>><img src="/images/<?=$i==0?'m_a.png':'mini.png'?>" width="7" height="7"></a>
    <? endforeach; ?>
</div>
<?endif;?>
<script type="text/javascript">
    $(function(){
        $('.banner_links a').click(function(){
            var href = $(this).attr('href');
            if($(this).hasClass('active') || $(href).is(':visible')) return false;
            $('.banner_links a.active img').attr('src','/images/mini.png');
            $('.banner_links a.active').removeClass('active');
            $(this).addClass('active').children('img').attr('src','/images/m_a.png');
            $("[id^='banner']:visible").css('display','none');
            $("[bid^='banner']:visible").css('display','none');
            $(href).css('display','block');
            $("[bid='"+href.replace('#','')+"']").css('display','block');
            if(window.bannerRightI!=null)
                clearTimeout(window.bannerRightI)
            window.bannerRightI=setTimeout(function(){var i = $('.banner_links a.active').index()+1;if(i==$('.banner_links').children('a').length) i=0; $('.banner_links a').eq(i).click();},15000);
            return false;
        })
        window.bannerRightI=setTimeout(function(){var i = $('.banner_links a.active').index()+1;if(i==$('.banner_links').children('a').length) i=0; $('.banner_links a').eq(i).click();},15000);
        
    })
</script>