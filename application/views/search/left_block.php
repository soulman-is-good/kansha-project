<?php
if(!empty($models)):
$models['@join'] = "INNER JOIN shop_item ON shop_item.group_id=shop_group.id";
$models['@group'] = "shop_group.id";
$models['@select'] = "shop_group.*, COUNT(shop_item.id) `cnt`";
$q = Shop_Group::getInstance()->table->formQuery($models)->buildSQL();
$q = X3::db()->query($q);
$groups = array();
if(is_resource($q)){
    while($g = mysql_fetch_assoc($q)){
        $cats = X3::db()->fetchAll("SELECT * FROM shop_category WHERE id IN ({$g['category_id']})");
        if(!empty($cats))
        foreach($cats as $cat){
            if(!isset($groups[$cat['id']]))
                $groups[$cat['id']] = array('title'=>$cat['title'],'url'=>"/".strtolower(X3_String::create($cat['title'])->translit(false,"'"))."-c".$cat['id'].".html",'grs'=>array());
            $groups[$cat['id']]['grs'][$g['id']]=$g;
        }
    }
}

?>
<div style="margin-top:-22px" class="product_left_side">
    <ul class="green">
        <?if(X3::user()->search_group>0):?>
        <li><a href="<?=X3::app()->request->parseUrl(array('group'=>0))?>">Все результаты</a></li>
        <?endif;?>
        <? foreach ($groups as $cat): ?>
        <li class="active"><a href="#" onclick="$(this).parent().toggleClass('active');return false;"><?=$cat['title']?></a>
            <ul class="blue">
                <? foreach ($cat['grs'] as $group): ?>
                <?if(X3::user()->search_group == $group['id']):?>
                <li><b><?=$group['title']?></b><sup><?=$group['cnt']?></sup></li>            
                <?else:?>
                <li><a href="<?=X3::app()->request->parseUrl(array('group'=>$group['id']))?>"><?=$group['title']?></a><sup><?=$group['cnt']?></sup></li>            
                <?endif;?>
                <? endforeach; ?>
            </ul>
        </li>
        <? endforeach; ?>
    </ul>
</div><!--product_left_side-->
<?endif;?>