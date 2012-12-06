<?
$acnt = X3::db()->count("SELECT 0 FROM data_address WHERE status");
$width = 240;
$width += $scount > 0 ? 140 : 0;
$width += $srcount > 0 ? 80 : 0;
$width += $acnt>0?90:0;

/*if (strpos($_SERVER['HTTP_REFERER'], 'service') != false)
    $type = 1;
else
    $type = 0;*/
?>
<table class="three_inset" width="100%">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;width:<?= $width ?>px">
                <a class="nero active" href="#" onclick="return false;"><span>О Компании<i>&nbsp;</i></span></a>
                <?if($acnt>0):?>
                <a class="des_green" href="<?=$url?>/address.html"><span>Адреса<i>&nbsp;</i></span></a>
                <?endif;?>                
                <a class="blu" href="<?= $url ?>/feedback.html"><span>Отзывы</span><sup><?= $fcount ?></sup></a>
                <? if ($scount > 0): ?>
                    <a class="rosso" href="<?= $url ?>/sale.html"><span>Распродажи</span><sup><?= $scount ?></sup></a>
                <? endif; ?>
                <? if ($srcount > 0): ?>
                    <a class="arancione" href="<?= $url ?>/services.html"><span>Услуги</span><i>&nbsp;</i></a>
                <? endif; ?>
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
    <?= $model->text ?>
</div>