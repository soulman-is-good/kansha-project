<?
$items = X3::db()->query($models);
if(!is_resource($items)) die('DB ERROR!');
$c = mysql_num_rows($items);
$i = 0;
$groups = array();
if((int)$fs['summ']==0) $fs['summ']=1;
while($item = mysql_fetch_object($items)):
    if(!isset($groups[$item->group_id]))
        $groups[$item->group_id] = X3::db()->fetch("SELECT usename FROM shop_group WHERE id=".$item->group_id);
    $model = $groups[$item->group_id];
    $image = false;
    if(strpos($item->image,'http://')===0){
        $image = $item->image;
        $size = getimagesize($image);
    }elseif(is_file('uploads/Shop_Item/'.$item->image)){
        $image = 'uploads/Shop_Item/125x125xf/'.$item->image;
        if(is_file($image))
            $size = getimagesize($image);
        else
            $size = array(122,122);
        $image = "/$image";
    }  
    $stat = X3::db()->fetch("SELECT MAX(price) as `max`, MIN(price) as `min`, COUNT(0) as `cnt` FROM company_item WHERE item_id=$item->id AND price>0");
    $fs = X3::db()->fetch("SELECT SUM(`rank`) `summ` FROM shop_feedback WHERE item_id IN (SELECT id FROM shop_item WHERE group_id={$item->group_id})");
    $fc = X3::db()->fetch("SELECT COUNT(0) `cnt`, SUM(`rank`) `summ` FROM shop_feedback WHERE item_id=$item->id");
    $sc = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_service WHERE groups REGEXP '(,|\[)$item->group_id(,|\])'");
    $url = '/'.strtolower(X3_String::create($item->title)->translit()).($item->articule!=''?"-($item->articule)-":'-').$item->id;
    $fs['summ'] = $fs['summ']==0?1:$fs['summ'];
    $rank = floor(5*$fc['summ']/$fs['summ']);
    $_s = 5-$rank;    
?>
<div class="expand_box">
    <div class="expand_box_pic">
        <div style="background-image:url(<?=$image?>);width:<?=$size[0]?>px;height:<?=$size[1]?>px;" class="expand_box_picture">&nbsp;</div>
        <div class="expand_box_pic_pic">
            <div style="float:left;padding:5px 5px 0 0"><input type="checkbox" value="<?=$item->id?>" name="compare[]"></div><a href="#" onclick="$(this).siblings('div').children('input').click();return false;"><span>Добавить к сравнению</span></a><br>
        </div>
    </div>

    <div class="expand_box_text">
        <h2 class="expand_header"><a itemprop="name" href="<?=$url?>.html"><?=($model['usename']?$item->title.($item->articule!=''?" ($item->articule)":''):$item->suffix)?></a></h2>
        <p><?=$item->properties_text?></p>
        <?if($stat['cnt']>0):?>
        <a class="blue" href="<?=$url?>/prices.html"><span>Цены</span><sup><?=$stat['cnt']?></sup></a>
        <?endif;?>
        <a class="blue" href="<?=$url?>.html"><span>Характеристики</span></a>
        <a class="blue" href="<?=$url?>/feedback.html"><span>Отзывы</span><sup><?=(int)$fc['cnt']?></sup></a>
        <a class="blue" href="<?=$url?>/services.html"><span>Услуги</span><sup><?=$sc['cnt']?></sup></a>
        <br>
        <?if($stat['cnt']>0):?>
        <?if($stat['min']>0 && $stat['min']<$stat['max']):?>
        <span itemprop="price" class="red_cost"><?=X3_String::create($stat['min'])->currency()?><i class="money">&nbsp;</i><i class="dash">-</i></span>
        <?endif;?>
        <span class="red_cost"><?=X3_String::create($stat['max'])->currency()?><i class="money">&nbsp;</i></span><br>
        <?endif;?>
                <div class="star_compar">
                    <div class="star_links">
                        <meta itemprop="ratingValue" content="<?=$rank?>" />
                        <?for($j=0;$j<$rank;$j++):?>
                        <a class="stars" href="#"><img height="16" width="16" src="/images/ostar.png"></a>
                        <?endfor;?>
                        <?for($j=0;$j<$_s;$j++):?>
                        <a class="stars" href="#"><img height="16" width="16" src="/images/gstar.png"></a>
                        <?endfor;?>
                        <a class="comment" href="/<?=$url?>/feedback.html"><?=(int)$fc['cnt']?> <?=X3_String::create("Отзыва")->numeral($fc['cnt'],array("Отзыв","Отзыва","Отзывов"))?></a>
                    </div>
                    <?if($stat['cnt']>0):?>
                    <a class="product_info_price" href="<?=$url?>/prices.html">Сравнить цены</a>
                    <span class="black_text">В <?=$stat['cnt']?> <?=X3_String::create("магазинах")->numeral($stat['cnt'],array("магазине","магазинах","магазинах"))?></span>
                    <?else:?>
                    <a class="product_info_price" href="<?=$url?>.html">Подробнее</a>
                    <?endif;?>
                </div>
    </div>
</div><!--expand_box-->
<? endwhile; ?>