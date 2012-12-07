<?X3::profile()->start('filterItems.widget');?>
<?php
//$props = Shop_Properties::get(array('@condition'=>array('group_id' => $model->id,'status','showinfilters')));
$properties = array();
$diapazon = array();
$sessProp = X3::user()->{"prop_$model->id"};
$greatQuery = array();
if(isset($sessProp['string'])){
    foreach($sessProp['string'] as $key=>$val){
        if(is_array($val))
            $greatQuery[$key] = array('IN'=>'('.implode(',',$val).')');
        elseif($val>0)
            $greatQuery[$key] = $val;
    }
}
if(isset($sessProp['scalar'])){
    foreach($sessProp['scalar'] as $key=>$val){
        if(is_array($val))
            $greatQuery[$key] = array('@@'=>"(`$key` BETWEEN {$val['from']} AND {$val['till']} OR `key` IS NULL)");
        elseif($val>0)
            $greatQuery[$key] = $val;
    }
}
if(isset($sessProp['boolean'])){
    foreach($sessProp['boolean'] as $key=>$val){
        $greatQuery[$key] = 1;
    }
}
$key = 0;
$grps = json_decode($model->filters,1);
if($grps!=null && !empty($grps)):
foreach($grps as $gr){
    if($gr['additional'] == 1)
        $p1 = 'add';
    else
        $p1 = 'prs';
    $ids = implode(',',$gr['ids']);
    $h = X3::db()->query("SELECT * FROM shop_properties WHERE group_id=$model->id AND status AND id IN ($ids)");
    $props = array();
    while($hb = mysql_fetch_object($h)){
        $props[$hb->id] = $hb;
    }
    foreach($gr['ids'] as $gs){
        $properties[$p1][$key]['model'] = (object)$props[$gs];
        $properties[$p1][$key]['model']->title = $gr['title'];
        $properties[$p1][$key]['model']->collapse = $gr['collapse'];
        if($props[$gs]->type!='string'){
            $tmp = $greatQuery;
            if($props[$gs]->type == 'boolean' && !isset($greatQuery[$props[$gs]->name])){
                $tmp[$props[$gs]->name] = '1';
                $sql = new X3_MySQL_Query('prop_'.$model->id);
                $sql = $sql->formCondition($tmp);
                if(X3::db()->count("SELECT ci.id FROM company_item ci INNER JOIN prop_$model->id p ON p.id=ci.item_id 
                WHERE ci.price>0 AND $sql")>0)
                    $properties[$p1][$key]['models'][] = $props[$gs];
            }else
                $properties[$p1][$key]['models'][] = $props[$gs];
            if($props[$gs]->type == 'integer' || $props[$gs]->type == 'decimal')
                $diapazon[$props[$gs]->name] = X3::db()->fetch("SELECT FLOOR(MIN({$props[$gs]->name})) `min`, CEILING(MAX({$props[$gs]->name})) `max` FROM prop_{$props[$gs]->group_id}");
        }else{
            $proplist = X3::db()->query("SELECT DISTINCT spl.* FROM `shop_proplist` spl INNER JOIN shop_item si ON si.group_id = spl.group_id INNER JOIN company_item ci ON ci.item_id = si.id WHERE ci.price>0 AND spl.group_id=$model->id AND spl.status AND spl.property_id='$gs'");
            if(is_resource($proplist))
            while($pl = mysql_fetch_assoc($proplist)){
                if ($gs == $pl['property_id']) {
                    $tmp = $greatQuery;
                    if(!isset($greatQuery[$pname['name']])){
                        $pname = X3::db()->fetch("SELECT name,type FROM shop_properties WHERE id={$pl['property_id']}");
                            $tmp[$pname['name']] = $pl['id'];
                            $sql = new X3_MySQL_Query('prop_'.$model->id);
                            $sql = $sql->formCondition($tmp);
                            if(X3::db()->count("SELECT ci.id FROM company_item ci INNER JOIN prop_$model->id p ON p.id=ci.item_id 
                            WHERE ci.price>0 AND $sql")>0)
                                $properties[$p1][$key]['models'][] = (object)$pl;
                    }else
                        $properties[$p1][$key]['models'][] = (object)$pl;
                }
            }
            //$proplist = Shop_Proplist::get(array('@condition'=>array('group_id' => $model->id,'status','property_id'=>$gs),'@order'=>'weight, title'));
            //foreach ($proplist as $j => $pl) {
                //if ($gs == $pl->property_id) {
                    //$properties[$p1][$key]['models'][] = $proplist[$j];
                //}
            //}
        }
    }
    $key++;
}
/*foreach ($props as $i => $p) {
    if($p->isadditional==1)
        $p1 = 'add';
    else
        $p1 = 'prs';
    if($p->type == 'boolean'){
        if(!empty($grps)){
            foreach($grps as $j=>$gs){
                if(trim($gs['title']) == '') continue;
                $tmpmdls = array('models'=>array());
                foreach ($props as $k => $pr){
                    if($pr->type == 'boolean' && in_array($pr->id,$gs['ids'])){
                        $tmpmdls['models'][] = $props[$k];
                    }
                    $tmpmdls['model'] = (object)array('label'=>$gs['title'],'type'=>'boolean');
                }
                if(!empty($tmpmdls['models'])){
                    $properties[$p1][$key] = $tmpmdls;
                    $key++;
                    unset($tmpmdls);
                }
            }
        }else{
            $properties[$p1][$key]['models'][0] = $props[$i];
            $properties[$p1][$key]['model'] = $props[$i];
        }
    }else
        $properties[$p1][$key]['model'] = $props[$i];
    if($p->type == 'string')
        foreach ($proplist as $j => $pl) {
            if ($p->id == $pl->property_id) {
                $properties[$p1][$key]['models'][] = $proplist[$j];
                //unset($proplist[$j]);
            }
        }
    if($p->type == 'integer' || $p->type == 'float'){
        $properties[$p1][$key]['models'][0] = $props[$i];
        if($p->multifilter){
            $diapazon[$p->name] = X3::db()->fetch("SELECT MIN($p->name) `min`, MAX($p->name) `max` FROM prop_$p->group_id");
        }
    }
    if(!isset($properties[$p1][$key]['models']))
        unset($properties[$p1][$key]);
    $key++;
}*/
foreach ($properties['prs'] as $p):
    if(($p['model']->type == 'boolean' || ($p['model']->type == 'string' && $p['model']->multifilter)) && empty($p['models']) ) continue;
    ?>
    <div class="product_inside_upper">
    <?if(($p['model']->type == 'boolean' && count($p['models'])>1) || $p['model']->type != 'boolean'):?>
        <h4 <?=$p['model']->collapse?'class="additional" ':''?><?=(($p['model']->type == 'string' && ($p['model']->multifilter || $p['model']->collapse)) || $p['model']->type == 'boolean')?'style="margin-bottom:12px;margin-top:14px"':''?>><span><?= $p['model']->title ?>:</span></h4>
    <?endif;?>
        <div <?=$p['model']->collapse?'style="display:none"':''?>>
        <?if($p['model']->type=='string'):?>
        <?if($p['model']->multifilter):?>
            <table><tr><td class="left_side_next">
        <?foreach ($p['models'] as $k=>$pp): ?>
                <input name="Filter[string][<?=$p['model']->name?>][]" type="checkbox" value="<?=$pp->id?>"<?=(isset($sessProp['string'],$sessProp['string'][$p['model']->name]) && is_array($sessProp['string'][$p['model']->name]) && in_array($pp->id,$sessProp['string'][$p['model']->name]))?' checked':''?> /><span><?=$pp->title!=''?$pp->title:$pp->value?></span><br/>
        <?endforeach;?>
            </td></tr></table>
        <?else:?>
        <select name="Filter[string][<?=$p['model']->name?>]">
                <option value="0"> Не выбрано </option>
            <?foreach ($p['models'] as $k=>$pp): ?>
                <option value="<?=$pp->id?>"<?=(isset($sessProp['string'],$sessProp['string'][$p['model']->name]) && $pp->id==$sessProp['string'][$p['model']->name])?' selected':''?>> <?=$pp->title!=''?$pp->title:$pp->value?> </option>
            <?endforeach;?>
        </select>
        <?endif;?>
        <?elseif($p['model']->type=='integer' || $p['model']->type=='decimal'):?>
            <?foreach ($p['models'] as $k=>$pp): ?>
            <?if($pp->multifilter):?>
                от <input name="Filter[scalar][<?=$pp->name?>][from]" value="<?=isset($sessProp['scalar'])?$sessProp['scalar'][$pp->name]['from']:$diapazon[$pp->name]['min']?>" type="text" style="width:70px" /> 
                до <input name="Filter[scalar][<?=$pp->name?>][till]" value="<?=isset($sessProp['scalar'])?$sessProp['scalar'][$pp->name]['till']:$diapazon[$pp->name]['max']?>" type="text" style="width:70px" />
            <?else:?>
                <input name="Filter[scalar][<?=$pp->name?>]" value="<?=isset($sessProp['scalar'])?$sessProp['scalar'][$pp->name]:''?>" type="text" />
            <?endif;?>
            <?endforeach;?>
        <?elseif($p['model']->type=='boolean'):?>
        <table>
            <tbody><tr>
                    <td class="left_side_next">
                        <?if(count($p['models'])==1):?>
                        <div style="height:17px" class="pustoi">&nbsp;</div>
                        <?endif;?>
            <?foreach ($p['models'] as $k=>$pp): ?>
                        <input name="Filter[boolean][<?=$pp->name?>]" type="checkbox"<?=(isset($sessProp['boolean'],$sessProp['boolean'][$pp->name]) && $pp->id=$sessProp['boolean'][$pp->name])?' checked':''?> value="<?=$pp->id?>" ><span><?=$pp->label?></span><br>
                        <div style="height:7px" class="pustoi">&nbsp;</div>
        <? endforeach; ?>
                    </td>
                </tr>
            </tbody></table>        
        <?endif;?>
        </div>
    </div>
<? endforeach; ?>
<?if(!empty($properties['add'])):?>
    <div class="product_inside_upper">
    <div class="on_the_side">
        <a onclick="$('.slide_it').slideToggle();return false;" class="extra" href="#">Дополнительные параметры</a>
        <a onclick="$('.slide_it').slideUp();return false;" class="up" href="#">↑</a>
        <a onclick="$('.slide_it').slideDown();return false;" class="down" href="#">↓</a>
    </div>
    <div style="display:none" class="slide_it">
<?$c = count($properties['add']);$opa = 0;foreach ($properties['add'] as $p):
    if(($p['model']->type == 'boolean' || ($p['model']->type == 'string' && $p['model']->multifilter)) && empty($p['models']) ) continue;?>
    <div class="product_inside_upper" <?=($opa==$c-1)?' style="border:none"':''?>>
    <?$opa++;if(($p['model']->type == 'boolean' && count($p['models'])>1) || $p['model']->type != 'boolean'):?>
        <h4 <?=$p['model']->collapse?'class="additional" ':''?><?=(($p['model']->type == 'string' && ($p['model']->multifilter || $p['model']->collapse)) || $p['model']->type == 'boolean')?'style="margin-bottom:12px;margin-top:14px"':''?>><span><?= $p['model']->title ?>:</span></h4>
    <?endif;?>
        <div <?=$p['model']->collapse?'style="display:none"':''?>>
        <?if($p['model']->type=='string'):?>
        <?if($p['model']->multifilter):?>
            <table><tr><td class="left_side_next">
        <?foreach ($p['models'] as $k=>$pp): ?>
                <input name="Filter[string][<?=$p['model']->name?>][]" type="checkbox" value="<?=$pp->id?>"<?=(isset($sessProp['string'],$sessProp['string'][$p['model']->name]) && is_array($sessProp['string'][$p['model']->name]) && in_array($pp->id,$sessProp['string'][$p['model']->name]))?' checked':''?> /><span><?=$pp->value?></span><br/>
        <?endforeach;?>
            </td></tr></table>
        <?else:?>
        <select name="Filter[string][<?=$p['model']->name?>]">
                <option value="0"> Не выбрано </option>
            <?foreach ($p['models'] as $k=>$pp): ?>
                <option value="<?=$pp->id?>"<?=(isset($sessProp['string'],$sessProp['string'][$p['model']->name]) && $pp->id==$sessProp['string'][$p['model']->name])?' selected':''?>> <?=$pp->value?> </option>
            <?endforeach;?>
        </select>
        <?endif;?>
        <?elseif($p['model']->type=='integer' || $p['model']->type=='decimal'):?>
            <?foreach ($p['models'] as $k=>$pp): ?>
            <?if($pp->multifilter):?>
                от <input name="Filter[scalar][<?=$pp->name?>][from]" value="<?=isset($sessProp['scalar'])?$sessProp['scalar'][$pp->name]['from']:$diapazon[$pp->name]['min']?>" type="text" style="width:60px" /> 
                до <input name="Filter[scalar][<?=$pp->name?>][till]" value="<?=isset($sessProp['scalar'])?$sessProp['scalar'][$pp->name]['till']:$diapazon[$pp->name]['max']?>" type="text" style="width:60px" />
            <?else:?>
                <input name="Filter[scalar][<?=$pp->name?>]" value="<?=isset($sessProp['scalar'])?$sessProp['scalar'][$pp->name]:''?>" type="text" />
            <?endif;?>
            <?endforeach;?>
        <?elseif($p['model']->type=='boolean'):?>
        <table>
            <tbody><tr>
                    <td class="left_side_next">
                        <?if(count($p['models'])==1):?>
                        <div style="height:17px" class="pustoi">&nbsp;</div>
                        <?endif;?>                        
            <?foreach ($p['models'] as $k=>$pp): ?>
                        <input name="Filter[boolean][<?=$pp->name?>]" type="checkbox"<?=(isset($sessProp['boolean'],$sessProp['boolean'][$pp->name]) && $pp->id=$sessProp['boolean'][$pp->name])?' checked':''?> value="<?=$pp->id?>" ><span><?=$pp->label?></span><br>
                        <div style="height:7px" class="pustoi">&nbsp;</div>
        <? endforeach; ?>
                    </td>
                </tr>
            </tbody></table>        
        <?endif;?>
        </div>
    </div>
<? endforeach; ?>
    </div>
    </div>
<?endif;?>
<script type="text/javascript">
    $('h4.additional').click(function(){
        $(this).toggleClass('expanded').siblings('div').slideToggle();
        return false;
    })
</script>
<?endif;?>
<?X3::profile()->end('filterItems.widget');?>
