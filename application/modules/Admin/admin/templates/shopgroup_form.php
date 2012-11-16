<style>
    table.formtable td.fieldname{
        width:200px
    }
    table.formtable td{
        text-align: left;
    }
    table.formtable td input[type="text"], table.formtable td select{
        width:400px;
    }
    .attr-fields input {
        width:500px;
    }
    .prop-list{
        width:500px;
    }
    .pltitle {
        background: #00e;
        color: #fff;
        font-weight:bold;        
        width:468px;
        border:1px solid #ccc;
        margin: 3px 0 0 0;
    }
    .props {
        list-style:  none;
        margin:0;
        padding:0;
        min-height: 100px;
        max-height:400px;
        position: relative;
        overflow-y: auto;
        border:2px groove #00e;
    }
    .props li em{
        float:right;
        
        margin-right:20px;
    }
    .props li span{
        padding:13px 10px 12px 10px;
        font: bold 11px Verdana;
        text-shadow: 1px 1px #0c014b;
        border-right:3px groove #210548;
        margin-right:5px;
        cursor:pointer;
    }
    .props li span:after{
        content: "::";
    }
    .props li b.ui-icon {
        display:inline-block;
        visibility: hidden;
        margin-left: 10px;
        border:1px solid #ccc;
        border-radius: 4px;
    }
    .props li b:hover {
        background: #ffffcc;
    }
    .props li {
        padding:10px 10px 10px 0;
        border:1px solid transparent;
    }
    .props li:nth-child(odd){
        background: #ffffcc;
    }
    .props li:nth-child(even){
        background: #fff;
    }
    ul.props li.active{
        background: #0c014b;
        color:#ffffcc;
        border: 1px dashed #ffffcc;
    }
    #grprops ul li a, #unigroup ul li a {
        font-size:12px;
    }
    #grprops li .ui-icon-close, #filtergroup li .ui-icon-close, #unigroup li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>
<?php
$gprops = json_decode($modules->properties);
$form = new Form($modules);
?>
    <div class="x3-submenu">
        <a href="/admin/shop">Товары</a> :: <span>Группы</span> :: <a href="/admin/shopcategory">Категории</a>
    </div>
    <div class="x3-main-content" style="">
        <div id="x3-buttons" x3-layout="buttons" style="display:block">
            <?if($subaction=='edit'):?>
            <a class="x3-button" id="genmodel" style="float:right" href="/shop_Group/genmodels/id/<?=$modules->id?>">Генерировать модели</a>
            <?endif;?>
        </div>
        <div id="x3-header" x3-layout="header"><a href="/admin/<?= $action ?>">Группы</a> > <? if ($subaction == 'add'): ?>Добавить
            <? else: ?>
            <?=$modules->title?><? endif; ?>
        </div>
        <div class="x3-paginator" x3-layout="paginator"></div>
        <div class="x3-functional" x3-layout="functional"></div>
        <br clear="both" />
        <div id="x3-container"> 
        <ul>
            <li><a href="#form">Основные</a></li>
            <li><a href="#seo">SEO</a></li>
            <?if($subaction=='edit'):?>
            <li><a href="#propsss">Свойства</a></li>
            <li><a href="#group">Группы</a></li>
            <li><a href="#union">Объединения</a></li>
            <li><a href="#filter">Фильтры</a></li>
            <?endif;?>
        </ul>
        <?= $form->start(array('action'=>'/admin/shopgroupsave')); ?>
        <div id="form">
            <div style="padding:10px;clear:both;">
        <?if($subaction=='edit'):?>
                <input name="Shop_Group[id]" type="hidden" value="<?=$modules->id?>" />
        <?endif;?>
<table class="formtable">
    <tr id="<?=$class?>-title">
        <td class="fieldname"><?=$modules->fieldName('category_id')?></td>
        <td class="field" style="padding-left:30%;text-align: left"><?
            $cats = Shop_Category::get();
            $icats = explode(',',$modules->category_id);
            foreach($cats as $cat){
                if(in_array($cat->id,$icats)){
                    echo '<div>'.X3_Html::form_tag('input', array('type'=>'checkbox','value'=>$cat->id,'name'=>'Shop_Group[category_id][]','id'=>'Shop_Group_category_id_'.$cat->id,'checked'=>'1')).' '.$cat->title.'</div>';
                }else
                    echo '<div>'.X3_Html::form_tag('input', array('type'=>'checkbox','value'=>$cat->id,'name'=>'Shop_Group[category_id][]','id'=>'Shop_Group_category_id_'.$cat->id)).' '.$cat->title.'</div>';
            }
        ?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-title">
        <td class="fieldname">ID группы</td>
        <td class="field">
            <?php
            $ggrs = Grabber_Groups::get(array('group_id'=>$modules->id));
            foreach($ggrs as $ggr):?>
            <div><?//Grabber::getByPk($ggr->grabber_id)->title?> <input type="text" value="<?=$ggr->value?>" name="Grabber_Groups[<?=$ggr->grabber_id?>][value]" /></div>
            <?
            endforeach;
            ?>
        </td>
        <td class="required"></td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-title">
        <td class="fieldname"><?=$modules->fieldName('title')?></td>
        <td class="field"><?=$form->input('title')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-suffix">
        <td class="fieldname"><?=$modules->fieldName('suffix')?></td>
        <td class="field"><?=$form->input('suffix',array('style'=>'width:95%'))?><input type="button" for="[name='Shop_Group[suffix]']" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-short_desc">
        <td class="fieldname"><?=$modules->fieldName('short_desc')?></td>
        <td class="field"><?=$form->input('short_desc',array('style'=>'width:95%'))?><input type="button" for="[name='Shop_Group[short_desc]']" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-content">
        <?=$form->renderPartial(array("text"),"<td class='fieldname'>%label</td><td class='field'>%field</td><td class='required'>%required</td><td class='error'></td>")?>
        
    </tr>
    <tr id="<?=$class?>-weight">
        <td class="fieldname"><?=$modules->fieldName('weight')?></td>
        <td class="field"><?=$form->input('weight')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-status">
        <td class="fieldname"><?=$modules->fieldName('status')?></td>
        <td class="field"><?=$form->checkbox('status')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-status">
        <td class="fieldname"><?=$modules->fieldName('usename')?></td>
        <td class="field"><?=$form->checkbox('usename')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-status">
        <td class="fieldname"><?=$modules->fieldName('bbracket')?></td>
        <td class="field"><?=$form->checkbox('bbracket')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="submit">
        <td colspan="3"><button name="take" type="submit">Сохранить</button></td>
        <td ><input type="checkbox" name="regenerate" /> Сгенерировать товары</td>
    </tr>
</table>
            </div>
        </div>
        <div id="seo">
<table class="formtable">            
    <tr><td colspan="4" bgcolor="#cacaca">Общая метаинформация</td></tr>
    <tr id="<?=$class?>-allheader">
        <td class="fieldname"><?=$modules->fieldName('allheader')?></td>
        <td class="field"><?=$form->input('allheader',array('style'=>'width:90%','placeholder'=>'По умолчанию'))?><input type="button" for="#Shop_Group_allheader" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-alltitle">
        <td class="fieldname"><?=$modules->fieldName('alltitle')?></td>
        <td class="field"><?=$form->input('alltitle',array('style'=>'width:90%'))?><input type="button" for="#Shop_Group_alltitle" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-allkeywords">
        <td class="fieldname"><?=$modules->fieldName('allkeywords')?></td>
        <td class="field"><?=$form->textarea('allkeywords',array('rows'=>2,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_allkeywords" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-alldescription">
        <td class="fieldname"><?=$modules->fieldName('alldescription')?></td>
        <td class="field"><?=$form->input('alldescription',array('rows'=>3,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_alldescription" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr><td colspan="4" bgcolor="#cacaca">Метаинформация для характеристик товара</td></tr>
    <tr id="<?=$class?>-metaheader">
        <td class="fieldname"><?=$modules->fieldName('metaheader')?></td>
        <td class="field"><?=$form->input('metaheader',array('style'=>'width:90%','placeholder'=>'По умолчанию'))?><input type="button" for="#Shop_Group_metaheader" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-metatitle">
        <td class="fieldname"><?=$modules->fieldName('metatitle')?></td>
        <td class="field"><?=$form->input('metatitle',array('style'=>'width:90%'))?><input type="button" for="#Shop_Group_metatitle" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-metakeywords">
        <td class="fieldname"><?=$modules->fieldName('metakeywords')?></td>
        <td class="field"><?=$form->textarea('metakeywords',array('rows'=>2,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_metakeywords" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-metadescription">
        <td class="fieldname"><?=$modules->fieldName('metadescription')?></td>
        <td class="field"><?=$form->textarea('metadescription',array('rows'=>3,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_metadescription" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr><td colspan="4" bgcolor="#cacaca">Метаинформация для цен товара</td></tr>
    <tr id="<?=$class?>-priceheader">
        <td class="fieldname"><?=$modules->fieldName('priceheader')?></td>
        <td class="field"><?=$form->input('priceheader',array('style'=>'width:90%','placeholder'=>'По умолчанию'))?><input type="button" for="#Shop_Group_priceheader" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-pricetitle">
        <td class="fieldname"><?=$modules->fieldName('pricetitle')?></td>
        <td class="field"><?=$form->input('pricetitle',array('style'=>'width:90%'))?><input type="button" for="#Shop_Group_pricetitle" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-pricekeywords">
        <td class="fieldname"><?=$modules->fieldName('pricekeywords')?></td>
        <td class="field"><?=$form->textarea('pricekeywords',array('rows'=>2,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_pricekeywords" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-pricedescription">
        <td class="fieldname"><?=$modules->fieldName('pricedescription')?></td>
        <td class="field"><?=$form->textarea('pricedescription',array('rows'=>3,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_pricedescription" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr><td colspan="4" bgcolor="#cacaca">Метаинформация для отзывов товара</td></tr>
    <tr id="<?=$class?>-feedheader">
        <td class="fieldname"><?=$modules->fieldName('feedheader')?></td>
        <td class="field"><?=$form->input('feedheader',array('style'=>'width:90%','placeholder'=>'По умолчанию'))?><input type="button" for="#Shop_Group_feedheader" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-feedtitle">
        <td class="fieldname"><?=$modules->fieldName('feedtitle')?></td>
        <td class="field"><?=$form->input('feedtitle',array('style'=>'width:90%'))?><input type="button" for="#Shop_Group_feedtitle" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-feedkeywords">
        <td class="fieldname"><?=$modules->fieldName('feedkeywords')?></td>
        <td class="field"><?=$form->textarea('feedkeywords',array('rows'=>2,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_feedkeywords" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-feeddescription">
        <td class="fieldname"><?=$modules->fieldName('feeddescription')?></td>
        <td class="field"><?=$form->textarea('feeddescription',array('rows'=>3,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_feeddescription" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr><td colspan="4" bgcolor="#cacaca">Метаинформация для услуг товара</td></tr>
    <tr id="<?=$class?>-servheader">
        <td class="fieldname"><?=$modules->fieldName('servheader')?></td>
        <td class="field"><?=$form->input('servheader',array('style'=>'width:90%','placeholder'=>'По умолчанию'))?><input type="button" for="#Shop_Group_servheader" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-servtitle">
        <td class="fieldname"><?=$modules->fieldName('servtitle')?></td>
        <td class="field"><?=$form->input('servtitle',array('style'=>'width:90%'))?><input type="button" for="#Shop_Group_servtitle" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-servkeywords">
        <td class="fieldname"><?=$modules->fieldName('servkeywords')?></td>
        <td class="field"><?=$form->textarea('servkeywords',array('rows'=>2,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_servkeywords" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-servdescription">
        <td class="fieldname"><?=$modules->fieldName('servdescription')?></td>
        <td class="field"><?=$form->textarea('servdescription',array('rows'=>3,'style'=>'width:90%'))?><input type="button" for="#Shop_Group_servdescription" value="..." class="suffixF" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="submit2">
        <td colspan="4"><button name="take2" type="submit">Сохранить</button></td>
    </tr>
        </table>
        </div>
        <?= $form->end();?>
            <?if($subaction=='edit'):?>
            <div id="propsss">
                <div class="prop-list">
                <ul name="Props" class="props" style="float: left;margin-right:10px;height: 800px;">
            <?
            $prop = Shop_Properties::get(array('@condition'=>array('group_id'=>$modules->id),'@order'=>'weight'));
            $props = array();
            $plist = array();
            $fill = false;
            $filters = json_decode($modules->filters,1);
            if(empty($filters)){
                $filters = array();
            }            
            foreach ($prop as $i=>$p): 
                $props[$p->id]['value']=$p->name;
                /*$tmp = Shop_Proplist::get(array('@condition'=>array('group_id'=>$modules->id,'property_id'=>$p->id),'@order'=>'weight'));
                if($tmp->count()>0)
                    foreach($tmp as $j=>$spl)
                        $plist[$p->name][] = array(
                            'id'=>$spl->id,
                            'value'=>$spl->value,
                            'weight'=>$spl->weight,
                            'status'=>$spl->status,
                            'title'=>$spl->title,
                            'description'=>$spl->description
                        );
                 */
                $props[$p->id]['label']=$p->label;                
            ?>
                    <li dtype="<?=$p->type?>" multifilter="<?=$p->multifilter?>" status="<?=$p->status?>" gr="<?=$p->isgroup?>" pid="<?=$p->id?>" val="<?=$p->name?>"><span></span><i><?=$p->label?></i><b class="ui-icon ui-icon-link"></b></li>
            <? endforeach; ?>
                </ul>
                </div>
                <form id="attr-form"></form>
                <br clear="both" />
                <!--button type="button" onclick="$('<ul />').attr('class','props').insertAfter('.props:last');initProps();">Добавить группу</button-->
            </div>
            <div id="group">
                        <div id="grprops">
                        </div>
                <a href="#button" onclick="add_gr();" class="x3-button" style="color:#fff">Добавить группу</a>
            </div>
            <div id="union">
                <div id="unigroup">
                </div>
            </div>
            <div id="filter">
                <div id="filtergroup">
                </div>
            <div class="prop-list" style="width:400px">
                <ul name="Props" class="props supersortprops" style="float: left;margin-right:10px;height: 800px;width:400px"></ul>
            </div>
            <form id="filter-form"></form>
            <br clear="both" />
            <button type="button" onclick="addPropFilter();">Добавить</button>
            </div>
            <?endif;?>
    </div>
        <?php
        ?>
        <script type="text/javascript">
                var plist = <?=json_encode($plist)?>;
                var filters = <?=  json_encode($filters)?>;
                var  pgroups = <?=empty($gprops)?'[]':$modules->table['properties']?>;
                var GID = '<?=$modules->id?>';
                var sel = '';
                var sel_id = 0;
                var last_count = 0;
                var last_item = null;
                var dataTypes = {
                    'string':'Строка',
                    'content':'Текст',
                    'integer':'Целое',
                    'decimal':'Вещественное',
                    'boolean':'Переключатель'
                }
                
		var availableTags = <?=json_encode($props)?>;
                var tagsSelect = $('<select />');
                //fulfill this var
                for(x in availableTags){
                    tagsSelect.append($('<option />').attr({'value':x}).html(availableTags[x].label));
                }
                ///////////////////////////
                var tmpl = '<fieldset style="" id="edit-attr"><legend>Редактировать</legend></fieldset>';
                var groupButton = $('<button />').attr({'type':'button'}).html('Сгруппировать').click(function(){
                    //TODO: check groups for existant (maybe)
                    var title = prompt('Название группы:');
                    if(title == '' || title == null) return false;
                    var pids = [];
                    $('#propsss .props .active').each(function(){
                        pids.push($(this).attr('pid'));
                    })
                    pgroups.push({title:title,ids:pids});
                    savePgroups();
                    Gtabs();
                });
                var filterButton = $('<button />').attr({'type':'button'}).html('Объединить фильтры').click(function(){
                    var title = prompt('Название фильтра:');
                    if(title == '' || title == null) return false;
                    var pids = [];
                    var id = -1;
                    var xids = [];
                    $('#filter .props .active').each(function(i){
                        if(id == -1) id = $(this).attr('pid');
                        pids = pids.concat(filters[$(this).attr('pid')].ids);
                        xids.push($(this).attr('pid'));
                    }).remove();
                    var tmp = [];
                    for(j in filters){
                        if(xids.indexOf(j)==-1){
                            tmp.push(filters[j]);
                        }
                    }
                    filters = tmp;
                    filters.splice(id,0,{title:title,ids:pids});
                    saveFilters();
                    Flist();
                    $('#filter .props li').eq(id).click();
                });
                <?if($subaction!='add'):?>
                function savePgroups(){
                    $.post('/Shop/Savepg',{models:pgroups,cid:<?=$modules->id?>},function(){
                        $.growl('Сохранено','ok');
                    })
                }
                function saveFilters(){
                    $.post('/Shop/Savefilters',{models:filters,cid:<?=$modules->id?>},function(){
                        $.growl('Сохранено','ok');
                    })
                }
                <?endif;?>
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}                
                function save_prop(){
                    $.ajax({url:'/shop_Properties/updateprops', data:$('#attr-form').serialize(), type:'post',success:function(m){
                        if(m.status=='OK'){
                            $.growl('Сохранено','ok');
                            $('#edit-attr input[name$="][value]"]').each(function(){
                                var m = $(this).attr('name').match(/\[([\d]+)\]/);
                                if(m!=null){                                    
                                    plist[sel][m.pop()].value = $(this).val();
                                }else
                                    console.log($(this))
                            })
                            $('#edit-attr input[name$="][title]"]').each(function(){
                                var i = $(this).attr('name').match(/\[([\d]+)\]/).pop();
                                plist[sel][i].title = $(this).val();
                            })
                            $('#edit-attr input[name$="][weight]"]').each(function(){
                                var i = $(this).attr('name').match(/\[([\d]+)\]/).pop();
                                plist[sel][i].weight = $(this).val();
                            })
                            $('#edit-attr input[name$="][status]"]').each(function(){
                                var i = $(this).attr('name').match(/\[([\d]+)\]/).pop();
                                plist[sel][i].status = $(this).is(':checked')?1:0;
                            })
                            $('#propsss .props .active i').html(m.message).attr({
                                'gr':$('#edit-attr input[name$="[isgroup]"]').is(':checked')?'1':'0',
                                'status':$('#edit-attr input[name$="[status]"]').is(':checked')?'1':'0',
                                'multifilter':$('#edit-attr input[name$="[multifilter]"]').is(':checked')?'1':'0'
                            });
                        }else
                            for(i in m.message)
                                $.growl(m.message[i]);
                    },dataType:'json',async:false});
                }
                $(function(){
                    $('#x3-container').tabs();
                    $('.renameprop').live('click',function(){
                        var id = $(this).parent().find('[name="Props[id]"]').val();
                        $('<div title="Переименовать название" />')
                            .append('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>Изменение уникального имени может повлечь сбои в дальнейшей работе! <b>Используйте с умом.</b></p>')
                            .append('Уникальное имя своства:')
                            .append($('<input />').attr({'name':'Name'}).val(availableTags[id]['value'])).append('<br/>')
                            .append('Уникальный преобразователь')
                            .append($('<input />').attr({'id':'trans'}))
                            .append($('<button />').attr({'type':'button'}).click(function(){
                                var val = $(this).parent().find('#trans').val();
                                var self = $(this)
                                if(val == '') return false;
                                $.get('/shop/genprop',{value:val},function(m){
                                    self.parent().find('[name="Name"]').val(m);
                                    self.parent().find('#trans').val('');
                                })
                            }).html('Преобразовать'))
                            .dialog({modal:true,
                            buttons:{
                                'Сохранить':function(){
                                    var vall = $(this).find('[name="Name"]').val();
                                    if(vall == availableTags[id]['value']) return false;
                                    var self = $(this);
                                    $.post('/shop/updatepropname',{name:vall,id:id},function(m){
                                        if(m=='OK'){
                                            self.find('[name="Name"]').val(vall);
                                            availableTags[id]['value'] = vall;
                                            $.growl('Сохранено!','ok');
                                        }else
                                            $.growl(m);
                                    }).error(function(){$.growl('Ошибка сохранения','error');})
                                    $(this).dialog('destroy');
                                },'Отмена':function(){
                                    $(this).dialog('destroy');
                                }
                            }});
                        return false;
                    })
                    $('a[href="#delProp"]').live('click',function(){
                        if(!confirm('Вы уверены что хотите удалить это свойство?')) return false;
                        var id = $(this).parent().find('[name="Props[id]"]').val();
                        var self = $(this);
                        $.get('/shop/deleteprop',{id:id},function(m){
                            if(m=='OK'){
                                $.growl('Удалено!');
                                self.parent().parent().remove();
                                $('#propsss .props li.active').removeClass('active').remove();
                                sel = sel_id = null;
                            }
                        })
                    })
                    $('#filter .props li').live('click',function(e){
                        if(e.ctrlKey){
                            $(this).toggleClass('active');
                            $('#edit-filter').remove();
                            if($('#filter .props li.active').length>1){
                                $('#filter-form').html('');
                                $('#filter-form').append(filterButton.clone(true).button({'icons':{'primary':'ui-icon-carat-2-e-w'}}));
                            }else
                                $('#filter-form').html('');
                            return false;
                        }   
                        if($(this).hasClass('active')) return true;
                        $('#edit-filter').remove();
                        $('#filter-form').html('');
                        $('#filter .props li.active').removeClass('active');
                        $(this).addClass('active');
                        var sel  = $(this);
                        var x =$(tmpl).attr({'id':'edit-filter'});
                        var pid = $(this).attr('pid');
                        var filter = filters[pid];
                        x.append(
                            $('<div />')
                            .append('<span>Название фильтра:</span>')
                            .append($('<input />').attr({'type':'text'}).val(filter.title).change(function(){
                                filters[pid].title = $(this).val();
                                sel.children('i').html($(this).val());
                                saveFilters();
                            }))
                        )
                        .append($('<input />').attr({'type':'checkbox','checked':filter.collapse==1}).click(function(){
                            filters[pid].collapse = $(this).is(':checked')?1:0;
                            saveFilters();
                        }))
                        .append('Отображать свернутым').append('<br/>')
                        .append($('<input />').attr({'type':'checkbox','checked':filter.additional==1}).click(function(){
                            filters[pid].additional = $(this).is(':checked')?1:0;
                            saveFilters();
                        }))
                        .append('В дополнительных');
                        var y = $('<div />').css({'border':'1px solid black','padding':'10px','border-radius':'5px'}).addClass('supersort')
                        for(j in filter.ids){
                            y.append(
                                $('<div />').css({'padding':'10px','border':'1px solid #cacaca','margin':'0 0 10px 0','background':'#FFF'}).attr({'pid':filter.ids[j]})
                                .append($('<span class="handle">::</span>').css({'cursor':'pointer','background':'#fafefe'}))
                                .append($('<span />').html(availableTags[filter.ids[j]].label))
                            );
                        }
                        y.sortable({
                            handle:'.handle',
                            connectWith:'.supersortprops',
                            stop:function(e,ui){
                                var pids = [];
                                ui.item.parent().children('div').each(function(){
                                    pids.push($(this).attr('pid'));
                                })
                                filters[pid].ids = pids;
                                saveFilters();
                            }
                        })
                        x.append(y);
                        $('#filter-form').append(x);
                    });
                    
                    $('#propsss .props li').live('click',function(e){
                        if(e.ctrlKey){
                            $(this).toggleClass('active');
                            $('#edit-attr').remove();
                            if($('#propsss .props li.active').length>1){
                                $('#attr-form').html('');
                                $('#attr-form').append(groupButton.clone(true).button({'icons':{'primary':'ui-icon-carat-2-e-w'}}));
                                //$('#attr-form').append(filterButton.clone(true).button({'icons':{'primary':'ui-icon-carat-2-e-w'}}));
                                //$('#attr-form').append(unionButton.clone(true).button({'icons':{'primary':'ui-icon-carat-2-e-w'}}));
                                //$('#attr-form').append(deleteButton);
                            }else
                                $('#attr-form').html('');
                            return false;
                        }
                        if($(this).hasClass('active')) return true;
                        $('#attr-form').html('');
                        if(typeof $('#edit-attr')[0] != 'undefined' && typeof $('#edit-attr').data('edited')!= 'undefined' && confirm('Изменения не сохранены! Сохранить изменения?')){
                            save_prop();
                        }
                        $('#edit-attr').remove();
                        $('#propsss .props li.active').removeClass('active');
                        $(this).addClass('active');
                        var x =$(tmpl);
                        sel = $(this).attr('val');
                        sel_id = $(this).index();
                        var pid = $(this).attr('pid');
                        x.find('legend').html('Свойство №'+pid+' ('+dataTypes[$(this).attr('dtype')]+')');
                        x.append(
                            $('<div />').append($('<input />').attr({'name':'Props[id]','type':'hidden'}).val(pid))
                            .append('<a href="#rename" class="renameprop">Название свойства:</a>')
                            .append($('<input />').attr({'name':'Props[value]'}).val($(this).children('i').text()))
                            .append($('<input />').attr({'name':'Props[isgroup]','type':'checkbox','checked':$(this).attr('gr')=='1'}))
                            .append($('<a />').attr({'href':'#delProp','class':'x3-button red','style':'float:right;color:#fff'}).html('Удалить'))
                            .append('как подгруппы')
                            .append('<br />')
                            .append('<br />')
                            .append($('<input />').attr({'name':'Props[status]','type':'checkbox','checked':$(this).attr('status')=='1'}))
                            .append('Видимость')
                            .append('<br />')
                            .append($('<input />').attr({'name':'Props[multifilter]','type':'checkbox','checked':$(this).attr('multifilter')=='1'}))
                            .append('Мультифильтр')
                        );
                        if(typeof plist[sel] == 'undefined')
                            plist[sel] = $.loadJSON('/shop/loadProp',{pid:pid,gid:GID},false);
                        if(typeof plist[sel] != 'undefined'){
                            x.append('<br/>Свойства:');
                            var y = $('<div />').attr('id','attr-sort').addClass('sortable1');
                            for(i in plist[sel]){
                                y.append(
                                    $('<div />').addClass('dragit').css({'padding':'10px','border':'1px solid #cacaca','margin':'0 0 10px 0','background':'#FFF'})
                                    .attr({'pid':plist[sel][i].id})
                                        .append($('<span class="handle">::</span>').css({'cursor':'pointer','background':'#fafefe'}))
                                        .append($('<input />').attr({'name':'Plist['+i+'][id]','type':'hidden'}).val(plist[sel][i].id))
                                        .append($('<input />').attr({'name':'Plist['+i+'][weight]','type':'hidden'}).val(plist[sel][i].weight))
                                        .append($('<input />').attr({'name':'Plist['+i+'][value]'}).val(plist[sel][i].value))
                                        .append($('<textarea />').attr({'name':'Plist['+i+'][description]'}).css('display','none').attr('class','propdesc').html(plist[sel][i].description))
                                        .append($('<a href="#desc" title="Описание" style="margin-left:10px"><img src="/images/admin/desc.gif" /></a>').click(function(){
                                            var t = $(this).siblings('[name$="][value]"').val();
                                            var desc = $(this).siblings('.propdesc')
                                            var tmp = $('<textarea />').attr({'id':'description'}).val(desc.val());
                                            $('<div />').attr({'title':'Описание свойства '+t}).append(
                                                tmp
                                            ).dialog({'width':'1000','height':'500','buttons':{
                                                    'Ok':function(){
                                                        desc.val(CKEDITOR.instances['description'].getData());
                                                        $(this).dialog('close');
                                                    },
                                                    'Отмена':function(){
                                                        $(this).dialog('close')
                                                    }
                                            },close:function(){
                                                $(this).data('ckeditor').destroy();
                                                $(this).dialog('destroy');
                                            },create:function(){$(this).data('ckeditor',CKEDITOR.replace( tmp[0] ));}})
                                        })).append($('<ul with="'+plist[sel][i].id+'" class="sortable2"><li style="display:inline">Объединить</ul>')
                                                    .css({'display':'none','list-style':'none','border':'1px dashed orange','padding':'10px','margin':'0 0 0 10px'})
                                                    .sortable({'connectWith':'.sortable1'}))
                                        .append('  как: ').append($('<input />').attr({'name':'Plist['+i+'][title]'}).val(plist[sel][i].title))
                                        .append('&nbsp;||&nbsp;').append($('<input />').attr({'name':'Plist['+i+'][status]','type':'checkbox','checked':plist[sel][i].status==1?true:false}))
                                        .append('Видимость')
                                        .append($('<a />').css({'float':'right'}).attr({'href':'#','title':'удалить значение','class':'delpropval'}).append('<img src="/images/cross.png" />'))
                                )
                            }
                            x.append(y.sortable({
                                handle:'span.handle',
                                connectWith:'.sortable2',
                                start:function(e,ui){
                                    $('.sortable2').css('display','inline');
                                    last_count = $('#attr-sort').children('.dragit').length-1;
                                    last_item = ui.item;
                                    ui.item.children('ul').css('display','none');
                                },
                                stop:function(e,ui){
                                    $('#attr-sort').children().length;
                                    $('.sortable2').css('display','none');
                                    if(last_count!=$('#attr-sort').children('.dragit').length){
                                        itemJoin.call(ui.item);
                                    }
                                    $('#attr-sort div').each(function(){
                                        $(this).children('[name$="][weight]"]').val($(this).index());
                                    })
                                }
                            }));
                        }
                        x.append($('<button />').attr('type','button').css('margin','10px').html('Записать').click(function(){save_prop()}).button())
                        $('#attr-form').append(x);
                    })
                    ////////////
                    //PROPERTIES
                    ////////////
                    initProps();
                    $('#propsss .props').live('sortstart',function(e,ui){
                        ui.item.parent().find('b').css('visibility','visible');
                        ui.item.find('b').css('display','none');
                    })
                    $('#propsss .props').live('sortstop',function(e,ui){
                            ui.item.parent().find('b').css('visibility','hidden');
                            ui.item.css('border','1px solid white');
                            var pids = [];
                            //$('.props').css({'overflow':'auto'})
                            ui.item.parent().children('li').each(function(i){
                                pids.push($(this).attr('pid'));
                            })
                            $.post('/site/weights',{'module':'Shop_Properties','ids':pids.join(',')})
                    })
                    $('#propsss .props').live('sortover',function(e,ui){
                        ui.item.css('border','2px solid blue');
                    })
                    $('#propsss .props').live('sortout',function(e,ui){
                        ui.item.css('border','1px solid white');
                        //console.log(ui.item);
                    })
                    //$('#propsss .props input[type="checkbox"]').click(function(){
                        //$.get('/admin/status/action/props',{attr:'isadditional',status:$(this).is(':checked')?'1':'0',pk:$(this).parent().parent().attr('pid')},function(){})
                    //})
                    $('#filter .props').live('sortstop',function(e,ui){
                            var pids = [];
                            ui.item.parent().children('li').each(function(i){
                                pids.push(filters[$(this).attr('pid')]);
                                $(this).attr('pid',i);
                            })
                            filters = pids;
                            saveFilters();
                    })
                    Gtabs();
                    Utabs();
                    Flist();
                    //Ftabs();
                    
                    //HANDLERS
                    //HANDLERS
                    //HANDLERS
                    
                    //Delete property value
                    $('.delpropval').live('click',function(){
                        if(window.bX3Idle) return false;
                        window.bX3Idle = true;
                        var id = $(this).parent().attr('pid');
                        var idx = $(this).parent().index();
                        var self = $(this);
                        self.parent().css('background','url(/application/modules/Admin/images/loader.gif) no-repeat right 50%');
                        $.get('/shop/checkitems',{pid:id},function(m){
                            window.bX3Idle = false;
                            self.parent().css('background','#fff');
                            var d = $('<div />').attr({'title':'Вы действительно хотите удалить это значение?'});
                            if(m!=null && m.length>0){
                                var cnt = m.length;
                                d.append('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Удаление данного свойства <b>ПОВЛИЯЕТ</b> на следующие товары:</p>');
                                d.append('<ul>');
                                for(i in m){
                                    if(cnt > 10 && i == 5) break;
                                    d.append('<li><a href="/admin/shop/edit/'+m[i].id+'" target="_blank">'+m[i].title+'</a></li>');
                                }
                                d.append('</ul>');
                                if(cnt > 10){
                                    d.append('<p><i>...и еще на <a target="_blank" href="/shop/checkitems/pid/'+id+'">'+(cnt-5)+'товаров</a></i></p>')
                                }
                            }else{
                                d.append('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>Удаление данного свойства не повлияет ни на один товар</p>');
                            }
                            d.dialog({buttons:{
                                    'Продолжить':function(){
                                        $.post('/shop/delpropval',{pid:id},function(m){
                                            if(m!='OK') $.growl('Ошибка удаления');
                                            else {
                                                self.parent().remove();
                                                plist[sel].splice(idx, 1);
                                                $.growl('Удалено!');
                                            }
                                        }).error(function(){$.growl('Ошибка удаления')})
                                        $(this).dialog("destroy")
                                    },
                                    'Отмена':function(){
                                        $(this).dialog("destroy")
                                    }
                            }})
                        },'json')
                        return false;
                    })
                    
                    $( "#grprops span.ui-icon-close" ).live( "click", function() {
                        if(confirm('Уверены, что хотите удалить группу?')){
                            var index = $( "li", $('#grprops') ).index( $( this ).parent() );
                            $('#grprops').tabs( "remove", index );
                            var tmp = [];
                            for(i in pgroups){
                                if(i!=index)
                                    tmp.push(pgroups[i]);
                            }
                            pgroups = tmp;
                            savePgroups();
                        }
                    });
                    $( "#filtergroup span.ui-icon-close" ).live( "click", function() {
                        if(confirm('Уверены, что хотите удалить группу?')){
                            var index = $( "li", $('#filtergroup') ).index( $( this ).parent() );
                            $('#filtergroup').tabs( "remove", index );
                            var tmp = [];
                            for(i in filters){
                                if(i!=index)
                                    tmp.push(filters[i]);
                            }
                            filters = tmp;
                            saveFilters();
                        }
                    });
                    $( "#unigroup span.ui-icon-close" ).live( "click", function() {
                        if(confirm('Уверены, что хотите удалить объединение?')){
                            var index = $( "li", $('#unigroup') ).index( $( this ).parent() );
                            $('#unigroup').tabs( "remove", index );
                            pgroups[index].uni = [];
                            savePgroups();
                        }
                    });
                    
                    $("#grprops li").live('dblclick',function(){
                        var i = $(this).index();
                        pgroups[i].title = prompt('Переименовать группу',pgroups[i].title);
                        $(this).children('a').html(pgroups[i].title);
                        savePgroups();
                    })
                    $('.unititle').live('blur',function(){
                        var idx = parseInt($(this).parent().parent().attr('pg'));
                        if(isNaN(idx)) return false;
                        var k = $(this).parent().parent().attr('no');
                        pgroups[idx].uni[k].title = $(this).val();
                        savePgroups();
                    })
                    $('.unisep').live('blur',function(){
                        var idx = parseInt($(this).parent().parent().attr('pg'));
                        if(isNaN(idx)) return false;
                        var k = $(this).parent().parent().attr('no');
                        pgroups[idx].uni[k].sep = $(this).val();
                        savePgroups();
                    })
                    $('.trash').live('click',function(){
                        var isfilter = $('#x3-container').tabs("option","selected") == 5;
                        var s = isfilter?"#filtergroup ":"#grprops "
                        if($(s+'.propcheck:checked').length==0) return false;
                        $(s+'.propcheck:checked').each(function(){
                            var pi = $(this).parent().attr('pid');
                            var idx = $(this).parent().parent().index()-1;
                            if(isfilter){
                                var k = filters[idx].ids.indexOf(pi);
                                filters[idx].ids.splice(k,1);
                                saveFilters();
                            }else{
                                var k = pgroups[idx].ids.indexOf(pi);
                                pgroups[idx].ids.splice(k,1);
                                savePgroups();
                            }
                            $(this).parent().remove();
                        })
                    })
                    $('.plink').live('click',function(){
                        if($('.propcheck:checked').length==0) return false;
                        var idx = $('#grprops').tabs("option","selected");
                        if(typeof pgroups[idx].uni == 'undefined')
                            pgroups[idx].uni = [];
                        var unip = {title:prompt('Введите название объединения','Без имени'),ids:[],sep:' '};
                        $('.propcheck:checked').each(function(){
                            var pid = $(this).parent().attr('pid');
                            unip.ids.push(pid);
                            $(this).attr({'checked':false});
                        })
                        pgroups[idx].uni.push(unip);
                        savePgroups();
                        Utabs();
                    })
                    $('.propcheck').live('click',function(){
                        if($('.propcheck:checked').length>0){
                            $('.plink, .trash').button("option","disabled",false)
                        }else
                            $('.plink, .trash').button("option","disabled",true)
                    })
                    $('.deluni').live('click',function(){
                        var j = $(this).parent().attr('no');
                        var k = $(this).parent().attr('pg');
                        pgroups[k].uni.splice(j,1);
                        $(this).parent().remove();
                        savePgroups();
                        return false;
                    })
                    $('.addprop').live('click',function(){
                            var self = $(this);
                            var isfilter = $('#x3-container').tabs("option","selected") == 5;
                            var s = isfilter?"#filtergroup ":"#grprops "                            
                            $('<div />').attr({'title':'Добавить свойство:','style':'text-align:center'}).append(tagsSelect).dialog({
                                modal:true,
                                width:500,
                                buttons:{
                                    'Ok':function(){
                                        var idx = self.parent().parent().index()-1;
                                        var val = tagsSelect.val();
                                        if(isfilter){
                                            if(typeof filters[idx].ids == 'undefined')
                                                filters[idx].ids = [];
                                            filters[idx].ids.push(val);
                                            saveFilters();
                                            $('<div />').attr({'pid':val,'no':filters[idx].ids.length-1}).append($('<input />').attr({'type':'checkbox','class':'propcheck'})).append(availableTags[val].label).insertBefore(self.parent().parent().children('div:last'));
                                        }else{
                                            if(typeof pgroups[idx].ids == 'undefined')
                                                pgroups[idx].ids = [];
                                            pgroups[idx].ids.push(val);
                                            savePgroups();
                                            $('<div />').attr({'pid':val,'no':pgroups[idx].ids.length-1}).append($('<input />').attr({'type':'checkbox','class':'propcheck'})).append(availableTags[val].label).insertBefore(self.parent().parent().children('div:last'));
                                        }
                                        $(this).dialog('destroy');
                                    },
                                    'Отмена':function(){
                                        $(this).dialog('destroy');
                                    }
                                }
                            });
                            return false;
                        })
                })
                
                /**
                 * join propery values. Make one be synonimous to other
                 */
                function itemJoin() {
                    var w = this.parent().attr('with');
                    if(w != undefined){
                        $.post('/shop_Proplist/join',{'id':this.attr('pid'),'with':w},function(m){}).error(function(m){})
                    }
                }
                
                function initProps(){
                    $('#propsss .props li b').sortable({
                        connectWith:'.props',
                        out:function(e,ui){
                            ui.item.css({'border':'1px solid white'});
                            if(typeof ui.item.parent().parent().attr('pid') != 'undefined'){
                                $('#propsss .props li b').css('visibility','hidden');
                                var _id = parseInt(ui.item.attr('pid'));
                                var _with = parseInt(ui.item.parent().parent().attr('pid'));
                                if(isNaN(_id) || isNaN(_with) || _id == _with || _id == 0 || _with == 0)
                                    return false;
                                $.get('/shop/unite/',{'id':_id,'with':_with},function(m){
                                    if(m!='OK')
                                        $.growl('Ошибка объединения свойств.');
                                    else{
                                        $.growl('Объединено!','ok');
                                        ui.item.remove();
                                        if($('#attr-form').find('legend').html().indexOf('№'+_id)!==-1)
                                            $('#attr-form').html('');
                                    }
                                }).error(function(){
                                    $.growl('Ошибка объединения!');
                                })
                            }
                        }
                    })
                    $('#propsss .props').sortable({
                        handle:'span',
                        connectWith:'.props li b'
                    })
                    $('#filter .props').sortable({
                        handle:'span'
                        //connectWith:'.supersort'
                    })
                }
                
                function Gtabs(){
                    var ul = $("<ul />");
                    var divs = $('<div />');
                    for(j in  pgroups){
                        ul.append($('<li />').append($('<a />').attr({'href':'#grp'+j}).html( pgroups[j].title))
                        .append('<span class="ui-icon ui-icon-close">X</span>'))
                        var div = $('<div />').attr({'id':'grp'+j})
                        for(k in  pgroups[j].ids){
                            var d = $('<div />').attr({'pid': pgroups[j].ids[k],'no':k}).append(
                                $('<input />').attr({'type':'checkbox','class':'propcheck'})
                            ).append((typeof pgroups[j].ids[k]=="undefined" || typeof availableTags[pgroups[j].ids[k]] == "undefined")?"":availableTags[pgroups[j].ids[k]].label);
                            if(typeof  pgroups[j].uni != 'undefined')
                            for(g in  pgroups[j].uni)
                                if(typeof  pgroups[j].uni[g] != 'undefined' &&  pgroups[j].uni[g].ids.indexOf( pgroups[j].ids[k])!=-1){
                                    d.css({'background-color':'#f0fafa'});
                                    break;
                                }
                            div.append(d);
                        }
                        div.append($('<div />').append($('<a />').attr({'class':'x3-button addprop','href':'#add-prop','style':'color:#FFF'}).html('+ Добавить')))
                        divs.append(div);
                    }
                    ul.append($('<li />').css({'float':'right'}).append($('<button />').attr({'type':'button','disabled':'1','class':'trash','primary-icon':'ui-icon-trash','style':'height:24px'})
                        .button({'text':false,icons:{'primary':'ui-icon-trash'},disabled:true})))
                    ul.append($('<li />').css({'float':'right'}).append($('<button />').attr({'type':'button','disabled':'1','class':'plink','primary-icon':'ui-icon-link','style':'height:24px'})
                        .button({'text':false,disabled:true,icons:{'primary':'ui-icon-link'}})))
                    $('#grprops').tabs("destroy").html('').append(ul).append(divs.html());
                    $('#grprops').tabs({tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"}).find( ".ui-tabs-nav" ).sortable({delay:500,stop:function(){
                            var tmp = [];
                            $('#grprops ul li').each(function(){
                                var text = $(this).children('a').text();
                                for(j in  pgroups){
                                    if( pgroups[j].title == text){
                                        tmp.push( pgroups[j]);
                                        break;
                                    }
                                }
                            })
                             pgroups = tmp;
                            savePgroups();
                            Gtabs();
                    }});
                    
                }
                
                function Flist(){
                    var ul = $('#filter .props');
                    ul.html('');
                    for(i in filters){
                        ul.append(
                            $('<li />').attr({pid:i}).append('<span />').append($('<i />').html(filters[i].title))
                            .append($('<a href="#" style="text-decoration:none;float:right;"></a>').html('<img src="/images/cross.png" />')
                            .click(function(){
                                var i = $(this).parent().attr('pid');
                                filters.splice(i,1);
                                $(this).parent().parent().children('li').eq(i).remove();
                                $('#filter-form').html('');
                                saveFilters();
                                Flist();
                                
                            }))
                        );
                    }
                }
                                
                function Ftabs(){
                    var ul = $("<ul />");
                    var divs = $('<div />');
                    for(j in  filters){
                        ul.append($('<li />').append($('<a />').attr({'href':'#fil'+j}).html( filters[j].title))
                        .append('<span class="ui-icon ui-icon-close">X</span>'))
                        var div = $('<div />').attr({'id':'fil'+j})
                        for(k in  filters[j].ids){
                            var d = $('<div />').attr({'pid': filters[j].ids[k],'no':k}).append(
                                $('<input />').attr({'type':'checkbox','class':'propcheck'})
                            ).append((typeof filters[j].ids[k]=="undefined" || typeof availableTags[filters[j].ids[k]] == "undefined")?"":availableTags[filters[j].ids[k]].label);
                            div.append(d);
                        }
                        div.append($('<div />').append($('<a />').attr({'class':'x3-button addprop','href':'#add-filter','style':'color:#FFF'}).html('+ Добавить')))
                        divs.append(div);
                    }
                    ul.append($('<li />').css({'float':'right'}).append($('<button />').attr({'type':'button','disabled':'1','class':'trash','primary-icon':'ui-icon-trash','style':'height:24px'})
                        .button({'text':false,icons:{'primary':'ui-icon-trash'}})))
                    $('#filtergroup').tabs("destroy").html('').append(ul).append(divs.html());
                    $('#filtergroup').tabs({tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"}).find( ".ui-tabs-nav" ).sortable({delay:500,stop:function(){
                            var tmp = [];
                            $('#filtergroup ul li').each(function(){
                                var text = $(this).children('a').text();
                                for(j in  filters){
                                    if( filters[j].title == text){
                                        tmp.push( filters[j]);
                                        break;
                                    }
                                }
                            })
                            filters = tmp;
                            saveFilters();
                            Ftabs();
                    }});
                    
                }
                
                function Utabs(){
                    var ul = $("<ul />");
                    var divs = $('<div />');
                    for(j in pgroups){
                        if(typeof pgroups[j].uni == 'undefined' || pgroups[j].uni.length==0) continue;
                        ul.append($('<li />').append($('<a />').attr({'href':'#uni'+j}).html(pgroups[j].title))
                        .append('<span class="ui-icon ui-icon-close">X</span>'))
                        var div = $('<div />').attr({'id':'uni'+j})
                        for(k in pgroups[j].uni){
                            var d = $('<fieldset />').attr({'no':k,'pg':j,'style':'position:relative'}).append(
                                $('<legend />').append(
                                    $('<input />').css({width:'256px'}).attr({'type':'text','class':'unititle','value':pgroups[j].uni[k].title})
                                ).append(
                                    $('<input />').css({width:'32px'}).attr({'type':'text','class':'unisep','value':(pgroups[j].uni[k].sep == undefined?' ':pgroups[j].uni[k].sep)})
                                )
                            ).append($('<a />').attr({'class':'deluni','href':'#','style':'text-decoration:none;position:absolute;right:5px;top:5px'})
                                .html('<img width="16" src="/images/cross.png" alt="X" />'));    
                            for(s in pgroups[j].uni[k].ids)
                                if(typeof availableTags[pgroups[j].uni[k].ids[s]] != 'undefined')
                                    d.append($('<div />').append(availableTags[pgroups[j].uni[k].ids[s]].label));
                            div.append(d);
                        }
                        
                        div.append($('<div />').css({'clear':'left'}))
                        divs.append(div);
                    }
                    //ul.append($('<li />').css({'float':'right'}).append($('<button />').attr({'type':'button','disabled':'1','class':'trash','primary-icon':'ui-icon-trash','style':'height:24px'})
                        //.button({'text':false,icons:{'primary':'ui-icon-trash'},disabled:true})))
                    //ul.append($('<li />').css({'float':'right'}).append($('<button />').attr({'type':'button','disabled':'1','class':'plink','primary-icon':'ui-icon-link','style':'height:24px'})
                        //.button({'text':false,disabled:true,icons:{'primary':'ui-icon-link'}})))
                    $('#unigroup').tabs("destroy").html('').append(ul).append(divs.html());
                    $('#unigroup').tabs({tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>"});                
                }
                
                function addPropFilter(){
                    var p = [];
                    for(i in filters){
                        p = p.concat(filters[i].ids);
                    }
                    var ts = $('<select />');
                    for(x in availableTags){
                        if(p.indexOf(x)==-1)
                            ts.append($('<option />').attr({'value':x}).html(availableTags[x].label));
                    }
                    if(ts.children('option').length==0) ts = 'Все свойства использованы';
                    $('<div />').attr({'title':'Добавить свойство:','style':'text-align:center'}).append(ts).dialog({
                        modal:true,
                        width:500,
                        buttons:{
                            'Ok':function(){
                                if(typeof ts != 'string'){   
                                    var val = ts.val();
                                    filters.push({title:availableTags[val].label,ids:[val]});
                                    Flist();
                                    saveFilters();
                                }
                                $(this).dialog('destroy');
                            },
                            'Отмена':function(){
                                $(this).dialog('destroy');
                            }
                        }                        
                    });
                }
                
                function add_gr(){
                    var title = prompt('Название группы:');
                    if(title == '' || title == null) return false;
                    pgroups.push({title:title,ids:[]});
                    savePgroups();
                    Gtabs();
                }
                
                $('.suffixF').click(function(){
                    window.selector_name = $(this).attr('for');
                    if(typeof $('#propForm')[0] == 'undefined') {
                        var d = $('<div />').attr({'id':'propForm','title':'Помошник по суффиксам'});
                        d.append($('<button type="button">Производитель</button>').click(function(){var v = $(window.selector_name).val();$(window.selector_name).val(v+' [manufacturer]');}))
                        d.append($('<button type="button">Название товара</button>').click(function(){var v = $(window.selector_name).val();$(window.selector_name).val(v+' [name]');}))
                        d.append($('<button type="button">Артикул</button>').click(function(){var v = $(window.selector_name).val();$(window.selector_name).val(v+' [articule]');}))
                        d.append($('<button type="button">Название группы</button>').click(function(){var v = $(window.selector_name).val();$(window.selector_name).val(v+' [group]');}))
                        $('#propsss .props li').each(function(){
                            var id = $(this).attr('pid');
                            d.append($('<button type="button" pid="'+id+'">'+$(this).children('i').text()+'</button>').click(function(){
                                var v = $(window.selector_name).val();
                                $(window.selector_name).val(v+' ['+$(this).attr('pid')+']');
                            }))
                        })
                        d.dialog({width:'1024px'});
                    }else
                        $('#propForm').dialog('open')
                })
                
          $('#submit button, #submit2 button').click(function(e){
                if(e.ctrlKey)
                    $(this).val("stay");
          })
        </script>        
<script type="text/javascript">
    var GID = <?=$modules->id?>;
    function startj(){
        $('#genmodel').css('display','none');        
        $.get('/uploads/status-models'+GID+'.log',function(m){
            if(m.status == 'idle'){
                if(typeof $('#message')[0] == 'undefined')
                    $('#x3-container').prepend($('<div />').attr({'id':'message'}).progressbar({value:0}))
                $('#message').progressbar('value',m.percent);
                setTimeout(function(){startj();},1000);
            }
        },'json').error(function(){$('#message').fadeOut();$('#genmodel').css('display','inline-block');});
    }
    setTimeout(function(){startj();},500);
</script>
        
    </div>