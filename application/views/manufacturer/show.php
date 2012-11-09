<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <h2>Каталог товаров <span><?=$model->title?></span><sup>356</sup></h2>
                </td>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    
    <?= $groups?>

    <div class="product_right">
        <?if(!empty($model->link)):?>
        <a class="brand_pic" href="<?=$model->link?>"><?=$image?></a>
        <?else:?>
        <?=$image?>
        <?endif;?>
        <p class="brand_text">
            <?=$model->text?>
        </p>



    <?=  X3_Widget::run('@layouts:widgets:news.php',array(),array('cache'=>true));?>



        

    </div><!--product_right-->
    <?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:43px;margin-bottom:20px'));?>
</div>