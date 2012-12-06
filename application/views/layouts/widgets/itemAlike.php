<? X3::profile()->start('itemAlike');?>
<?php
$low = $price['min']-$price['min']*0.1;
if($low<=0) $low = 1;
$high = $price['max']+$price['max']*0.1;
$items = X3::db()->query("
        SELECT si.id, si.group_id, si.articule, si.title, MIN(ci.price) price, si.image, si.properties_text FROM
        shop_item si
        INNER JOIN company_item ci ON ci.item_id=si.id
        WHERE si.status AND ci.price>0 AND si.group_id='$group_id' AND si.id<>'$not' AND ci.price>='$low' AND ci.price<='$high'
        GROUP BY ci.item_id
        ORDER BY RAND()
        LIMIT 3
    ");
if(is_resource($items) && ($c=mysql_num_rows($items))>0):
?>
<div style="margin-top:-25px" class="tender">
    <table class="tender_stripe one">
        <tbody><tr>
                <td style="width:155px">
                    <h2>Похожие товары</h2>
                </td>
                <td>
                    <div class="tender_long_stripe">
                        <div class="tender_left">&nbsp;</div>
                        <div class="tender_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
<?while($item = mysql_fetch_assoc($items)):
    $imodel = new Shop_Item;
    $imodel->getTable()->acquire($item);
    $imodel->id = $item['id'];
    $imodel->setIsNewRecord(false);
    //if(!isset($groupTitle) || $groupTitle == null)
        //$groupTitle = $imodel->realGroupTitle();
?>
    <div class="tender_box">
        <div style="background-image:url(/uploads/Shop_Item/55x65xf/<?=$imodel->image?>)" class="tender_box_picture one"></div>
        <div class="tender_text one">
            <a href="<?=$imodel->getLink()?>.html"><?=$imodel->title?></a>
            <span class="tender_red"><?=X3_String::create($item['price'])->currency()?> <i>&nbsp;</i></span>
            <span class="tender_letter"><?=$imodel->properties_text?></span>
        </div>
        <div class="clear-both">&nbsp;</div>
    </div>
<?endwhile?>    
    <!--table style="margin-top:15px;margin-bottom:27px">
        <tbody><tr>
                <td>
                    <a style="margin:0px" class="compare_type" href="#"><span>Сравнить модели</span></a>
                </td>
                <td style="vertical-align:middle;padding-left:9px;padding-right:9px">
                    <span class="s">c</span>
                </td>
                <td style="vertical-align:middle;width:131px">
                    <a class="s_link" href="#">Samsung i9100 Galaxy S II (16Gb)</a>
                </td>
            </tr>
        </tbody></table-->

</div>
<?

endif;?>
<? X3::profile()->end('itemAlike');?>