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
$ops = array(array('%content'=>'Нет','value'=>'0'));
$mdls = Page::get(array('parent_id'=>'NULL'));
foreach($mdls as $mdl){
    if($mdl->id==$model->id) continue;
    if($mdl->id==$model->parent_id)
       $ops[]=array('value'=>$mdl->id,'%content'=>$mdl->title,'selected'=>'selected');
    else
       $ops[]=array('value'=>$mdl->id,'%content'=>$mdl->title);
}
echo $form->start();
if(!is_null($model->id))
    echo $form->hidden('id');
?>
<table class="formtable">
    <tr id="<?=strtolower($class)?>-parent_id">
        <td class="fieldname"><?=$model->fieldName('parent_id')?></td>
        <td class="field"><?=$form->select($ops,array('id'=>'Page_parent_id','name'=>'Page[parent_id]'))?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-title">
        <td class="fieldname"><?=$model->fieldName('title')?></td>
        <td class="field"><?=$form->input('title')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-name">
        <td class="fieldname"><?=$model->fieldName('name')?></td>
        <td class="field"><?=$form->input('name')?></td>
        <td class="required">*</td>
        <td class="error">&nbsp;</td>
    </tr>
    <tr id="<?=strtolower($class)?>-text">
        <td class="fieldname"><?=$model->fieldName('text')?></td>
        <td class="field"><?=$form->textarea('text',array('cols'=>'150','style'=>'width:700px'))?></td>
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
<script type="text/javascript">
    $("#Page_text").redactor();
</script>