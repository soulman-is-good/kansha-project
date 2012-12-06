<?php
$phones = $company->getAddress()->phones;
?>
<div class="product_expand">

    <table class="product_des" width="100%">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px;width:<?=mb_strlen("Распродажа $model->title у $company->title")*10?>px">
                    <h2 class="long"><span class="pink">Распродажа</span> <?=$model->title?> у <span><?=$company->title?></span></h2>
                </td>
                <td>
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>

    <table width="100%" class="order">
        <tbody><tr>
                <td>
                    <div style="background-image:url(/uploads/Sale/300x300xf/<?=$model->image?>)" class="order_big_pic">&nbsp;</div>
                </td>
                <td>
                    <table>
                        <tbody><tr>
                                <td width="147">
                                    <span class="red_red"><?=X3_String::create($model->price)->currency()?><img width="12" height="18" src="/images/big_t.jpg"></span>
                                    <span class="little_green">До: <?=$model->date()?></span>
                                    <div class="order_phone">
                                        <h4>Телефоны:</h4>
                                        <ul>
                                            <? foreach ($phones as $phone): ?>
                                            <li><?=$phone?></li>
                                            <? endforeach; ?>
                                        </ul>
                                    </div>
<?/*                                    <h4>Время работы</h4>

                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">в</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day" href="#">ч</a>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day active" href="#">в</a>

                                    <span style="display:block;margin-top:9px" class="order_span">с 10:00 до 20:00</span>
 */?>
                                </td>
                                <td>
                                    <table>
                                        <tbody><tr>
                                                <td>
                                                    <img src="/uploads/Company/125x57xh/<?=$company->image?>" alt="<?=$company->title?>" title="<?=$company->title?>" />
                                                    <a class="blue" href="<?=$company->getLink().html?>">Профиль продавца</a>
                                                </td>
                                                <td style="padding-left:28px">
                                                    <?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('company_id'=>$company->id),'url'=>$company->getLink()."/feedback.html"))?>
                                                    <span class="order_span"><?=$company->title?></span>
                                                    <a class="blue" href="/company/go/to/<?=base64_encode($company->id)?>">Перейти на сайт продавца</a>

                                                </td>
                                            </tr>

                                        </tbody></table>
                                    <?if($company->delivery!=''):?>
                                    <table>
                                        <tbody><tr>
                                                <td>
                                                    <h4>Условия доставки</h4>
                                                    <p><?=$company->delivery?></p>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    <?endif;?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h4>Характеристики</h4>
                                    <?=$model->text?>

                                </td>
                            </tr>
                        </tbody></table>
                </td>
            </tr>

        </tbody></table>





<?if($model->gifttitle!=''):?>
    <div class="table_des">

        <table class="product_des">
            <tbody><tr>
                    <td style="padding-right:10px;white-space:nowrap;height:19px">
                        <h2 class="sale_in">Подарок для <span class="less"><?=$model->title?></span></h2>
                    </td>
                    <td width="100%">
                        <div class="black_stripe">
                            <div class="left_stripe_black">&nbsp;</div>
                            <div class="right_stripe_black">&nbsp;</div>
                        </div>
                    </td>
                </tr>
            </tbody></table>
        <table class="sale_in_box">
            <tbody><tr>
                    <td>
                        <div style="background-image:url(/uploads/Sale/115x130xf/<?=$model->giftimage?>)" class="sale_in_pic">&nbsp;</div>
                    </td>
                    <td>
                        <h4><?=$model->gifttitle?></h4>
                        <?=$model->gifttext?>
                    </td>
                </tr>
            </tbody></table>




    </div><!--table_des-->
<?endif;?>



<?/*
    <table style="position:relative;width:100%;margin-top:10px" class="company_service">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px">
                    <h2>Услуги для <span>Samsung i9100 Galaxy S II (16Gb)</span></h2>
                </td>
                <td width="100%">
                    <div style="width:auto" class="orange_company">
                        <div class="orange_left">&nbsp;</div>
                        <div class="orange_right">&nbsp;</div>
                    </div>

                </td>
            </tr>
        </tbody></table>

    <table width="100%" class="des_table_list">
        <tbody><tr>
                <td>
                    <ul>
                        <li><a href="#">Адаптация</a><sup>6</sup></li>
                        <li><a href="#">Восстановление</a><sup>5</sup></li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><a href="#">Консультации</a><sup>6</sup></li>
                        <li><a href="#">Модернизация</a><sup>5</sup></li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><a href="#">Ремонт</a><sup>6</sup></li>
                        <li><a href="#">Диагностика</a><sup>5</sup></li>
                    </ul>
                </td>
                <td width="170">
                    <ul>
                        <li><a href="#">Гарантийное обслуживание</a><sup>6</sup></li>
                        <li><a href="#">Прошивка</a><sup>5</sup></li>
                    </ul>
                </td>
            </tr>
        </tbody></table>

    <table class="des_buy">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px">
                    <h2>Покупают для <span>Samsung GT-S5830 Galaxy Ace</span></h2>
                </td>
                <td width="100%">
                    <div class="des_stripe">
                        <div class="des_stripe_left">&nbsp;</div>
                        <div class="des_stripe_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="des_box">
                        <div style="background-image:url(./uploads/image1.jpg)" class="des_box_pic">&nbsp;</div>
                        <div class="sale_in_box_text">
                            <h4><a href="#">Bluetooth-гарнитура</a></h4>
                            <p>Самая полная база предложений в Казахстане позволит вам купить bluetooth наушники по выгодной цене. Технические характеристики беспроводной гарнитуры, 
                                удобная сортировка, фото и сравнение товаров - созданы для удобства выбора беспроводных наушников - вы получаете необходимый продукт за минимальную цену.</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="des_box">
                        <div style="background-image:url(./uploads/image2.jpg)" class="des_box_pic">&nbsp;</div>
                        <div class="sale_in_box_text">
                            <h4><a href="#">Bluetooth-гарнитура</a></h4>
                            <p>Самая полная база предложений в Казахстане позволит вам купить bluetooth наушники по выгодной цене. Технические характеристики беспроводной гарнитуры, 
                                удобная сортировка, фото и сравнение товаров - созданы для удобства выбора беспроводных наушников - вы получаете необходимый продукт за минимальную цену.</p>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody></table>
*/?>

    <?/*<table style="width:100%" class="blue_stripes">
        <tbody><tr>
                <td style="width:75px">
                    <h2>Комментарии<sup>0</sup></h2>
                </td>
                <td>
                    <div class="one_blue_stripe">
                        <div class="blue_left">&nbsp;</div>
                        <div class="blue_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div style="background-image:url(./uploads/vk_box.jpg)" class="sale_in_vk">&nbsp;</div>
*/?>
    <div class="red_info">
        <img width="39" height="29" src="/images/tungle.png">
        <p><?= SysSettings::getValue('BuyerText', 'content', 'Ответственность', null, '') ?></p>

    </div>
</div>
<?=X3_Widget::run('@layouts:widgets:BannerBottom.php')?>