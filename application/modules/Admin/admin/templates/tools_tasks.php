<?
//var_dump(X3::db()->fetchAll("SELECT * FROM data_informer WHERE category='tasks'"));exit;
?>
<div class="x3-submenu">
    <?=$modules->menu('/admin/tools/tasks')?>
</div>
<?/*STANDART TEMPLATE*/
$users = User::get();
$_tmpUser = array();
?>
<style>
    .formtable {width:100%}
</style>
<div class="x3-main-content">
    
<div id="x3-header" x3-layout="header">Задания
    <div id="x3-buttons" x3-layout="buttons"></div>
</div>
<div class="x3-functional" x3-layout="functional"></div>
<div class="clear-both">&nbsp;</div>

<div id="x3-container">
<ul>
    <?foreach($users as $user):$_tmpUser[$user->id]=$user->name?>
    <li><a href="#user<?=$user->id?>"><?=$user->name?></a></li>
    <?endforeach;?>
</ul>
<?foreach($users as $user):?>
<div id="user<?=$user->id?>">
<table>
<tr>
    <th><input type="checkbox" id="check-deleteall" onclick="$('[id^=\'delete-\']').each(function(){$(this).attr('checked',$('#check-deleteall').is(':checked'))})" /></th>
    <th class="col-status">Выполнено</th>
    <th class="col-message">Задание</th>
    <th colspan="2"></th>
</tr>
<?
$informers = Informer::get(array('@condition'=>array("category"=>'tasks',"message"=>array('LIKE'=>"'%\"user_id\":\"$user->id\"%'")),'@order'=>"created_at DESC"));
foreach ($informers as $info):
    $tmp = json_decode($info->message);
    ?>
    <tr>
        <td <?if($info->status):?>style="background: #c0f177"<?endif;?>><input type="checkbox" id="delete-<?=$module->id?>" value="<?=$module->id?>" name="Delete[<?=$class?>][]" /></td>
        <td <?if($info->status):?>style="background: #c0f177"<?endif;?> class="row-status">
            <?if($info->status):?>
            <span style="display:inline-block" class="ui-widget-content ui-corner-all ui-icon ui-icon-flag">&nbsp;</span>
            <?else:?>
            <span>&nbsp;</span>
            <?endif;?>
        </td>
        <td <?if($info->status):?>style="background: #c0f177"<?endif;?> class="row-user_id">
            <?=$tmp->message?>
        </td>    
        <td <?if($info->status):?>style="background: #c0f177"<?endif;?> class="action-edit">
            <a href="/admin/tools/tasks?edit=<?=$info->id?>"><img src="/application/modules/Admin/images/buttons/edit.png" /></a>
        </td>
        <td <?if($info->status):?>style="background: #c0f177"<?endif;?> class="action-delete">
            <a href="/admin/tools/tasks?delete=<?=$info->id?>"><img src="/application/modules/Admin/images/buttons/delete.png" /></a>
        </td>
    </tr>    
<?endforeach;?>
</table>
    <div style="margin-top:25px;">
        <?php
        if($informer->user_id == $user->id)
            $inf = $informer;
        else
            $inf = new Informer;
        $form = new Form($inf);
        echo $form->start(array('method'=>'post','action'=>'/admin/tools/tasks'));
        if(!$inf->getTable()->getIsNewRecord())
            echo $form->hidden('id');
        ?>        
        <input type="hidden" name="Informer[user_id]" value="<?=$user->id?>" />
        <input type="hidden" name="Informer[category]" value="tasks" />
        <?$errors = $inf->getTable()->getErrors();if(!empty($errors)):?>
        <ul style="color: #c71f1f">
        <?php
        foreach($errors as $errs)
            foreach($errs as $err)
                echo "<li>{$err}</li>";
        ?>
        </ul>
        <?endif;?>
        <table class="formtable">
            <thead><?=$inf->getTable()->getIsNewRecord()?'Добавить':'Редактировать'?> задание для <?=$user->name?></thead>
            <?=$form->renderPartial(array('message'=>'Задание'))?>
            <tr id="submit">
                <td colspan="3"><button name="take" type="submit">Сохранить</button></td>
            </tr>            
        </table>
        <?=$form->end();?>
    </div>
</div>
<?endforeach;?>
</div>
</div>
<script type="text/javascript">
    $('#x3-container').tabs();
    $('.sysedit').each(function(){
        $(this).click(function(){
            var self = $(this);
            var href = self.attr('href');
            var txt = self.text();
            $.get(href,function(m){
                var form = $(m).find('form').wrap('<div title="'+txt+'" id="dialog-sett"></div>').parent();
                var script = form.find('script');
                form.find('textarea').css('width','100%');
                form.find('form').submit(function(){
                    $.post($(this).attr('action'),$(this).serialize(),function(m){
                        if(m=='OK'){
                            $.growl('Сохранено','ok');
                        }else
                            $.growl('Ошибка!');
                        $("#dialog-sett").dialog('close');
                    })
                    return false;
                })
                form.dialog({modal:true,width:400,open:function(){$('body').append(script)},close:function(){script.remove();$(this).dialog('destroy')}})
            })
            return false;
        })
    })
</script>