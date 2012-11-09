<style type="text/css">
    .formtable td{
        height: 35px;
    }
    .formtable .fieldname{
        width:157px;
        font:20px Tahoma;
        color:#464646;
    }

    .formtable .field input[type="text"] {
        display:block;
        font:16px Tahoma;
        border: 1px solid #848181;
        padding:9px;
        width:459px;
    }
    .formtable .field textarea {
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
echo $form->start();
echo $form->hidden('id');
echo $form->hidden('name');
echo $form->hidden('title');
echo $form->hidden('type');
echo $form->hidden('group');
?>
<table class="formtable">
    <tr id="<?=strtolower($class)?>-value">
        <td class="fieldname"><?=$model->fieldName('value')?></td>
        <td class="field"><?=$form->textarea('value')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
</table>
<?=$form->end();?>