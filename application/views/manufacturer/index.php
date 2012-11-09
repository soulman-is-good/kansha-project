<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <h2>Каталог производителей<sup><?=$count?></sup></h2>
                </td>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>

    <?//echo  $groups?>
    <div class="product_right list_text" style="margin-left:0">
        <?= $list?>
        <?=  X3_Widget::run('@layouts:widgets:news.php',array('model'=>$model),array('cache'=>false));?>

    </div><!--product_right-->
    <?= X3_Widget::run('@layouts:widgets:BannerBottom.php', array('style' => 'margin-top:43px;margin-bottom:20px')); ?>

</div>
