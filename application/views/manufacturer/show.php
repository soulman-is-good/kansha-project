<style>
    .product_right .mgr_product {
        float: left;
        margin-left: 10px;
        position: relative;
        margin-bottom:10px;
    }
</style>
<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:47px">
                    <h2>Каталог товаров <span><?=$model->title?></span><sup>356</sup></h2>
                </td>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    
    <?= $groups?>

    <div class="product_right">
<?php
$items = X3::db()->query("SELECT DISTINCT si.*, MIN(ci.price) price FROM shop_item si INNER JOIN company_item ci ON ci.item_id=si.id INNER JOIN manufacturer m ON m.id=si.manufacturer_id WHERE si.status AND m.id=$model->id GROUP BY ci.item_id ORDER BY RAND() LIMIT 5");
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
                    <a class="product_info_price" href="<?=$mdl->getLink()?>.html/prices.html">Сравнить цены</a>
                </div>
            </div>
            <?$i++;endwhile;?>
        </div>        
<?endif;?>
        <?if(!empty($model->link)):?>
        <a class="brand_pic" href="<?=$model->link?>"><?=$image?></a>
        <?else:?>
        <?=$image?>
        <?endif;?>
        <p class="brand_text">
            <?=$model->text?>
        </p>



    <?=  X3_Widget::run('@layouts:widgets:news.php',array(),array('cache'=>true));?>



        

    </div><!--product_right-->
    <?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:43px;margin-bottom:20px'));?>
</div>