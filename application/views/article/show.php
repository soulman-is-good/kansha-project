<?php
            $image = false;
            if(is_file('uploads/Article/'.$model->image)){
                $image = "/uploads/Article/".$model->image;
                $size = getimagesize(X3::app()->basePath.$image);
            }
?>
<div class="product_expand" style="position:relative">
<div style="position:absolute;top:-40px;right:0px;z-index:110"><a style="color:#3879BD" href="/news.html">читать последние новости</a></div>
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px">
                    <h2 style="margin-bottom:20px"><?=$model->title?></h2>
                </td>
                <td style="width:100%">
                    <div style="margin-top:9px" class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div style="margin-top:-24px;" class="news_round_up">
        <div style="border:none" class="mn_news">
            <span><?=$model->date()?></span>
            <?if($image):?>
            <div style="background-image:url(<?=$image?>);height:<?=$size[1]?>px" class="big_big_photo">&nbsp;</div>
            <?endif;?>
            <?=$model->text?>
        </div>
    </div>
    <?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:25px;margin-bottom:20px'));?>
</div>