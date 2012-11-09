<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <h2><?=$model->title?><sup><?=$count?></sup></h2>
                </td>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div class="product_left_side">
        <ul class="brand_catalog">
            <? foreach ($groups as $group): ?>
            <li><a href="<?=$group['url']?>"><?=$group['title']?></a></li>
            <? endforeach; ?>
        </ul>

        <?//X3_Widget::run('@layouts:widgets:popularBrands.php');?>
    </div><!--product_left_side-->

    <div class="product_right">
        <table width="100%" class="article_with_pic">
            <tbody><tr>
                    <? $i=0;foreach ($groups as $group): 
                        $im = trim($group['image'],'/');
                        if(is_file($im)){
                            $size = getimagesize($im);
                        }else
                            $size = array(135,107);
                        ?>
                    <?if($i%3==0 && $i>0) echo'</tr><tr>';?>
                    <td>
                        <div class="article_box">
                            <div style="background-image:url(<?=$group['image']?>)" class="article_box_pic"><a href="<?=$group['url']?>" style="text-decoration: none;">
                                <img src="/images/_zero.gif" width="<?=$size[0]?>" height="<?=$size[1]?>" />
                                </a></div>
                            <div class="article_text">
                                <a href="<?=$group['url']?>"><?=$group['title']?></a>
                            </div>
                        </div>
                    </td>
                    <?$i++; endforeach; ?>
                    <?$i = $i%3; if($i%3>0);while($i-->=0) echo $i==0?'<td></td>':'<td></td>';?>
            </tbody></table>

<?//  X3_Widget::run('@layouts:widgets:serviceForCat.php',array('model'=>$model))?>

<?/*        <table width="100%" class="des_table_list">
            <tbody><tr><td><span>По названию</span></td></tr>
                <tr>
                    <td>
                        <ul>
                            <li><a href="#">Адаптация</a><sup>6</sup></li>
                            <li><a href="#">Восстановление</a><sup>5</sup></li>
                            <li><a href="#">Диагностика</a><sup>5</sup></li>
                            <li><a href="#">Модернизация</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><a href="#">Монтаж</a><sup>6</sup></li>
                            <li><a href="#">Прошивка</a><sup>5</sup></li>
                            <li><a href="#">Разблокировка</a><sup>5</sup></li>
                            <li><a href="#">Ремонт</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td style="width:170px">
                        <ul>
                            <li><a href="#">Русификация</a><sup>6</sup></li>
                            <li><a href="#">Сервисное обслуживание</a><sup>5</sup></li>
                            <li><a href="#">Гарантийное обслуживание</a><sup>5</sup></li>
                            <li><a href="#">Установка ПО</a><sup>5</sup></li>
                        </ul>
                    </td>

                </tr>

                <tr><td class="article_name"><span>По названию</span></td></tr>
                <tr>
                    <td class="green">
                        <ul>
                            <li><a href="#">Адаптация</a><sup>6</sup></li>
                            <li><a href="#">Восстановление</a><sup>5</sup></li>
                            <li><a href="#">Диагностика</a><sup>5</sup></li>
                            <li><a href="#">Модернизация</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td class="green">
                        <ul>
                            <li><a href="#">Монтаж</a><sup>6</sup></li>
                            <li><a href="#">Прошивка</a><sup>5</sup></li>
                            <li><a href="#">Разблокировка</a><sup>5</sup></li>
                            <li><a href="#">Ремонт</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td class="green" style="width:170px">
                        <ul>
                            <li><a href="#">Русификация</a><sup>6</sup></li>
                            <li><a href="#">Сервисное обслуживание</a><sup>5</sup></li>
                            <li><a href="#">Гарантийное обслуживание</a><sup>5</sup></li>
                            <li><a href="#">Установка ПО</a><sup>5</sup></li>
                        </ul>
                    </td>

                </tr>
            </tbody></table>*/?>

    </div><!--product_right-->
    <div class="clear-both">&nbsp;</div>
</div>