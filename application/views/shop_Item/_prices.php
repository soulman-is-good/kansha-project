<?php
$city = (int)X3::user()->city || X3::user()->region;
if(is_array($city)) $city = $city[0];
$q1 = "";
$q2 = "";
if($city>0){
    $q1 = " INNER JOIN data_address a ON a.company_id=dc.id ";
    $q2 = " AND a.type=0 AND a.city='$city'";
}
$models = X3::db()->query("SELECT DISTINCT ci.id id, ci.item_id, ci.company_id, ci.price, ci.url, dc.delivery, 
    (SELECT COUNT(0) FROM company_stat WHERE company_stat.company_id=ci.company_id) AS `stats`,
    dc.title company, dc.isfree1 AS isfree, dc.login, dc.updated_at updated_at, dc.site site, dc.image
    FROM company_item ci INNER JOIN data_company dc ON dc.id=ci.company_id $q1
    WHERE dc.status AND ci.price>0 AND ci.item_id=$model->id $q2 GROUP BY ci.company_id ORDER BY dc.isfree1, dc.weight, stats DESC");

$cities = X3::db()->fetchAll("SELECT DISTINCT r.id, r.title FROM data_region r INNER JOIN data_address a ON a.city = r.id 
    INNER JOIN company_item i ON i.company_id = a.company_id WHERE i.item_id=$model->id");
/*Company_Item::get(array('@select'=>"company_item.id, company_item.item_id, company_item.company_id, company_item.price, company_item.url, (SELECT COUNT(0) FROM company_stat WHERE company_stat.company_id=dc.id) AS `stats`",
    '@condition'=>array('price'=>array('>'=>'0'),'item_id'=>$model->id),
    '@join'=>"INNER JOIN `data_company` `dc` ON `dc`.`id`=`company_item`.`company_id`",
    '@order'=>"dc.isfree, dc.weight, stats DESC"));*/
$c = mysql_num_rows($models);
?>
<div class="table_des">
    <table width="100%" class="des_header">
        <tbody><tr>
                <td style="white-space:nowrap;width:460px">
                    <table>
                        <tbody><tr>
                                <td>
                                    <a class="des_black" href="<?=$url?>.html"><span>Характеристики</span><i>&nbsp;</i></a>
                                </td>
                                <td>
                                    <a class="des_green active" href="<?=$url?>/prices.html"><span>Где купить?</span><sup><?=$mm['cnt']?></sup><i>&nbsp;</i></a>
                                </td>
                                <td>
                                    <a class="des_blue" href="<?=$url?>/feedback.html"><span>Отзывы</span><sup><?=$fcount?></sup><i>&nbsp;</i></a>
                                </td>
                                <td>
                                    <a class="des_orange" href="<?=$url?>/services.html"><span>Услуги</span><sup><?=$scount?></sup><i>&nbsp;</i></a>
                                </td>
                            </tr>
                        </tbody></table>
                </td>
                <td style="width:100%">
                    <div style="margin-top:10px" class="price_stripe">
                        <div class="price_stripe_left">&nbsp;</div>
                        <div class="price_stripe_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <table width="100%" style="margin-top:0" class="des_header">
        <tbody><tr>
                <?if($c>0):?>
                <?if(0):?>
                <td style="padding-top:20px;padding-left:15px">
                    <a class="product_price" href="#">Поднять ваши предложения выше!</a>
                </td>
                <?endif;?>
                <td>
                    <form>
                        <table width="100%" style="margin-top:4px">
                            <tbody><tr>
                                    <td>
                                    </td>
                                    <td align="right">
                                        <span>География магазинов:</span>
                                        <select name="geo_shop" onchange="update_city(this);">
                                            <option value="0">Любая</option>
                                            <? foreach ($cities as $city): ?>
                                            <?if($city['id'] == X3::user()->city):?>
                                            <option selected="selected" value="<?=$city['id']?>"><?=$city['title']?></option>
                                            <?else:?>
                                            <option value="<?=$city['id']?>"><?=$city['title']?></option>
                                            <?endif;?>
                                            <? endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            </tbody></table>
                    </form>
                </td>
            </tr>
        </tbody></table>
    <table class="price_table" style="margin-top:13px">
        <tbody>
            <tr class="price_gray">
                <td style="padding-bottom:10px">
                    <span style="margin-left:14px">Цена</span>
                </td>
                <td>
                    <span>Продавец</span>
                </td>
                <td>
                    <span>Контакты</span>
                </td>
                <td>
                    <span>Доставка</span>
                </td>
                <td>
                    <span>Адрес магазина</span>
                </td>
            </tr>
            <? 
                $fs = X3::db()->fetch("SELECT SUM(`rank`) `summ` FROM company_feedback");
                if((int)$fs['summ']==0) $fs['summ'] = 1;            
                $i=0;
            while($model=  mysql_fetch_object($models)): 
                $address = Address::get(array(
                            '@condition'=>array('company_id'=>$model->company_id,'type'=>'0'),
                            ),1);
                $phones = $address->phones;
                $fc = X3::db()->fetch("SELECT COUNT(0) `cnt`, SUM(`rank`) `summ` FROM company_feedback WHERE company_id=$model->company_id");
                $rank = floor(5*$fc['summ']/$fs['summ']);
                $_s = 5-$rank;
                $region = $address->getRegion();
                if($region != null)
                        $region = $region->title;
                $name = X3_String::create($model->company)->translit();
                $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
                ?>

            <tr class="<?=($model->isfree?'':'red_active')?>">
                <td class="<?=($model->isfree?'':'red_price')?> first">
                    <div class="price_inside" style="margin-top:0px">
                        <span class="red_cost" style="margin-top:0px"><?=  X3_String::create($model->price)->currency()?><i class="money">&nbsp;</i></span>
                        <span class="price_date">от <?=date("d.m.Y",$model->updated_at)?></span>
                        <noindex>
                        <a rel="nofollow" href="/buy/<?=$model->id?>.html">Заказать</a>
                        </noindex>
                    </div>
                </td>
                <td class="<?=($model->isfree?'':'red_price')?>">
                    <div class="go_site" style="margin-top:0px">
                        <a href="/<?=$name?>-company<?=$model->company_id?>.html" style="text-decoration: none"><img src="/uploads/Company/81x31xh/<?=$model->image?>"></a><br>
                        <div class="star_links">
                            <?for($j=0;$j<$rank;$j++):?>
                            <a class="stars" href="#"><img width="16" height="16" src="/images/ostar.png"></a>
                            <?endfor;?>
                            <?for($j=0;$j<$_s;$j++):?>
                            <a class="stars" href="#"><img width="16" height="16" src="/images/gstar.png"></a>
                            <?endfor;?>
                            
                            <a class="comment" href="/<?=$name?>-company<?=$model->company_id?>/feedback.html"><?=(int)$fc['cnt']?> <?=  X3_String::create('Отзывов')->numeral($fc['cnt'],array('Отзыв','Отзыва','Отзывов'))?></a>
                            <?if(X3_String::create($model->url)->check_protocol()):?>
                            <a class="go" href="/company/go/to/<?=  base64_encode($model->company_id.",".$model->id)?>" target="_blank">Перейти на сайт продавца</a>
                            <?endif;?>
                        </div>
                    </div>
                </td>
                <td class="<?=($model->isfree?'':'red_price')?>">
                    <div class="shops_contact">
                        <?if(count($phones)>0):?>
                        <ul class="number" style="margin-top:0px;margin-bottom:8px">
                            <? foreach ($phones as $phone): ?>
                            <li><?=$phone?></li>
                            <? endforeach; ?>
                        </ul>
                        <?endif;?>
                        <ul class="e-mail" style="margin-bottom:0px;margin-top:0px">
                            <?if(!empty($address->email)):?>
                            <li>e-mail: <a href="mailto:<?=$address->email?>"><?=$address->email?></a></li>
                            <?endif;?>
                            <?if(!empty($address->skype)):?>
                            <li>skype: <a href="skype:<?=$address->skype?>"><?=$address->skype?></a> </li>
                            <?endif;?>
                            <?if(!empty($address->icq)):?>
                            <li>icq: <a class="left" href="icq:<?=$address->icq?>"><?=$address->icq?></a> </li>
                            <?endif;?>

                        </ul>
                    </div>
                </td>
                <td class="<?=($model->isfree?'':'red_price')?>">
                    <noindex>
                    <?if(!empty($model->delivery)):?>
                    <p class="city" style="margin-top:0px"><a target="_blank" href="<?=$model->delivery?>">Весь Казахстан</a></p>
                    <?endif;?>
                    <?if(!empty($address->delivery)):?>
                    <p class="city"><a target="_blank" href="<?=$address->delivery?>"><?=$region?></a></p>
                    <?endif;?>
                    </noindex>
                </td>
                <td width="100" class="<?=($model->isfree?'':'red_price')?> last">
                    <noindex>
                    <div class="go_site" style="margin-top:0px">
                        <p style="margin-top:0px"><?=$model->title?>
                            <?=$region . (!empty($address->address)?", ".$address->address:'')?></p>

                    </div>
                    </noindex>
                </td>
            </tr><!--red_active-->
            <?if($i<$c-1):?>
            <tr height="9px"><!-- -->
            <?endif;?>
            <? $i++;endwhile; ?>
        </tbody></table>
            <?else:?>
                <td style="padding-top:32px">
                    <h4>В данное время предложения по этому товару отсутствуют</h4>
                    
                </td>    
            </tr>
        </tbody></table>    
            <?endif;?>
        <script type="text/javascript">
            function update_city(el){
                $.post('<?=$url?>/prices.html',{'city':$(el).val()},function(m){
                    if(m=='OK'){
                        location.reload();
                    }
                })
            }
        </script>        
</div>