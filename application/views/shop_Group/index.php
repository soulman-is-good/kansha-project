<?php
$col = ceil(count($models)/3);
?>
<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <h2>Все разделы</h2><?/*<sup><?=$count?> <?= X3_String::create($count)->numeral($count,array('товар','товара','товаров'))?> в <?=$gcount?> <?= X3_String::create($gcount)->numeral($gcount,array('разделе','разделах','разделах'))?></sup>*/?>
                </td>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div class="sub_menu_sitemap" style="border-top:none">
    <table style="width:100%">
        <tbody><tr>
                <td width="33%">
                    <? $i=0;foreach ($models as $model): ?>
                    <div class="sub_menu_list">
                        <div class="name">
                            <img style="position:relative;left:-5px" src="/uploads/Shop_Category/<?=$model['model']->image?>">
                            <h3><a href="<?="/".strtolower(X3_String::create($model['model']->title)->translit(false,"'"))."-c".$model['model']->id;?>.html"><?=$model['model']->title?></a><sup><?=$model['model']->count?></sup></h3></div>
                        <div class="link_list">
                            <? foreach ($model['models'] as $m): ?>
                            <a href="<?=$m->url?>"><?=$m->title?> <sup style="color:#222;border:none;text-decoration:none;"><?=$m->count?></sup></a>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <?if($i>0 && $i%$col==0) echo '</td><td width="33%">'?>
                    <?$i++;endforeach;?>
                </td>
            </tr>
        </tbody></table>    
    </div>
    <?/*
    <!--div style="margin-top:-22px" class="product_left_side">
        <ul class="green">
            <li class="active"><a href="#">Компьютеры</a>
                <ul class="blue">
                    <li><a href="#">Ноутбуки</a><sup>18</sup></li>
                    <li><a href="#">Нетбуки</a><sup>19</sup></li>
                    <li><a href="#">Tablet</a><sup>19</sup></li>
                    <li><a href="#">UMPC</a><sup>13</sup></li>
                    <li><a href="#">Неттопы </a><sup>13</sup></li>
                    <li><a href="#">Моноблоки </a><sup>24</sup></li>
                    <li><a href="#">Настольные ПК</a><sup>32</sup></li>
                    <li><a href="#">Планшеты </a><sup>18</sup></li>
                    <li><a href="#">Электронные книги</a><sup>18</sup></li>
                    <li><a href="#">Электронные книги</a><sup>18</sup></li>
                    <li><a href="#">Игровые приставки</a><sup>5</sup></li>
                    <li><a href="#">USB flash drive</a><sup>21</sup></li>
                    <li><a href="#">Карты памяти </a><sup>21</sup></li>
                </ul>
            </li>
            <li><a href="#">Комплектующие</a></li>
            <li><a href="#">Сети и интернет</a></li>
            <li><a href="#">Видеотехника</a></li>
            <li><a href="#">Аудиотехника</a></li>
            <li><a href="#">Фототехника</a></li>
            <li><a href="#">Техника для дома</a></li>
            <li><a href="#">Красота и здоровье</a></li>
            <li><a href="#">Расходники</a></li>
            <li><a href="#">Переферия</a></li>
            <li><a href="#">Средства связи</a></li>
            <li><a href="#">Программное обеспечение</a></li>
            <li><a href="#">Техника для кухни</a></li>
            <li><a href="#">Климатическая техника</a></li>
            <li><a href="#">Аксессуары</a></li>
        </ul>
    </div><!--product_left_side-->

    <div class="product_right razdely" style="margin-left:0">
        <table width="100%" class="brands">
            <tbody><tr>
                    <td>
                        <ul class="green">
                        <? $i=0;foreach ($models as $model): ?>
                            <li class="active">
                                <a href="<?="/".strtolower(X3_String::create($model['model']->title)->translit(false,"'"))."-c".$model['model']->id;?>.html"><?=$model['model']->title?></a>
                                <ul class="blue">
                                    <? foreach ($model['models'] as $m): ?>
                                    <li><a href="<?=$m->url?>"><?=$m->title?></a><sup><?=$m->count?></sup></li>
                                    <?if($i>0 && $i%$col==0){
                                        echo '</ul></li></ul></td><td><ul class="green"><li class="active"><ul class="blue">';
                                    }?>
                                    <? $i++;endforeach; ?>
                                </ul>
                            </li>
                        <? $i++;endforeach; ?>
                        </ul>
                    </td>

                </tr>
            </tbody></table>


    </div><!--product_right-->*/?>
    <?= X3_Widget::run('@layouts:widgets:BannerBottom.php', array('style' => 'margin-top:43px;margin-bottom:20px')); ?>

</div>