<?X3::profile()->start('popular_sections.widget');?>

<?php
$q = X3::db()->query("SELECT shop_group.id as id,shop_group.title as title,(SELECT COUNT(0) FROM shop_stat WHERE item_id=shop_item.id) AS k 
    FROM shop_group 
    INNER JOIN shop_item ON shop_item.group_id = shop_group.id 
    WHERE shop_group.status GROUP BY shop_group.id ORDER BY k DESC, shop_group.weight, id DESC LIMIT 9");
$l = 10;
if(is_resource($q) && mysql_num_rows($q)>0):
    $models = array();
    while(FALSE!==($m = mysql_fetch_assoc($q)) && $l>0){
        $pps = X3::db()->fetchAll("SELECT sp.id id, sp.group_id group_id, sp.title title, sp.value, sp.property_id 
            FROM shop_proplist sp WHERE sp.group_id='{$m['id']}' AND sp.property_id IN (SELECT id FROM shop_properties WHERE isgroup AND group_id='{$m['id']}') 
                ORDER BY weight, title");
        if(count($pps)>0){
            foreach($pps as $mod){
                $models[] = array(Shop_Group::getLink($mod['group_id'],$mod['id'],false,$mod['title']).".html",$mod['title']);
                $l--;
                if($l==0) break;
            }
        }else
            $models[] = array(Shop_Group::getLink($m['id'],false,$m['title']).".html",$m['title']);;
        $l--;
    }
?>
<table class="popular">
				<tr>
					<td>
						<h2><?=X3::translate('Популярные')?></h2>
					</td>
					<td style="width:100%">
						<div class="popular_blue_stripe">
                                                    <div class="popular_left_s">&nbsp;</div>
                                                    <div class="popular_right_s">&nbsp;</div>
						</div>
						
					</td>
				</tr>
			</table>
			<div class="clear-both">&nbsp;</div>
			<ul>
                            <? foreach ($models as $model): ?>
                            <li><a href="<?=$model[0]?>"><?=$model[1]?></a></li>
                            <? endforeach; ?>
				<li><a href="/vse-razdely.html" class="all_last"><?=X3::translate('Все разделы')?></a></li>
				
				
			</ul>
<?endif;?>
<?X3::profile()->end('popular_sections.widget');?>
