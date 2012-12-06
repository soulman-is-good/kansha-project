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
$roles = array(
    'user'=>array('value'=>'user','%content'=>'Покупатель'),
    'admin'=>array('value'=>'admin','%content'=>'Администратор'),
);
$roles[$model->role]['selected']="selected";
$form = new X3_Form($model);
echo $form->start();
if(!is_null($model->id)){
    echo $form->hidden('id');
}
echo $form->hidden('lastbeen_at');
$class = strtolower($class);
?>
<table class="formtable">
    <tr id="<?=$class?>-name">
        <td class="fieldname"><?=$model->fieldName('name')?></td>
        <td class="field"><?=$form->input('name')?></td>
        <td class="required"></td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-login">
        <td class="fieldname"><?=$model->fieldName('login')?></td>
        <td class="field"><?=$form->input('login')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-email">
        <td class="fieldname"><?=$model->fieldName('email')?></td>
        <td class="field"><?=$form->input('email')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-password">
        <td class="fieldname"><?=$model->fieldName('password')?></td>
        <td class="field"><input name="User[password]" id="User_password" type="password" /></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=$class?>-role">
        <td class="fieldname"><?=$model->fieldName('role')?></td>
        <td class="field"><?=$form->select($roles,array('id'=>'User_role','name'=>'User[role]'))?></td>
        <td class="required"></td>
        <td class="error">&nbsp;</td>
    </tr>
</table>
<?=$form->end();?>