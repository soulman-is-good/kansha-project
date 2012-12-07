<?php
    //if($model->parent_id>0)
        //$parents = X3::db()->query("SELECT id, short_title, name FROM data_page WHERE status");// AND parent_id=$model->parent_id");
    //else
        $parents = X3::db()->query("SELECT id, short_title, name FROM data_page WHERE status AND type='$this->type'");// AND parent_id=$model->id");
?>
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
        <tr>
            <td style="padding-right:10px;white-space:nowrap;height:47px">
                <header>
                <h1><?=$model->title?></h1>
                </header>
            </td>
            <td style="width:100%">
                <div class="black_stripe">
                    <div class="left_stripe_black">&nbsp;</div>
                    <div class="right_stripe_black">&nbsp;</div>
                </div>
            </td>
        </tr>
    </table>
    <?if(mysql_num_rows($parents)>0):?>
    <div class="product_left_side" style="margin-top:-12px">
        <ul class="text_list">
            <?while($p = mysql_fetch_assoc($parents)):?>
                <?if($p['id'] == $model->id):?>
                <li><span style="line-height: 2"><?=$p['short_title']?></span></li>
                <?else:?>
                <li><a href="/page/<?=$p['name']?>.html"><?=$p['short_title']?></a></li>
                <?endif;?>
            <?endwhile;?>
        </ul>
    </div><!--product_left_side-->
    <div class="product_right list_text">
    <?else:echo'<div class="product_right list_text" style="margin-top:-24px;margin-left:25px">'; endif;?>
<?if($model->type=='Для товаров' && $model->group_id>0):?>
<?php
$items = X3::db()->query("SELECT DISTINCT si.*, MIN(ci.price) price FROM shop_item si INNER JOIN company_item ci ON ci.item_id=si.id INNER JOIN shop_group sg ON sg.id=si.group_id WHERE si.status AND sg.id=$model->group_id GROUP BY ci.item_id ORDER BY RAND() LIMIT 5");
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
<?endif;?>
<?=$model->text?>
    </div><!--product_right-->
<?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:43px;margin-bottom:20px'));?>
</div>
