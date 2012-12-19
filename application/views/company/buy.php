<?
$address = $company->getAddress();
$name = X3_String::create($company->title)->translit();
$name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
$prices = X3::db()->fetch("SELECT MIN(price) `min`, MAX(price) `max`, COUNT(0) `cnt` FROM company_item WHERE item_id=$item->id AND price>0");
$url = '/'.strtolower(X3_String::create($item->title)->translit()).($item->articule!=''?"-($item->articule)-":'-').$item->id;
?>
<div class="product_expand">

    <table class="product_des">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <h1 class="long">Заказ <?=$item->getGroup()->usename?$item->title.$item->withArticule():$item->suffix?> у <span><?=$company->title?><span></span></span></h1>
                </td>
                <td style="width:100%">
                    <div style="margin-top:9px" class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div style="position:relative;top:-20px;font:11px Tahoma,sans-serif;color: #666666"><?=str_replace('X',$company->title,nl2br(SysSettings::getValue('Company.Sales', 'content','Описание Заказать', null, 'Для заказа данного товара у компании X пожалуйста позвоните по указанному ниже телефону или перейдите на сайт продавца.')))?></div>
    <table width="100%" class="order">
        <tbody><tr>
                <td width="176px">
                    <div style="background-image:url(/uploads/Shop_Item/176x176xf/<?=$item->image?>)" class="order_big_pic">&nbsp;</div>
                </td>
                <td>
                    <table>
                        <tbody><tr>
                                <td width="147">
                                    <span class="red_red"><?=X3_String::create($model->price)->currency()?><img width="12" height="18" src="/images/big_t.jpg"></span>
                                    <span class="little_green">Обновлено: <?=(date('d.m.Y')==date('d.m.Y',$company->updated_at)?'сегодня':date('d.m.Y',$company->updated_at))?></span>
                                    <div class="order_phone">
                                        <h4>Телефоны:</h4>
                                        <ul>
                                            <? foreach ($address->phones as $z=>$phone): if($z>0 && $z%4==0) break;?>
                                            <li><?=$phone?></li>
                                            <? endforeach; ?>
                                        </ul>
                                    </div>
                                    <?/*
                                    <h4>Время работы</h4>

                                    <a class="day<?=$address->worktime->days->Monday==1?' active':''?>" href="#">п</a>
                                    <a class="day<?=$address->worktime->days->Tuesday==1?' active':''?>" href="#">в</a>
                                    <a class="day<?=$address->worktime->days->Wednesday==1?' active':''?>" href="#">с</a>
                                    <a class="day<?=$address->worktime->days->Thursday==1?' active':''?>" href="#">ч</a>
                                    <a class="day<?=$address->worktime->days->Friday==1?' active':''?>" href="#">п</a>
                                    <a class="day<?=$address->worktime->days->Saturday==1?' active':''?>" href="#">с</a>
                                    <a class="day<?=$address->worktime->days->Sunday==1?' active':''?>" href="#">в</a>

                                    <span style="display:block;margin-top:9px" class="order_span"><?=$address->worktime->times[0]?></span>
                                     */?> 
                                </td>
                                <td>
                                    <table>
                                        <tbody><tr>
                                                <td>
                                                    <img src="/uploads/Company/125x57xf/<?=$company->image?>" />
                                                    <?if(!empty($model->url)):?>
                                                    <noindex>
                                                    <a rel="nofollow" class="blue" target="_blank" href="/company/go/to/<?=  base64_encode($company->id.','.$model->id)?>">Перейти на сайт продавца</a>
                                                    </noindex>
                                                    <?endif;?>
                                                </td>
                                                <td style="padding-left:28px">
<?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('company_id'=>$company->id),'url'=>"/$name-company$company->id/feedback.html"))?>
                                                    <span class="order_span"><?=$company->title?></span>
                                                    <a class="blue" href="/<?=$name?>-company<?=$company->id?>.html">Профиль продавца</a>
                                                </td>
                                            </tr>

                                        </tbody></table>
                                    <?if(trim($company->delivery_rules)!=''):?>
                                    <table>
                                        <tbody><tr>
                                                <td>
                                                    <h4>Условия доставки</h4>
                                                    <p><?=$company->delivery_rules?></p>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    <?endif;?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h4>Характеристики</h4>
                                    <p><?=$item->getPropstext()?></p>
                                    <table>
                                        <tbody><tr>
                                                <td style="padding-right:20px">
                                                    <span class="red_cost"><?=  X3_String::create($prices['min'])->currency();?><i class="money">&nbsp;</i><i class="dash">-</i></span>
                                                    <span class="red_cost"><?=  X3_String::create($prices['max'])->currency();?><i class="money">&nbsp;</i></span>
                                                </td>
                                                <td style="padding-top:10px;padding-right:18px">
                                                    <a class="product_info_price" href="<?=$url?>/prices.html">Сравнить цены</a>
                                                </td>
                                                <td style="padding-top:10px">
                                                    <span class="market">  в <?=$prices['cnt']?> <?=  X3_String::create('')->numeral($prices['cnt'],array('магазне','магазнах','магазнах'))?></span>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                </td>
                            </tr>
                        </tbody></table>
                </td>
            </tr>

        </tbody></table>






    <div class="table_des">



    </div><!--table_des-->





<?=$this->renderPartial('@layouts:widgets:servicesFor.php',array('model'=>$item))?>
<?=$this->renderPartial('@layouts:widgets:buyWith.php',array('model'=>$item))?>

    <div class="red_info">
        <img width="39" height="29" src="/images/tungle.png">
        <p><?=SysSettings::getValue('BuyerText', 'content', 'Ответственность', null, '')?></p>

    </div>



</div>