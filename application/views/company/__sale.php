<table class="three_inset">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;">
                <a class="nero" href="<?= $url ?>.html"><span>О Компании</span></a>
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