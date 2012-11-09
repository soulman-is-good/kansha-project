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
$roles = array(
    '*'=>array('value'=>'*','%content'=>'Все'),
    'user'=>array('value'=>'user','%content'=>'Только покупатель'),
    'admin'=>array('value'=>'admin','%content'=>'Только администратор'),
    'sale'=>array('value'=>'sale','%content'=>'Только продавец'),
    'user+sale'=>array('value'=>'user+sale','%content'=>'Покупатель и продавец'),
    'admin+sale'=>array('value'=>'admin+sale','%content'=>'Администратор и продавец'),
    'admin+user'=>array('value'=>'admin+user','%content'=>'Администратор и покупатель'),
);
$roles[$model->role]['selected']="selected";
$form = new X3_Form($model);
echo $form->start();
if(!is_null($model->id))
    echo $form->hidden('id');
?>
<table class="formtable">
    <tr id="<?=strtolower($class)?>-title">
        <td class="fieldname"><?=$model->fieldName('title')?></td>
        <td class="field"><?=$form->input('title')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-link">
        <td class="fieldname"><?=$model->fieldName('link')?></td>
        <td class="field"><?=$form->input('link')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-role">
        <td class="fieldname"><?=$model->fieldName('role')?></td>
        <td class="field"><?=$form->select($roles,array('id'=>'Menu_role','name'=>'Menu[role]'))?></td>
        <td class="required"></td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-weight">
        <td class="fieldname"><?=$model->fieldName('weight')?></td>
        <td class="field"><?=$form->input('weight')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-status">
        <td class="fieldname"><?=$model->fieldName('status')?></td>
        <td class="field"><?=$form->checkbox('status')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
</table>
<?=$form->end();?>