<?php
/*$models = X3::db()->query("SELECT shop_group.id as id,shop_group.title as title,(SELECT COUNT(0) FROM shop_stat WHERE item_id=shop_item.id) AS k 
    FROM shop_group 
    INNER JOIN shop_item ON shop_item.group_id = shop_group.id 
    WHERE shop_group.status GROUP BY shop_group.id ORDER BY k DESC, shop_group.weight, id DESC LIMIT 18");
$groups = array();
$z = 0;
$x = 0;
while($gr = mysql_fetch_assoc($models)){
    if($x>0 && $x%6 == 0)
        $z++;
    $pps = X3::db()->fetchAll("SELECT sp.id id, sp.group_id group_id, sp.title title, sp.value, sp.property_id,(SELECT COUNT(0) FROM shop_stat WHERE item_id=shop_item.id) AS k 
        FROM shop_proplist sp INNER JOIN shop_item ON shop_item.group_id = sp.group_id WHERE sp.group_id={$gr['id']} AND sp.property_id IN (SELECT id FROM shop_properties WHERE isgroup AND group_id={$gr['id']}) 
            ORDER BY k, weight, title");
    if(count($pps)>0){
        foreach($pps as $mod)
        $groups[$z][]=array(strtolower(X3_String::create($mod['title'])->translit())."-group{$mod['group_id']}-p{$mod['id']}.html",$mod['title']);
    }
    else
        $groups[$z][]=array(strtolower(X3_String::create($gr['title'])->translit())."-group{$gr['id']}.html",$gr['title']);
    $x++;
    
}*/
$groups = array();
$z = 0;
$x = 0;
if(Page::num_rows(array('type'=>"Для товаров",'status'))>0){
    $models = Page::get(array('type'=>"Для товаров",'status'));
    foreach($models as $model){
        if($x>0 && $x%6 == 0)
            $z++;
        $groups[$z][] = array("/page/$model->name.html",$model->title);
    }
}else{
    $models = X3::db()->query("SELECT * FROM shop_category WHERE status ORDER BY weight, title");
    while($m = mysql_fetch_assoc($models)){
        if($x>0 && $x%6 == 0)
            $z++;
        $groups[$z][] = array("/".strtolower(X3_String::create($m['title'])->translit(false,"'"))."-c".$m['id'].".html",$m['title']);
        $x++;
    }
}
$servs = X3::db()->query("SELECT name,title FROM data_service WHERE status ORDER BY weight, title, id DESC LIMIT 6");
$pages = X3::db()->query("SELECT name,short_title as title FROM data_page WHERE status AND parent_id IS NULL AND type='Общие' ORDER BY weight, created_at DESC, title LIMIT 6");
?>
<div id="long_gray_stripe">
    <div class="long_gray_content">
        <table>
            <tr>
                <td>
                    <div class="variants header">
                        <h4>Товары</h4>
                        <ul>
                            <? foreach ($groups[0] as $gr): ?>
                            <li><a href="<?=$gr[0]?>"><?=$gr[1]?></a></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </td>
                <?if(!empty($groups[1])):?>
                <td>
                    <div class="variants">
                        <ul>
                            <? foreach ($groups[1] as $gr): ?>
                            <li><a href="<?=$gr[0]?>"><?=$gr[1]?></a></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </td>
                <?endif;?>
                <?if(!empty($groups[1])):?>
                <td>
                    <div class="variants">
                        <ul>
                            <? foreach ($groups[2] as $gr): ?>
                            <li><a href="<?=$gr[0]?>"><?=$gr[1]?></a></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </td>
                <?endif;?>

                <td>
                    <div class="variants_turn">
                        <div class="dbl_background">&nbsp;</div>
                        <h4>Услуги</h4>
                        <ul>
                            <?while($s = mysql_fetch_assoc($servs)):?>
                            <li><a href="/service/<?=$s['name']?>.html"><?=$s['title']?></a></li>
                            <?endwhile;?>
                        </ul>
                    </div>
                </td>
                <td>
                    <div class="variants_kansha">
                        <div class="dbl_background">&nbsp;</div>
                        <h4>Kansha.kz</h4>
                        <ul>
                            <?while($s = mysql_fetch_assoc($pages)):?>
                            <li><a href="/page/<?=$s['name']?>.html"><?=$s['title']?></a></li>
                            <?endwhile;?>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>