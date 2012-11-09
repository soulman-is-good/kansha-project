<?php
$group = $model->getGroup();
$pgroups = json_decode($group->properties,1);
if(empty($pgroups)) $pgroups = array();
array_push($pgroups, array('title'=>'Прочие','ids'=>'*'));
$last_index = count($pgroups)-1;
$pvals = array();
$props = $model->getProperties();
$plabels = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id=$model->group_id AND status ORDER BY weight");
$_pvalues = X3::db()->fetchAll("SELECT id, value, title FROM shop_proplist WHERE group_id=$model->group_id AND status ORDER BY weight");
$pvalues = array();
foreach($_pvalues as $pv){
   $pvalues[$pv['id']] = $pv['title']!=''?$pv['title']:$pv['value'];
}
unset($_pvalues);
$megaPROP = '';
$lfound = array();
foreach($plabels as $i=>$prop){
    //echo "<h3>".$prop['boris']."</h3>";
    //if($prop[''] == 'id') continue;
    if(!isset($props->_fields[$prop['name']])) continue;
    $name = $prop['name'];
    $label = $plabels[$i];//plabel($plabels,$name);
    $grfound = false;
    $last = null;
    if(strpos($prop['type'],'string')===0 || strpos($prop['type'],'content')===0){
        $val = $pvalues[$props->$name];
        if(trim($val)==''){
            $lfound[] = $label['label'];
            continue;
        }
        if($val!='-')
            $megaPROP .= $val . ', ';
        foreach($pgroups as $j=>$pg){
            if(!empty($pg['uni'])){
                $found = false;
                foreach ($pg['uni'] as $uni){
                    $p = $uni['title'];
                    if(in_array($label['id'],$uni['ids'])){
                        $lfound[]=$label['label'];
                        $found=true;
                        if(isset($pvals[$j][$p])){
                            $pvals[$j][$p]['value'][] = $val;
                        }else
                            $pvals[$j][$p] = array('value'=>array($val),'id'=>$label['id'],'sep'=>$uni['sep'],'type'=>$prop['type']);
                    }
                }
                if(!$found && is_array($pg['ids']) && in_array($label['id'],$pg['ids'])){
                    $lfound[] = $label['label'];
                    $pvals[$j][$label['label']] = array('value'=>$val,'id'=>$label['id'],'type'=>$prop['type']);
                }elseif($found)
                    break;
            }else{
                if(is_array($pg['ids']) && in_array($label['id'],$pg['ids'])){
                    $grfound = true;
                    $lfound[] = $label['label'];
                    $pvals[$j][$label['label']] = array('value'=>$val,'id'=>$label['id'],'type'=>$prop['type']);
                    break;
                }
            }
        }
        $last = array($label['label'] => array('value'=>$val,'id'=>$label['id'],'type'=>$prop['type']));
    }elseif(strpos($prop['type'],'boolean')===0){
        foreach($pgroups as $j=>$pg){
           if(!empty($pg['uni'])){
                $found = false;
                foreach ($pg['uni'] as $uni){
                    $p = $uni['title'];
                    if(in_array($label['id'],$uni['ids'])){
                        $found=true;
                        $lfound[]=$label['label'];
                        if($props->$name==1)
                            if(isset($pvals[$j][$p])){
                                $pvals[$j][$p]['value'][] = $label['label'];
                            }else
                                $pvals[$j][$p] = array('value'=>array($label['label']),'id'=>$label['id'],'sep'=>$uni['sep'],'type'=>$prop['type']);
                    }
                }
                if(!$found && is_array($pg['ids']) && in_array($label['id'],$pg['ids'])){
                    $lfound[] = $label['label'];
                    $pvals[$j][$label['label']] = array('value'=>(($props->$name)?'<img width="15" height="15" src="/images/green_cross.jpg">':'<img width="15" height="15" src="/images/cross.jpg">'),'id'=>$label['id'],'type'=>$prop['type']);
                }elseif($found)
                    break;
            }else{
                if(is_array($pg['ids']) && in_array($label['id'],$pg['ids'])){
                    $grfound = true;
                    $lfound[] = $label['label'];
                    $pvals[$j][$label['label']] = array('value'=>(($props->$name)?'<img width="15" height="15" src="/images/green_cross.jpg">':'<img width="15" height="15" src="/images/cross.jpg">'),'id'=>$label['id'],'type'=>$prop['type']);
                    break;
                }
            }            
        }
        $last = array($label['label'] => array('value'=>(($props->$name)?'<img width="15" height="15" src="/images/green_cross.jpg">':'<img width="15" height="15" src="/images/cross.jpg">'),'id'=>$label['id'],'type'=>$prop['type']));
    }elseif($props->$name!=0){
        foreach($pgroups as $j=>$pg){
           if(!empty($pg['uni'])){
                $found = false;
                foreach ($pg['uni'] as $uni){
                    $p = $uni['title'];
                    if(in_array($label['id'],$uni['ids'])){
                        $found=true;
                        $dType = '';
                        $lfound[]=$label['label'];
                        if(preg_match('/\((\S+)\)/', $label['label'],$arr)>0)
                                $dType = $arr[1];
                        $val = $props->$name;
                        if(strpos($prop['type'],'decimal')===0){
                            $val = trim($val,'0');
                            if(substr($val, -1,1)=='.') $val .= '0';
                        }
                        if(isset($pvals[$j][$p])){
                            $pvals[$j][$p]['value'][] = $val.$dType;
                        }else
                            $pvals[$j][$p] = array('value'=>array($val.$dType),'id'=>$label['id'],'sep'=>$uni['sep'],'type'=>$prop['type']);
                    }
                }
                if(!$found && is_array($pg['ids']) && in_array($label['id'],$pg['ids'])){
                    $lfound[] = $label['label'];
                    $pvals[$j][$label['label']] = array('value'=>$props->$name,'id'=>$label['id'],'type'=>$prop['type']);
                }elseif($found)
                    break;
            }else{
                if(is_array($pg['ids']) && in_array($label['id'],$pg['ids'])){
                    $grfound = true;
                    $lfound[] = $label['label'];
                    $val = $props->$name;
                    if(strpos($prop['type'],'decimal')===0){
                        $val = trim($val,'0');
                        if(substr($val, -1,1)=='.') $val .= '0';
                    }                    
                    $pvals[$j][$label['label']] = array('value'=>$val,'id'=>$label['id'],'type'=>$prop['type']);
                    break;
                }
            }            
        }
    }
}

foreach($plabels as $i=>$prop){
    if(!isset($props->_fields[$prop['name']])) continue;
    $name = $prop['name'];
    $label = $plabels[$i];//plabel($plabels,$name);
    if(in_array($label['label'], $lfound)) continue;
    if(strpos($prop['type'],'string')===0 || strpos($prop['type'],'content')===0){
        $val = $pvalues[$props->$name];
        $pvals[$last_index][$label['label']] = array('value'=>$val,'id'=>$label['id'],'type'=>$prop['type']);
    }elseif(strpos($prop['type'],'boolean')===0){
        $pvals[$last_index][$label['label']] = array('value'=>(($props->$name)?'<img width="15" height="15" src="/images/green_cross.jpg">':'<img width="15" height="15" src="/images/cross.jpg">'),'id'=>$label['id'],'type'=>$prop['type']);
    }elseif($props->$name!=0){
        $pvals[$last_index][$label['label']] = array('value'=>$props->$name,'id'=>$label['id'],'type'=>$prop['type']);
    }
}
?>
<div class="table_des">
            <table class="des_header">
                <tbody><tr>
                        <td style="padding-right:10px;white-space:nowrap;height:19px">
                            <div style="margin-top:0px" class="des_header_links">
                                <?if($mm['cnt']>0):?>
                                <a style="margin-left:0px" class="green_green" href="<?=$url?>/prices.html"><span>Цены<i>&nbsp;</i></span><sup><?=$mm['cnt']?></sup></a>
                                <?endif;?>
                                <a class="black_black active" href="#"><span>Характеристики<i>&nbsp;</i></span></a>
                                <a class="blue_blue" href="<?=$url?>/feedback.html"><span>Отзывы<i>&nbsp;</i></span><sup><?=$fcount?></sup></a>
                                <a class="orange_orange" href="<?=$url?>/services.html"><span>Услуги<i>&nbsp;</i></span><sup><?=$scount?></sup></a>
                            </div>
                        </td>
                        <td>
                            <div style="margin-top:10px" class="black_stripe_long">
                                <div class="black_left">&nbsp;</div>
                                <div class="black_right">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </tbody></table>


            <?$c = count($pgroups);foreach($pgroups as $i=>$pg): /*if($c>1 && $pg['ids'] == '*') continue;*/?>
            <table width="100%" class="des_<?=($i==0?'main':'size')?>"><!--des_main-->
                <tbody><tr>
                        <td class="gray_long" colspan="2">
                                <?if($pg['ids']!='*'):?>
                            <a onclick="$(this).toggleClass('active').parent().parent().parent().children('tr:not(:first)').toggle();return false;" href="#"><i><!----></i>
                                <span><?=$pg['title']?></span>
                            </a>
                                <?endif;?>
                            <span class="link"><?=$title?></span>
                        </td>
                    </tr>
                    <?if(!empty($pvals[$i])) foreach($pvals[$i] as $p=>$v):
                        //if(($pg['ids']!='*' && ($v['value']=='-' || !in_array($v['id'],$pg['ids'])))) continue;
                    ?>
                    <tr>
                        <td class="short">
                            <h3><?=$p?></h3>
                        </td>
                        <td>
                            <?if($v['type']=='decimal'):
                            ?>
                            <h4><?=(is_array($v['value'])?implode($v['sep'], $v['value']):$v['value'])?></h4>
                            <?else:?>
                            <h4><?=(is_array($v['value'])?implode($v['sep'], $v['value']):$v['value'])?></h4>
                            <?endif;?>
                        </td>
                    </tr>
                    <?endforeach;?>
                </tbody></table><!--table.des_main-->
            <?endforeach;?>

        </div><!--table_des-->