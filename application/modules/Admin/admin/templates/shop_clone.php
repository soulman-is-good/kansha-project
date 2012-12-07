<style>
    .new {
        background: #ccffcc;
    }
    #x3-container table.prop-table td {
        text-align: left;
    }
    .head b {
        display:inline-block;
        width:200px;
    }
    .head input[type="text"],.head select {
        width:300px;
    }
</style>
<?
/* STANDART TEMPLATE */
if ($modules instanceOf X3_Module_Table):
    $G = null;
    $cs = X3::db()->fetchAll("SELECT id, title FROM data_company");
    $coms = array();
    foreach($cs as $cco)
        $coms[$cco['id']] = $cco['title'];
    unset($cs);
    $groups = X3::db()->query("SELECT * FROM shop_group ORDER BY title");//Shop_Group::get(array('@order'=>array('title')),false);
    $gs = '';
    $manufs = array('0'=>'Нет');
    $_mans = X3::db()->query("SELECT name,title FROM manufacturer ORDER BY title");//Manufacturer::get(array('@order'=>'title'));
    while($_man = mysql_fetch_assoc($_mans)){
        $manufs[$_man['name']] = $_man['title'];
    }
    unset($_mans);
    $i=0;
    while($group = mysql_fetch_assoc($groups)){
        if($subaction=='add')
            if($_GET['add']=='togroup' && $group['id'] == $_GET['id']){                
                $gs .= X3_Html::form_tag('option', array('value'=>$group['id'],'%content'=>$group['title'],'selected'=>'selected'));
                $G = $group;
            }else
                $gs .= X3_Html::form_tag('option', array('value'=>$group['id'],'%content'=>$group['title']));
        elseif($subaction=='clone'){    
            if($modules->group_id==$group['id']){
                $gs .= X3_Html::form_tag('option', array('value'=>$group['id'],'%content'=>$group['title'],'selected'=>'selected'));
                $G = $group;
            }else
                $gs .= X3_Html::form_tag('option', array('value'=>$group['id'],'%content'=>$group['title']));
        }
        $i++;
    }
    $gs = '<select style="font-size: 14px;" id="group_select"><option vlaue="0">Выберите группу</option>'.$gs.'</select>';
    $form = new Form($modules);
    ?>
    <div class="x3-submenu">
        <span>Товары</span> :: <a href="/admin/shopgroup">Группы</a> :: <a href="/admin/shopcategory">Категории</a>
    </div>
    <div class="x3-main-content" style="">
        <div id="x3-buttons" x3-layout="buttons"></div>
        <div id="x3-header" x3-layout="header"><a href="/admin/<?= $action ?>">Товары</a> > 
            <? if ($subaction == 'add' && $_GET['add']=='togroup'): ?>
            <a href="/admin/shop/add">Добавить</a> в <?=$G->title?>
            <? else: ?>
            Клонировать <?=$gs?><? endif; ?>
        </div>
        <div class="x3-paginator" x3-layout="paginator"></div>
        <div class="x3-functional" x3-layout="functional"></div>
        <div id="x3-container">
            <div style="padding:10px;clear:both;">
        <?= $form->start(array('action'=>'/shop_Item/shopsave')); ?>
            <div id="grabber"></div>
        <?= $form->end();?>
            </div>
        </div>
    </div>
    <script id="script">
        var div = null;
        var xfld = $('<fieldset />').attr({'style':'border:1px solid #cacaca;padding:10px;display:inline-block'});
        var grabber = $("#grabber");
        if(typeof $("#glinks")[0] != 'undefined')
            div = $("#glinks");
        else
            div = $('<div />').css({'margin-top':'5px'}).attr('id','glinks')       
        <?if($subaction=='add' && $_GET['add']!='togroup'):?>
        grabber.append('<span>Поиск:</span>').append(
            $('<input id="GRAB" type="text" size="120" style="display:block" />').blur(function(){
                if($(this).val()!='')
                $.post('/admin/shopsearch',{search:$(this).val()},function(m){
                    $(m).dialog({width:'1000px'});
                })                
            }))
        .append($('<button />').html('Найти').attr('type','button').click(function(){
            var q = $("#GRAB").val();
            var result = $.loadJSON('/admin/grab/?q='+q,false)
            if(result.length>0){
                div.html('');
                for(i in result){
                    var a = $('<a />').attr('href','#').html(result[i]['text']).data({'modelid':result[i]['modelid'],'hid':result[i]['hid']})
                    a.click(function(){
                        var props = $.loadJSON('/admin/grab/?modelid='+$(this).data('modelid')+'&hid='+$(this).data('hid'),false);
                        generate_form.call(this,props);
                        return false;
                    })
                    div.append($('<div />').append(a));
                }        
            }
            return false;
        })).append('<hr/>')
        <?endif;?>
        grabber.append(div);
        $("#group_select").bind('change',function(){
            location.href = '/admin/shop/add/togroup/id/'+$(this).val();
        });
function click_check(e){
    $(this).parent().parent().css('background','#dd55dd');
}
var model_title = "";
var model_articule = "";
function generate_form(props){
    if(props!=null){
        if(props['image'] == null) props['image'] = ['',''];
        if(props['articule'] == null) props['articule'] = '';
        if(props['model_name'] == null) props['model_name'] = '';
        if(props['suffix'] == null) props['suffix'] = '';
        <?if($subaction=='add' && $_GET['add']=='togroup'):?>
        div.html('').append('<div class="head"><b>Фото</b>:<input name="Shop[image]" type="file" /></div>');
        <?else:?>
        if(props['image'][1]!=null && props['image'][1].indexOf('http://')==0)
        div.html('')
        .append('<img src="'+props['image'][1]+'" /><br/><div class="head"><b>Фото url</b>:<input value="'+props['image'][1]+'" name="Shop[imageurl]" type="text" /><div>')
        .append('<div class="head"><b>Фото</b>:<input name="Shop_Item[image]" type="file" /></div>');
        else
        div.html('')
        .append('<img width="100" src="/uploads/Shop_Item/'+props['image'][1]+'" /><br/><div class="head"><b>Фото url</b>:<input value="" name="Shop[imageurl]" type="text" /><div>')
        .append('<div class="head"><b>Фото</b>:<input name="Shop_Item[image]" type="file" /><input value="'+props['image'][1]+'" name="Shop_Item[image_source]" type="hidden" /></div>');
        div.append('<div class="head"><input type="checkbox" name="imagedel" /> Удалить картинку</div>');
        <?endif;?>
        var title = props['title'];
        //var r = /\(([^\)]+?)\)/g;
        title = title.replace(/^\s+/,'').replace(/(\s+)$/,'');
        var mans = <?=  json_encode($manufs);?>//$.loadJSON('/manufacturer/getlist/as/name',false);
        <?if($subaction!='clone'):?>
        var arc = $('#GRAB').val().replace(mans[props['manname']]+' ','');
        if(props['articule']=='' && title.toLowerCase().indexOf(arc.toLowerCase())===-1)
            props['articule'] = arc;
        <?endif;?>
        model_title = title;
        model_articule = props['articule']!=''?' ('+props['articule']+')':'';
        div
        .append("<div style='padding:7px;margin:10px;font:12px TimesNewRoman;border:1px solid black' id='model_header'>"+model_title+model_articule+"</div>")
        .append(
            $('<div class="head"><b>Производитель</b>:</div>')
            .append($('<select />').attr({'name':'manufacturer_name','id':'manuf'}))
        )
        .append(
            $('<div class="head"><b>Название</b>:</div>')
            .append($('<input />').attr({'type':'text','name':'Shop[title]'}).val(title).change(function(){
                model_title = $(this).val();
                $("#model_header").html(model_title+model_articule)
            }))
            .append($('<input />').attr({'type':'checkbox','name':'Shop[isnew]','checked':props['isnew']==1})).append('<span style="margin-right:10px">Новинка</span>')
            .append($('<input />').attr({'type':'checkbox','name':'Shop[istop]','checked':props['istop']==1})).append('В топ')
        )
        .append(
            $('<div class="head"><b>Суффикс</b>:</div>')
            .append($('<input />').attr({'type':'text','name':'Shop[suffix]'}).val(props['suffix']))
            .append($('<input />').attr({'type':'checkbox','name':'gensuffix','checked':!props['item_id']>0})).append('сгенерировать')
        )            
        .append(
            $('<div class="head"><b>Артикул</b>:</div>')
            .append($('<input />').attr({'type':'text','name':'Shop[articule]'}).val(props['articule']).change(function(){
                model_articule = $(this).val()!=''?' ('+$(this).val()+')':'';
                $("#model_header").html(model_title+model_articule)
            }))
        )
        .append(
            $('<div class="head"><b>Модель</b>:</div>')            
            .append($('<input />').attr({'type':'text','name':'Shop[model_name]'}).val(props['model_name']))
        )
        .append(
            $('<div class="head"><b>Название группы</b>:</div>')            
            .append($('<input />').attr({'type':'text','name':'Group[title]'}).val(props['group_title']))
        )
        div.append($('<input />').attr({'type':'hidden','name':'Grabber[modelid]'}).val(props['modelid']))
        div.append($('<input />').attr({'type':'hidden','name':'Grabber[hid]'}).val(props['hid']))
        div.append($('<input />').attr({'type':'hidden','name':'Group[id]'}));
        if(typeof props['group_id'] != 'undefined'){
            $("[name='Group[id]']").val(props['group_id']);
            $("#group_select").val(props['group_id']);
        }
        if(props['item_id']>0){
            div.append($('<input />').attr({'type':'hidden','name':'Shop[id]'}).val(props['item_id']))
        }
        for(i in mans){
            var o = $('<option />').attr({'value':i}).html(mans[i]);
            $('#manuf').append(o);
        }
        if(typeof props['manname'] != 'undefined'){
            $('#manuf').val(props['manname']);
        }
        gen_props(props);
    }else{
        $.growl('Не удалось получить свойства товара.')
    }
}
function gen_props(props){
        delete (props['suffix']);
        delete (props['model_name']);
        delete (props['group_id']);
        delete (props['istop']);
        delete (props['isnew']);
        delete (props['manname']);
        delete (props['item_id']);
        delete (props['group_title']);
        delete (props['articule']);
        delete (props['image']);
        delete (props['title']);
        delete (props['modelid']);
        delete (props['hid']);
        if(typeof $('.prop-table')[0] !== 'undefined')
            $('.prop-table').parent().remove();
        var fset = $('<fieldset style="border:1px solid #cacaca;padding:10px;display:inline-block"><legend><input type="text" name="propgroup[]" value="Общие характеристики" /></legend><table class="prop-table"></table></fieldset>')
        var table = fset.find('.prop-table');
        //TODO: Make real-time switching between types.
        var type_select = $('<select />')
                .append($('<option />').attr({'value':'string'}).html('Строка'))
                .append($('<option />').attr({'value':'integer'}).html('Целое'))
                .append($('<option />').attr({'value':'decimal'}).html('Вещественное'))
                .append($('<option />').attr({'value':'content'}).html('Текст'))
                .append($('<option />').attr({'value':'boolean'}).html('Переключатель'))
        var filter_select = $('<select />')
                .append($('<option />').attr({'value':'prop'}).html('Свойство'))
                .append($('<option />').attr({'value':'filter'}).html('Фильтр'))
                .append($('<option />').attr({'value':'multifilter'}).html('Мультифильтр'))
        var j = 1 ;
        for(i in props){
            if(i == 'image' || typeof props[i].type == 'undefined') continue;
            var field = null;
            props[i].type = props[i].type.replace(/(\[.+\])/,'');
            switch (props[i].type){
                case "string":
                case "integer":
                case "decimal":
                    field = $('<input />').attr({'type':'text','name':'prop['+i+'][value]'}).val(props[i].value);
                    break;
                case "boolean":
                    field = $('<input />').attr({'type':'checkbox','name':'prop['+i+'][value]'}).val(1);
                    if(props[i].value==1) field.attr('checked','checked');
                    break;
                case "content":
                    field = $('<textarea />').attr({'name':'prop['+i+'][value]'}).val(props[i].value);
                    break;
            }
            if(typeof props[i].weight == 'undefined') props[i].weight = j;
            var tr = $('<tr />')
                .append($('<td />').attr({'class':'drag-handle'})
                        .append($('<input />').attr({'type':'checkbox','class':'item-box'})).click(function(e){click_check().call(this,e);})
                        .append($('<span />').attr({'style':'background:#dadaca;color:#FFF;cursor:s-resize'}).html('::'))
                )
                .append($('<td />')
                        .append($('<input />').attr({'type':'hidden','class':'weight','name':'prop['+i+'][weight]'}).val(props[i].weight))
                        .append($('<input />').attr({'type':'hidden','name':'prop['+i+'][original]'}).val(props[i].original))
                        .append($('<input />').attr({'type':'text','name':'prop['+i+'][label]'}).val(props[i].label).toggleClass('new',props[i].isnew==1))
                )
                .append($('<td />').append(field))
                .append($('<td />')
                    .append(type_select.clone().attr({'name':'prop['+i+'][type]'}).val(props[i].type))
                )
                .append($('<td />')
                    .append($('<a />').attr({'href':'#up'}).html('&uarr;').click(function(){
                        var tr = $(this).parent().parent();
                        var i = tr.index();
                        if(i<1) return false;
                        var where = tr.parent().children().eq(i-1);
                        tr.insertBefore(where);
                        where.find('.weight').val(i+1);
                        tr.find('.weight').val(i);
                        return false;
                    }))
                )
                .append($('<td />')
                    .append($('<a />').attr({'href':'#down'}).html('&darr;').click(function(){
                        var tr = $(this).parent().parent();
                        var i = tr.index();
                        var where = tr.parent().children().eq(i+1);
                        if(typeof where[0] == 'undefined') return false;
                        tr.insertAfter(where);
                        tr.find('.weight').val(i+2);
                        where.find('.weight').val(i+1);
                        return false;
                    }))
                )
                .append($('<td />')
                    .append($('<input />').attr({'type':'checkbox','name':'prop['+i+'][status]','checked':(props[i].status=='1')}).addClass('visiblity').val(props[i].status))
                )
                .append($('<td />')
                    .append($('<a />').attr({'href':'#delete'}).html('<img src="/images/cross.png" />').click(function(){
                        $(this).parent().parent().remove();
                        return false;
                    }))
                )
            table.append(tr);
            j++;
        }
        div.append(fset);
        div.append('<br style="clear:both" />')
        /*div.append($('<button />').html('Добавить подгруппу').click(function(){
            var fields = xfld.clone();
            fields.append($('<legend />').append('<input type="text" name="propgroup[]" value="Новая подгруппа" />')
            .append($('<table />').addClass('prop-table').droppable()))
            .insertBefore(this);
            return false;
        }));*/
        div.append($('<div />').append($('<button />').attr({'type':'submit'}).html('Сохранить').click(function(e){
            if(e.shiftKey){
                $('<input />').attr({'type':'hidden','name':'stay','value':'1','id':'stay'}).insertAfter(this)
            }            
            return save_func.call(this);
        }).button()).append($('<button />').attr({'type':'submit','name':'continue'}).html('Сохранить и продолжить').click(function(){
            return save_func.call(this);
        }).button()));
        div.prepend($('<div />').append($('<button />').attr({'type':'submit'}).html('Сохранить').click(function(e){
            if(e.shiftKey){
                $('<input />').attr({'type':'hidden','name':'stay','value':'1','id':'stay'}).insertAfter(this)
            }            
            return save_func.call(this);
        }).button()).append($('<button />').attr({'type':'submit','name':'continue'}).html('Сохранить и продолжить').click(function(){
            return save_func.call(this);
        }).button()));
        $('.visibility').button();
        save_func(1);
        $('#group_select').unbind('change');
        $('#group_select').bind('change',function(){
            var val = $(this).val()
            var title = $(this).find(':selected').text();
            $("[name='Group[title]']").val(title);
            $("[name='Group[id]']").val(val);
            //TODO: fire script that checks properties realtime marking them as exist (not green)
        });
    }
function save_func(sam){
            data = $('[name^="Shop["], [name^="prop["], [name^="Group["]').removeAttr('style').serialize();
            $(this).attr('disable','1');
            var err = $.loadJSON('/shop_Item/validateShop',data,false);
            if(typeof err.status =='undefined') return false;
            if(err.status == 'OK')
                return true;
            $(this).removeAttr('disable');
            for(i in err.message){
                $('[name="Shop['+i+']"]').parent().css({'color':'#DA2232','font-weight':'bold'});
                $('#stay').remove();
                $.growl(err.message[i])
            }
            var btns = {};
            if(sam!==1 && err.status == 'WARNING'){
                btns = {
                    'Продолжить сохранение':function(){$('#shop_Item-form').submit();$(this).dialog('close');},
                    'Закрыть':function(){$(this).dialog('close');}}
            }else
                btns = {'Закрыть':function(){$(this).dialog('close');}}
            $(err.result).attr('id','sres').dialog({buttons:btns});
            return false;    
}
<?
if($subaction == 'clone'):
    ?>
var a = $('<a/>').html('<?=addslashes($modules->title)?>')
<?/*$.get('/shop/getpropsfor/subaction/<?=$subaction?>/<?=isset($_GET['id'])?"/id/{$_GET['id']}":""?>value/<?=$_GET[$subaction]?>',function(prs){
    generate_form.call(a[0],prs);
},'json')*/?>
    <?
        $_GET['subaction']=$subaction;
        $_GET['value'] = $_GET[$subaction];
    ?>
    var prs = <?=Shop::getInstance()->actionGetpropsfor();?>;
    generate_form.call(a[0],prs);
<? endif; ?>
    </script>
<? endif; ?>