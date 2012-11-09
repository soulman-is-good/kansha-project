<?php

?>
<div class="product_expand">
    <div style="margin-top:20px;" class="news_round_up">
        <table style="margin-bottom:43px">
            <tbody><tr>
                    <td>
                        <div class="news_round_up_inside">
                            <?if($nc>0 && $ac>0):?>
                            <a class="news active" href="#anews"><span>Новости</span><i>&nbsp;</i></a>
                            <a class="news" href="#article"><span>Обзоры</span><i>&nbsp;</i></a>
                            <?elseif($nc>0):?>
                            <a class="news active" href="#"><span>Новости</span><i>&nbsp;</i></a>
                            <?elseif($ac>0):?>
                            <a class="news active" href="#"><span>Обзоры</span><i>&nbsp;</i></a>
                            <?endif;?>
                        </div>
                    </td>
                    <td style="width:100%">
                        <div class="blue_blue_stripe">
                            <div class="blue_left">&nbsp;</div>
                            <div class="blue_right">&nbsp;</div>
                        </div>
                    </td>
                </tr>
            </tbody></table>
        <?if($nc>0):?>
        <div id="anewsi" class="nnewsm">
        <? foreach ($news as $i=>$model): 
            $image = false;
            if(is_file('uploads/News/'.$model->image)){
                $image = "/uploads/News/139x83xf/".$model->image;
                $size = getimagesize(X3::app()->basePath.$image);
            }
            ?>
            <div class="mn_news news">
                <span><?=$model->date()?></span>
                <h3><a href="/news/<?=$model->id?>.html"><?=$model->title?></a></h3>
                <?if($image):?>
                <div class="mn_news_photo"><a href="/news/<?=$model->id?>.html" style="text-decoration: none"><img src="<?=$image?>" /></a></div>
                <?endif;?>
                <p class="mn_news_text"><?=X3_String::create(strip_tags($model->text))->carefullCut(512)?></p>
            </div>
        <? endforeach; ?>
    <div style="margin-top:22px" class="sale_selector expand">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$pagnews));?>
    </div>
    <div style="float:none" class="navi">
        <?=$pagnews?>
    </div>
        </div>
        <?endif;?>
        <?if($ac>0):?>
        <div id="articlei"  <?if($nc>0)echo ' style="display:none"'?> class="nnewsm">
        <? foreach ($articles as $i=>$model): 
            $image = false;
            if(is_file('uploads/Article/'.$model->image)){
                $image = "/uploads/Article/139x83xf/".$model->image;
                $size = getimagesize(X3::app()->basePath.$image);
            }
            ?>
            <div class="mn_news news">
                <span><?=$model->date()?></span>
                <h3><a href="/article/<?=$model->id?>.html"><?=$model->title?></a></h3>
                <?if($image):?>
                <div class="mn_news_photo"><a href="/article/<?=$model->id?>.html" style="text-decoration: none"><img src="<?=$image?>" /></a></div>
                <?endif;?>
                <p class="mn_news_text"><?=X3_String::create(str_replace('\r\n',"",strip_tags($model->text)))->carefullCut(512)?></p>
            </div>
        <? endforeach; ?>
    <div style="margin-top:22px" class="sale_selector expand">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$pagarts));?>
    </div>
    <div style="float:none" class="navi">
        <?=$pagarts?>
    </div>
        </div>
        <?endif;?>
    </div>
    <?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:25px;margin-bottom:20px'));?>

</div>
<script type="text/javascript">
    $('.product_expand').css('display','none');
    $('.news_round_up_inside a.news').click(function(){
        if($(this).hasClass('active')) return false;
        $(this).siblings('a.news').removeClass('active');
        $(this).addClass('active');
        $('.nnewsm').css('display','none');
        $($(this).attr('href')+'i').css('display','block')
    })
    $('.mn_news h3 a').click(function(){
        if(!$(this).hasClass('active')){
            $(this).parent().siblings('div').slideDown();
            $(this).addClass('active');
            return false;
        }
    })
    var article = <?=isset($_GET['article'])?'1':'0'?>;
    $(function(){
        var h = location.hash;
        if(article == 1)
            h = '#article';
        if(h.indexOf('#')==0 && (h=='#anews' || h=='#article')){
            $('.news_round_up_inside .news').removeClass('active');
            $('.news_round_up_inside .news[href="'+h+'"]').addClass('active');
            $('.nnewsm').css('display','none');
            $(h+'i').css('display','block')
        }
        $('.product_expand').css('display','block');
    })
</script>