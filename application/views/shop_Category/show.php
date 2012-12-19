<?
$gids = array();
?>
<style>
    .product_expand h6, .product_expand p {
        margin-top:0;
    }
    .product_expand .mgr_product {
        float: left;
        margin-left: 20px;
        position: relative;
        margin-bottom:10px;
    }
</style>
<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <header>
                    <h1><?=$model->title?><sup><?=$count?></sup></h1>
                    </header>
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
            <? foreach ($groups as $group): $gids[] = $group['id']; ?>
            <li><a href="<?=$group['url']?>"><?=$group['title']?></a></li>
            <? endforeach; ?>
        </ul>

        <?//X3_Widget::run('@layouts:widgets:popularBrands.php');?>
    </div><!--product_left_side-->

    <div class="product_right">
    <article><h6 style="color: #000;font-weight:normal;">
            <?=$model->text?>
        </h6></article>

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
<?php
$items = X3::db()->query("SELECT si.*, MIN(ci.price) price FROM shop_item si INNER JOIN company_item ci ON ci.item_id=si.id WHERE si.status AND si.group_id IN ('".implode("','",$gids)."') GROUP BY ci.item_id ORDER BY RAND() LIMIT 6");
if(is_resource($items) && mysql_num_rows($items)>0):
?>
        <div id="caru" style="height:187px;width:100%;overflow:hidden;position:relative;">
            <?$i=0;while($item = mysql_fetch_assoc($items)):
                $mdl = new Shop_Item();
                $mdl->getTable()->acquire($item);
                $mdl->getTable()->setIsNewRecord(false);
                ?>
            <div class="mgr_product">
                <a class="pic" href="<?=$mdl->getLink()?>.html"><div style="background-image:url(/uploads/Shop_Item/113x100xf/<?=$mdl->image?>)" class="mgr_box_picture">&nbsp;</div></a>
                <div class="main_product_info">
                    <a class="product_info" title="<?=$mdl->title?>" href="<?=$mdl->getLink()?>.html"><?=$mdl->title?></a>
                    <span>от <b><?=$item['price']?><i>&nbsp;</i></b></span>
                    <a class="product_info_price" href="<?=$mdl->getLink()?>/prices.html">Сравнить цены</a>
                </div>
            </div>
            <?$i++;endwhile;?>
        </div>        
<?endif;?>    
<div class="product_expand" style="margin-top:25px;">
    <table class="production" width="100%">
        <tbody><tr>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
</div>
        <table width="100%" class="article_with_pic" style="margin-top:30px">
            <tbody><tr>
                    <? $i=0;foreach ($groups as $group): 
                        $im = trim($group['image'],'/');
                        if(is_file($im)){
                            $size = getimagesize($im);
                        }else
                            $size = array(135,107);
                        ?>
                    <?if($i%4==0 && $i>0) echo'</tr><tr>';?>
                    <td>
                        <div class="article_box" style="float:none;margin:0 auto 22px;">
                            <div style="background-image:url(<?=$group['image']?>)" class="article_box_pic"><a href="<?=$group['url']?>" style="text-decoration: none;">
                                <img src="/images/_zero.gif" width="<?=$size[0]?>" height="<?=$size[1]?>" />
                                </a></div>
                            <div class="article_text">
                                <a href="<?=$group['url']?>"><?=$group['title']?></a>
                            </div>
                        </div>
                    </td>
                    <?$i++; endforeach; ?>
                    <?$i = $i%4; if($i%4>0);while($i-->=0) echo $i==0?'<td></td>':'<td></td>';?>
            </tbody></table>    
    <div class="clear-both">&nbsp;</div>
</div>