<?X3::profile()->start('menuItems.widget');?>

<?php
$cats = array();
$secs = Shop_Category::get(array('@condition'=>array('status'),'@order'=>'weight, title'));
$grps = Shop_Group::get(array('@condition'=>array('status'),'@order'=>'weight, title'));
$scount = (int)$secs->count()/3;
foreach($secs as $s=>$sec){
    $cats[$sec->id]['model'] = $secs[$s];
    $cats[$sec->id]['summ'] = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_item ci INNER JOIN shop_item si ON si.id=ci.item_id 
        INNER JOIN shop_group sg ON sg.id=si.group_id WHERE sg.status AND si.status AND sg.category_id REGEXP '$sec->id,|$sec->id$'");
    foreach($grps as $i=>$g){
        $c = explode(',',$g->category_id);
        if(in_array($sec->id,$c)){
            $pps = X3::db()->fetchAll("SELECT id, group_id, title, value, property_id FROM shop_proplist WHERE group_id=$g->id AND property_id IN (SELECT id FROM shop_properties WHERE isgroup AND group_id=$g->id) ORDER BY weight, title");
            if(!empty($pps)){
                foreach ($pps as $j=>$ps)
                    $cats[$sec->id]['models'][]=$pps[$j];
            }else
                $cats[$sec->id]['models'][]=$grps[$i];
        }
    }
}
?>
							<table>
								<tr>
									<td style="width:33%">
                                                                            <?$i=0;foreach ($cats as $cat): 
                                                                                $url = "/".strtolower(X3_String::create($cat['model']->title)->translit(false,"'"))."-c".$cat['model']->id;
                                                                                if(!isset($cat['models'])) continue;
                                                                                ?>
										<div class="sub_menu_list">
											<div class="name"><img src="/uploads/Shop_Category/<?=$cat['model']->image?>" style="position:relative;left:-5px"/><h3><a href="<?=$url?>.html"><?=$cat['model']->title?></a><sup><?=$cat['summ']['cnt']?></sup></h3></div>
											<div class="link_list">
                                                                                                <? foreach ($cat['models'] as $mod): 
                                                                                                if(isset($mod['value'])):?>
												<a href="/<?=strtolower(X3_String::create($mod['title'])->translit(false,"'"))?>-group<?=$mod['group_id']?>-p<?=$mod['id']?>.html"><?=$mod['title']?></a>
                                                                                                <?else:?>
												<a href="/<?=strtolower(X3_String::create($mod->title)->translit(false,"'"))?>-group<?=$mod->id?>.html"><?=$mod->title?></a>
                                                                                                <?endif;?>
                                                                                                <? endforeach; ?>
											</div>
										</div>
                                                                            <?if($i%$scount == 0 && $i>0) echo '</td><td>';
                                                                            $i++;endforeach; ?>
								</tr>
							</table>
<?X3::profile()->end('menuItems.widget');?>
