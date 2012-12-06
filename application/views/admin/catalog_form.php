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
$pos = array(
    array('value'=>'0','%content'=>'Нет'),
    array('value'=>'1','%content'=>'(1)'),
    array('value'=>'2','%content'=>'(2)'),
    array('value'=>'3','%content'=>'(3)'),
    array('value'=>'4','%content'=>'(4)'),
    array('value'=>'5','%content'=>'(5)'),
    array('value'=>'6','%content'=>'(6)'),
    array('value'=>'7','%content'=>'(7)'),
    array('value'=>'8','%content'=>'(8)'),
    array('value'=>'9','%content'=>'(9)'),
    array('value'=>'10','%content'=>'(10)'),
    array('value'=>'11','%content'=>'(11)'),
    array('value'=>'12','%content'=>'(12)'),
    array('value'=>'13','%content'=>'(13)'),
);
echo $form->start(array('target'=>$class.'_frame','action'=>"/admin/file/class/$class.html"));
if(!is_null($model->id)){
    echo $form->hidden('id');
}
$pos[$model->bmain]['selected'] = "selected";
echo $form->hidden('created_at');
echo X3_Html::form_tag('input',array('type'=>'hidden','name'=>'Catalog[section_id]','value'=>(int)$_POST['section_id']));
?>
<iframe style="width:0px;height:0px;position:absolute;left:-1000px;z-index:-1" name="<?=$class?>_frame" id="<?=$class?>_frame"></iframe>
<table class="formtable">
    <tr id="<?=$class?>-title">
        <td class="fieldname"><?=$model->fieldName('title')?></td>
        <td class="field"><?=$form->input('title')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-image">
        <td class="fieldname"><?=$model->fieldName('image')?></td>
        <td class="field"><?=$form->file('image')?></td>
        <td class="required"></td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-text">
        <td class="fieldname"><?=$model->fieldName('text')?></td>
        <td class="field"><?=$form->textarea('text',array('rows'=>7,'cols'=>150))?></td>
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
    <tr id="<?=$class?>-isnew">
        <td class="fieldname"><?=$model->fieldName('isnew')?></td>
        <td class="field"><?=$form->checkbox('isnew')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-bmain">
        <td class="fieldname"><?=$model->fieldName('bmain')?></td>
        <td class="field"><?=$form->select($pos,array('name'=>'Catalog[bmain]','id'=>'Catalog_bmain'))?></td>
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
            call_list('section');
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

    $("#Catalog_text").redactor();

</script>