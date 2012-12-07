<?php
if($type == 0){
        $models = array();
        $secs = Shop_Category::get(array('@condition' => array('status'), '@order' => 'weight, title'));
        foreach ($secs as $s => $sec) {
            $models[$sec->id]['model'] = $secs[$s];
            $grps = X3::db()->query("SELECT * FROM shop_group WHERE status AND category_id REGEXP '^$sec->id,|,$sec->id,|,$sec->id$|^$sec->id$' ORDER BY `weight`, `title`");
            while ($g = mysql_fetch_object($grps)) {
                $c = explode(',', $g->category_id);
                if (in_array($sec->id, $c)) {
                    $pps = X3::db()->query("SELECT id, group_id, title, value, property_id FROM shop_proplist WHERE group_id=$g->id AND property_id IN (SELECT id FROM shop_properties WHERE isgroup AND group_id=$g->id) ORDER BY weight, title");
                    if (is_resource($pps) && mysql_num_rows($pps)>0) {
                        while($ps=mysql_fetch_assoc($pps)){
                            $count = Manufacturer::countByGroup($ps['id']);
                            if($count>0)
                            $models[$sec->id]['models'][] = array(
                                'count'=>$count,
                                'title'=>$ps['title'],
                                'url'=>'/manufacturer/'.strtolower(X3_String::create($ps['title'])->translit(false,"'")).'-group'.$ps['group_id'].'-p'.$ps['id']
                            );//$pps[$j];
                        }
                    }else{
                        $count = X3::db()->count("SELECT `m`.`id` FROM manufacturer m INNER JOIN shop_item si ON si.manufacturer_id=m.id INNER JOIN company_item ci ON ci.item_id=si.id WHERE m.status AND price>0 AND si.group_id=$g->id GROUP BY m.id");
                        if($count>0)
                        $models[$sec->id]['models'][] = array(
                            'count'=>$count,
                            'title'=>$g->title,
                            'url'=>'/manufacturer/'.strtolower(X3_String::create($g->title)->translit(false,"'")).'-group'.$g->id
                        );//$grps[$i];
                    }
                }
            }
        }
    
}else{
        $models = array();
        $secs = Shop_Category::get(array('@condition' => array('status'), '@order' => 'weight, title'));
        foreach ($secs as $s => $sec) {
            $models[$sec->id]['model'] = $secs[$s];
            $grps = X3::db()->query("SELECT DISTINCT sg.* FROM shop_group sg INNER JOIN shop_item si ON si.group_id=sg.id WHERE sg.status AND si.manufacturer_id=$model->id AND category_id REGEXP '^$sec->id,|,$sec->id,|,$sec->id$|^$sec->id$' ORDER BY sg.`weight`, sg.`title`");
            while ($g = mysql_fetch_object($grps)) {
                $c = explode(',', $g->category_id);
                if (in_array($sec->id, $c)) {
                    $pps = X3::db()->query("SELECT id, group_id, title, value, property_id FROM shop_proplist WHERE group_id=$g->id AND property_id IN (SELECT id FROM shop_properties WHERE isgroup AND group_id=$g->id) ORDER BY weight, title");
                    if (is_resource($pps) && mysql_num_rows($pps)>0) {
                        while($ps=mysql_fetch_assoc($pps)){
                            $count = Manufacturer::countByGroup($ps['id'],'PriceAndManufacturer',$model->id);
                            if($count>0)
                            $models[$sec->id]['models'][] = array(
                                'count'=>$count,
                                'title'=>$ps['title'] . " $model->title",
                                'url'=>'/'.strtolower(X3_String::create($ps['title'])->translit(false,"'")).'-group'.$ps['group_id'].'-p'.$ps['id'].'/'.$model->name
                            );//$pps[$j];
                        }
                    }else{
                        $count = X3::db()->count("SELECT `m`.`id` FROM manufacturer m INNER JOIN shop_item si ON si.manufacturer_id=m.id INNER JOIN company_item ci ON ci.item_id=si.id WHERE m.status AND price>0 AND si.group_id=$g->id AND m.id=$model->id");
                        $models[$sec->id]['models'][] = array(
                            'count'=>$count,
                            'title'=>$g->title . " $model->title",
                            'url'=>'/'.strtolower(X3_String::create($g->title)->translit(false,"'")).'-group'.$g->id.'/'.$model->name
                        );//$grps[$i];
                    }
                }
            }
        }    
}
?>
<div style="margin-top:-22px" class="product_left_side">
        <ul class="green">
            <?$i=0; foreach ($models as $model): if(empty($model['models']))continue;?>
            <li<?=($i==0 || $type==1?' class="active"':'')?>><a class="manuf" href="#"><?=$model['model']->title?></a>
                <ul class="blue">
                    <? foreach ($model['models'] as $gr): ?>
                    <li><a href="<?=$gr['url']?>.html"><?=$gr['title']?></a><sup><?=$gr['count']?></sup></li>
                    <? endforeach; ?>
                </ul>
            </li>
            <? $i++;endforeach; ?>
        </ul>
    </div><!--product_left_side-->
<script type="text/javascript">
    $(function(){
        $('.manuf').each(function(){
            $(this).click(function(){
                if($(this).parent().hasClass('active')) return false;
                
                var X = $(this).parent().parent().children('.active')
                X.children('.blue').slideUp(function(){
                    X.removeClass('active');
                })
                var Y = $(this).parent()
                Y.children('.blue').slideDown(function(){Y.addClass('active')});
                return false;
            });
        })
    })
</script>