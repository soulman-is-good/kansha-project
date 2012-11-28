<?
$width = 240;
$width += $scount > 0 ? 140 : 0;
$width += $srcount > 0 ? 80 : 0;

/*if (strpos($_SERVER['HTTP_REFERER'], 'service') != false)
    $type = 1;
else
    $type = 0;*/
$addreses = X3::db()->query("SELECT r.title AS city,a.city AS city_id, a.phones, a.fax, skype, icq, delivery, address, email
    FROM data_address a INNER JOIN data_region r ON r.id=a.city WHERE a.company_id=$model->id ORDER BY city");
?>
<table class="three_inset" width="100%">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;width:<?= $width ?>px">
                <a class="nero active" href="#" onclick="return false;"><span>О Компании<i>&nbsp;</i></span></a>
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
    <?if (is_resource($addreses)):?>
    <table class="shops_about_upper"><tr><td>
    <?  $city = '';
        while ($address = mysql_fetch_assoc($addreses)):
            $phones = explode(';;', $address['phones']);
            ?>
            <? if ($city != $address['city']): ?>
                <h2><?= $city = $address['city'] ?></h2>    
        <? endif; ?>
            <table class="shops_upper_right">
                <tbody><tr>

                        <td class="first">
                            <span>Телефоны:</span>
                        </td>
                        <td>
                            <ul style="padding:0;margin:0">
                                <? foreach ($phones as $phone): ?>
                                    <li style="background:none;padding:0"><?= $phone ?></li>
        <? endforeach; ?>
                            </ul>
                        </td>

                    </tr>
                    <tr class="pustoi">
                        <td colspan="2">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td class="first">
                            <span>Адрес: </span>
                        </td>
                        <td class="second">
                            <span><?= $address['address'] ?></span>
                        </td>
                    </tr>
                    <tr class="pustoi"><td colspan="2">&nbsp;</td></tr>
                    <?if(trim($address['email'])!=''):?>
                    <tr>
                        <td class="first">
                            <span>E-mail:</span>
                        </td>
                        <td>
                            <a class="black" href="mailto:<?=$address['email']?>"><?=$address['email']?></a>
                        </td>
                    </tr>
                    <?endif;?>
                    <?if(trim($address['skype'])!=''):?>
                    <tr>
                        <td class="first">
                            <span>Skype:</span>
                        </td>
                        <td>
                            <a href="skype:<?=$address['skype']?>" class="black" ><?=$address['skype']?></a>
                        </td>
                    </tr>
                    <?endif;?>
                    <?if(trim($address['icq'])!=''):?>
                    <tr>
                        <td class="first">
                            <span>ICQ:</span>
                        </td>
                        <td>
                            <?$icqs = explode(',',$address['icq']);
                            foreach($icqs as $i=>$icq):?>
                            <a href="icq:<?=$icq?>" class="black"><?=$icq?></a><?if(count($icq)-1<$i) echo '<br/>';?>
                            <?endforeach?>
                        </td>
                    </tr>
                    <?endif;?>
                    <tr class="pustoi"><td colspan="2">&nbsp;</td></tr>
                </tbody></table>    
            <hr/>
        <? endwhile; ?>
            </td></tr></table>
<? endif; ?>
</div>