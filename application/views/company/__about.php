<table class="three_inset">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;">
                <a class="nero active" href="#" onclick="return false;"><span>О Компании<i>&nbsp;</i></span></a>
                <a class="blu" href="<?=$url?>/feedback.html"><span>Отзывы</span><sup><?=$fcount?></sup></a>
                <?if($scount>0):?>
                <a class="rosso" href="<?=$url?>/sale.html"><span>Распродажи</span><sup><?=$scount?></sup></a>
                <?endif;?>
                <?if($srcount>0):?>
                <a class="arancione" href="<?=$url?>/services.html"><span>Услуги</span><i>&nbsp;</i></a>
                <?endif;?>
            </td>
            <td>
                <div style="margin-top:9px" class="black_stripe">
                    <div class="left_stripe_black">&nbsp;</div>
                    <div class="right_stripe_black">&nbsp;</div>
                </div>
            </td>
        </tr>
    </tbody></table>
<div class="shops_p_color">
<?=$model->text?>
</div>