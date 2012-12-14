<?php
$groups = Shop_Group::get(array('@order'=>'title'));
if(isset($_GET['groups']))
    $ops = '<option value="0">Все</option>';
else
    $ops = '<option selected="selected" value="0">Все</option>';
$tr = true;
foreach ($modules as $module){
    foreach($groups as $i=>$gr){
        if($gr->id == $module->group_id){
            $modules::$_groups[$module->id] = $groups[$i];
        }
        if($tr)
            if(isset($_GET['groups']) && $gr->id == $_GET['groups'])
                $ops .= X3_Html::form_tag('option', array('selected'=>'selected','value'=>$gr->id,'%content'=>$gr->title));
            else
                $ops .= X3_Html::form_tag('option', array('value'=>$gr->id,'%content'=>$gr->title));
    }
    $tr = false;
}
?>
<div class="x3-submenu">
    <span>Товары</span> :: 
    <?if($this->allowed('Shop_Group')):?>
    <a href="/admin/shopgroup">Группы</a> :: 
    <?endif;?>
    <?if($this->allowed('Shop_Category')):?>
    <a href="/admin/shopcategory">Категории</a>
    <?endif;?>
</div>
<?=
$this->renderPartial("@views:admin:templates:default".AdminSide::$theme.".php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Товары",
            'labels'=>array(
                'group_id'=>array('@value'=>'Группа','@wrapper'=>'{{=$modules->groupTitle()}}'),
                //'manufacturer_id'=>array('@value'=>'Производитель','@wrapper'=>'{{=$modules->manufacturerTitle({@manufacturer_id})}}'),
                'title'=>array('@value'=>'Название','@wrapper'=>'<a href="/admin/shop/edit/{@id}">{@title}{{=$modules->withArticule()}}</a> 
                    <a href="/{@id}.html" target="_blank" title="товар на сайте"><span style="display:inline-block;" class="ui-icon ui-icon-newwin">&nbsp;</span></a>'),
                //'image'=>array('@value'=>'Фото','@wrapper'=>'<img title="{@image}" src="{{=((strpos($modules->image,"http://")===0)?$modules->image:"/uploads/Shop_Item/100x100/".$modules->image)}}" />'),
                'status'=>'Видимость'
            ),
            'class'=>$class,
            'actions'=>$actions,
            'action'=>$action,
            'subaction'=>$subaction,
            'paginator'=>$paginator,
            'functional'=>'Поиск: <input type="text" value="" name="search" class="x3-shop_Item-search" id="search" /> &nbsp;&nbsp;
                <form style="float:right" type="get">Группа: <select name="groups" id="groups">'.$ops.'</select></form>'
            )
        )?>

<script type="text/javascript">
    $(function(){
        $('#search').blur(function(){
            if($(this).val()!=''){
                if(typeof $('#xxx')[0] != 'undefined'){
                    $('#xxx').dialog('destroy').remove();
                }
                $(this).attr('disabled',true).css('background','url(/application/modules/Admin/images/loader_tmp.gif) no-repeat 50% 50%');
                var self = $(this);
                $.post('/admin/shopsearch',{search:self.val()},function(m){
                    $(m).attr('id','xxx').dialog({width:'1000px'});
                    self.attr('disabled',false).css('background','none');
                })
            }
        })
        $("#groups").change(function(){
            var val = $(this).val();
            var url = location.href;
            var tmp = url.replace('http://','').replace(/\/page\/[0-9]+/,'').replace(/page=[0-9]+/,'').split('/');
            tmp.shift();
            var furl = [];
            var newval = false;
            var found = false;
            for(i in tmp){
                var t = tmp[i].replace('.html','');
                if(t.indexOf('?')!==-1){
                    t = t.split('?');
                    tmp[i] = t[0];
                    furl.push(t[0]);
                    t = t.pop().split('&');
                    for(j in t){
                        var x = t[j].split('=');
                        furl.push(x[0]);
                        if(x[0]=='groups'){
                            x[1] = val;
                            found = true;
                        }
                        if(x.length==1)
                            furl.push('1');
                        else
                            furl.push(x[1]);
                    }
                }else{
                    if(newval){
                        t = val;
                        newval = false;
                    }
                    if(t == 'groups')
                        found = newval = true;
                    tmp[i] = t;
                    furl.push(t);
                }
            }
            if(!found){
                furl.push('groups', val);
            }
            url = furl.join('/');            
            location.href = '/'+url;            
        })
    })
</script>