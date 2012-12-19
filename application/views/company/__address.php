<?
$width = 310;
$width += $scount > 0 ? 140 : 0;
$width += $srcount > 0 ? 80 : 0;

/*if (strpos($_SERVER['HTTP_REFERER'], 'service') != false)
    $type = 1;
else
    $type = 0;*/
$addreses = X3::db()->query("SELECT r.title AS city,r.name AS name, a.city AS city_id, a.phones, a.fax, skype, icq, delivery, address, email, type
    FROM data_address a INNER JOIN data_region r ON r.id=a.city WHERE a.company_id=$model->id ORDER BY type, ismain DESC, r.title");
?>
<table class="three_inset" width="100%">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;width:<?= $width ?>px">
                <a class="nero" href="<?=$url?>.html"><span>О Компании</span></a>
                <a class="des_green active" href="<?=$url?>/address.html" onclick="return false;"><span>Адреса<i>&nbsp;</i></span></a>
                <a class="blu" href="<?= $url ?>/feedback.html" style="margin-left:10px"><span>Отзывы</span><sup><?= $fcount ?></sup></a>
                <? if ($scount > 0): ?>
                    <a class="rosso" href="<?= $url ?>/sale.html"><span>Распродажи</span><sup><?= $scount ?></sup></a>
                <? endif; ?>
                <? if ($srcount > 0): ?>
                    <a class="arancione" href="<?= $url ?>/services.html"><span>Услуги</span><i>&nbsp;</i></a>
                <? endif; ?>
            </td>
            <td>
                <div style="margin-top:9px" class="price_stripe">
                    <div class="price_stripe_left">&nbsp;</div>
                    <div class="price_stripe_right">&nbsp;</div>
                </div>
            </td>
        </tr>
    </tbody></table>
<div class="shops_p_color">
    <?if (is_resource($addreses)):?>
    <table class="shops_about_upper" width="100%"><tr><td>
                <div class="addresses"><div class="ctype"><h2><a class="active" href="#type0">Магазины</a></h2><div style="margin-top:10px" id="type0">
    <?  $city = '';
        $type = 0;
        while ($address = mysql_fetch_assoc($addreses)):
            $phones = explode(';;', $address['phones']);
            ?>
            <? if ($type != $address['type']){ echo '</div></div><div class="ctype"><h2 style="left:120px;"><a href="#type1">Сервис-центры</a></h2><div id="type1" style="margin-top:10px;display:none">';$type = $address['type'];$city=''; }?>
            <? if ($city != $address['city']): ?>
            <?endif;?>
            <? if ($city != $address['city']): ?>
                <h3><a name="<?=$address['name']?>">&nbsp;</a><?= $city = $address['city'] ?></h3>
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
                    </div></div></div>
            </td></tr></table>
    <script>
        $(function(){
            $('.ctype').each(function(){
                var self = $(this);
                self.children('h2').children('a').click(function(){
                    var id = $(this).attr('href');
                    $('.ctype a.active').removeClass('active');
                    $('.ctype div[id^="type"]').css('display','none');
                    $(this).addClass('active');
                    $(id).css('display','block');
                    return false;
                })
            })
            var h = location.hash.replace('#','');
            if(h!=''){
                h = h.split('-');
                if(h[0] == 'service'){
                    $('[href="#type1"]').click();
                }else
                    $('[href="#type0"]').click();
                location.href = '#'+h[1];
            }
        })
    </script>    
<? endif; ?>
</div>