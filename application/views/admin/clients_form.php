<style type="text/css">
    .formtable td{
        height: 35px;
    }
    .formtable .fieldname{
        width:157px;
        font:20px Tahoma;
        color:#464646;
    }

    .formtable .field input[type="text"], .formtable .field input[type="password"], .formtable .field select {
        display:block;
        font:16px Tahoma;
        border: 1px solid #848181;
        padding:9px;
        width:459px;
    }
    .formtable .required {
        color:#ff0000;
        padding-left: 10px;
    }
    .formtable .error {
        color:#ff0000;
        padding-left: 12px;
        font:12px Tahoma;
        font-style: italic;
    }
</style>
<?php
$form = new X3_Form($model);
$class = strtolower($class);
echo $form->start(array('target'=>$class.'_frame','action'=>"/admin/file/class/$class.html"));
if(!is_null($model->id)){
    echo $form->hidden('id');
}
echo $form->hidden('created_at');
?>
<iframe style="width:0px;height:0px;position:absolute;left:-1000px;z-index:-1" name="<?=$class?>_frame" id="<?=$class?>_frame"></iframe>
<table class="formtable">
    <tr id="<?=$class?>-title">
        <td class="fieldname"><?=$model->fieldName('title')?></td>
        <td class="field"><?=$form->input('title')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-url">
        <td class="fieldname"><?=$model->fieldName('url')?></td>
        <td class="field"><?=$form->input('url')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-image">
        <td class="fieldname"><?=$model->fieldName('image')?></td>
        <td class="field"><?=$form->file('image')?></td>
        <td class="required"></td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-content">
        <td class="fieldname"><?=$model->fieldName('content')?></td>
        <td class="field"><?=$form->textarea('content')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-weight">
        <td class="fieldname"><?=$model->fieldName('weight')?></td>
        <td class="field"><?=$form->input('weight')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-status">
        <td class="fieldname"><?=$model->fieldName('status')?></td>
        <td class="field"><?=$form->checkbox('status')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
</table>
<?=$form->end();?>
<script type="text/javascript">
    $("#<?=$class?>_frame").load(function(){
        var html = $(this).contents().find('body').html();
        var mod = '<?=$class?>';
        var m = eval('('+html+')');
        if(m.status=='OK'){
            callback=null;
            call_list(mod);
        }else{
            $('[id^="'+mod+'-"] .error').html('');
            for(var i in m.errors){
                $('#'+mod+'-'+i+' .error').html(m.errors[i][0]);
            }
            var errs = panel.scape.data.buttons.children('.errors');
            if(typeof errs != 'undefined') errs.remove();
            panel.scape.data.buttons.append($('<div />').addClass('errors')
            .css({'float':'right','font-style':'italic','font-size':'12px','color':'#f00','margin-right':'120px'})
            .html((m.errors.db!=null)?m.errors.db:'Проверте пожалуйста правильность заполнения!'));
        }
    });
callback = function(mod){
    $('#'+mod+'-form').submit();
    return false;
}
</script>