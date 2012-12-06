<div class="x3-main-content">
<style>
    table {
        width:100%;
    }
    table td:first-child{
           width:100px;
    }
    table td{
        text-align: left;
    }
    table td input[type="text"],table.formtable td select{
        width:100%;
    }
    table td textarea{
        width:100%;
    }
</style>
<?/*STANDART TEMPLATE*/
if($modules instanceOf X3_Module_Table):
$groups = array(array('id'=>0,'pid'=>0,'title'=>'Не выбрано'));
$qg = X3::db()->query("SELECT id, title FROM shop_group ORDER BY title");
while($gr = mysql_fetch_assoc($qg)){
    $prs = X3::db()->query("SELECT spl.id, spl.title FROM shop_proplist spl INNER JOIN shop_properties sp ON spl.property_id=sp.id WHERE sp.isgroup AND sp.group_id='{$gr['id']}' GROUP BY spl.id ORDER BY spl.title");
    if(is_resource($prs) && mysql_num_rows($prs)>0)
        while($pr = mysql_fetch_assoc($prs)){
            $groups[] = array('id'=>$gr['id'],'pid'=>$pr['id'],'title'=>$pr['title']);
        }
    $groups[] = array('id'=>$gr['id'],'pid'=>0,'title'=>$pr['title']);
}
    
$form = new Form($modules);
?>
<div id="x3-header" x3-layout="header"><a href="/admin/<?=$action?>"><?=$moduleTitle;?></a> > <?if($subaction=='add'):?>Добавить<?else:?>Редактировать<?endif;?></div>
<div class="x3-paginator" x3-layout="paginator"></div>
<div class="x3-functional" x3-layout="functional"></div>
<div class="x3-main-content" style="clear:left">
<?=$form->start(array('action'=>"/admin/{$action}/save"));?>
<?
$errs = $modules->table->getErrors();
if(X3::db()->getErrors()!='' || !empty($errs)):?>
    <div class="x3-errors">
        <ul>
            <?foreach($errs as $err)
                foreach($err as $er):?>
            <li><?=$er?></li>
            <?endforeach;?>
            <li><?=X3::db()->getErrors()?></li>
        </ul>
    </div>
<?endif;
echo $form->hidden('property_id');
echo $form->render();?>
</div>
    <script type="text/javascript">
        var groups = <?=json_encode($groups)?>;
        var group_name = $('<a href="#" style="margin:4px 0" />');
        $(function(){
            var div = $('<div />').attr({'title':'Выберите раздел'}).css({'overflow':'auto'})
            for(i in groups){
                var gr = groups[i];
                var a = $('<a />').css({
                    'display':'block',
                    'margin':'0 7px 10px 7px'
                }).attr({'href':'#'}).data('group',gr).html(gr.title);
                a.click(function(e){
                    $('#Sale_group_id').val($(this).data('group').id)
                    $('#Sale_property_id').val($(this).data('group').pid)
                    group_name.html($(this).data('group').title);
                    div.dialog('close');
                    return false;
                })
                div.append(a);
            }
            var curg = $('#Sale_group_id').val();
            var curp = $('#Sale_property_id').val();
            curp = curp==''?0:curp;
            group_name.insertAfter($('#Sale_group_id').css({'visibility':'hidden','position':'absolute','left':'-999px'}));
            for(i in groups){
                if(groups[i].id == curg && (curp==0 || groups[i].pid == curp)){
                    group_name.html(groups[i].title);
                    break;
                }
            }
            group_name.click(function(){
                div.dialog({width:500,height:600})
            })
        })
    </script>
<?endif;?>
</div>