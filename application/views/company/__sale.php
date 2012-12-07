<?
$acnt = X3::db()->count("SELECT 0 FROM data_address WHERE status");
$width = 380;
$width += $srcount>0?80:0;
$width += $acnt>0?90:0;
?>
<table class="three_inset" width="100%">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;width:<?=$width?>px">
                <a class="nero" href="<?= $url ?>.html"><span>О Компании</span></a>
                <?if($acnt>0):?>
                <a class="des_green" href="<?=$url?>/address.html"><span>Адреса<i>&nbsp;</i></span></a>
                <?endif;?>                
                <a class="blu" href="<?= $url ?>/feedback.html"><span>Отзывы</span><sup><?= $fcount ?></sup></a>
                <a class="rosso active" href="#" onclick="return false;"><span>Распродажи</span><sup><?= $scount ?></sup><i>&nbsp;</i></a>
                <? if ($srcount > 0): ?>
                <a class="arancione" href="<?=$url?>/services.html"><span>Услуги</span></a>
                <? endif; ?>
            </td>
            <td>
                <div class="orange_company">
                    <div class="orange_left">&nbsp;</div>
                    <div class="orange_right">&nbsp;</div>
                </div>
            </td>
        </tr>
    </tbody></table>
<div class="upper_profile_text">
    <div class="upper_text_text">
        <p>Пока нет распродаж</p>
    </div>
</div>