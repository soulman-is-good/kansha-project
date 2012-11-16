<style>
    table.formtable td{
        text-align: left;
    }

    table td.fieldname{
        width:200px
    }
    table td{
        text-align: left;
    }
    table td input[type="text"], table td select, table td textarea{
        width:100%;
    }     
</style>
<?php
$form = new Form($modules);
?>
<div class="x3-submenu">
    <a href="/admin/shop">Товары</a> :: <a href="/admin/shopcategory">Группы</a> :: <span>Категории</span>
</div>
<div class="x3-main-content" style="">
    <div id="x3-buttons" x3-layout="buttons"></div>
    <div id="x3-header" x3-layout="header"><a href="/admin/<?= $action ?>">Категории</a> > <? if ($subaction == 'add'): ?>Добавить
        <? else: ?>
            Редактировать<? endif; ?>
    </div>
    <div class="x3-paginator" x3-layout="paginator"></div>
    <div class="x3-functional" x3-layout="functional"></div>
    <div id="x3-container"> 
        <div style="padding:10px;clear:both;">                
            <?= $form->start(array('action' => '/admin/shopcategorysave')); ?>
            <? if ($subaction == 'edit'): ?>
                <input name="Shop_Category[id]" type="hidden" value="<?= $modules->id ?>" />
            <? endif; ?>                
            <table class="formtable">
                <tr id="<?= $class ?>-title">
                    <td class="fieldname"><?= $modules->fieldName('image') ?></td>
                    <td class="field"><?= $form->file('image') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-title">
                    <td class="fieldname"><?= $modules->fieldName('title') ?></td>
                    <td class="field"><?= $form->input('title') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-content">
                    <td class="fieldname"><?= $modules->fieldName('text') ?></td>
                    <td class="field"><?= $form->textarea('text') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-weight">
                    <td class="fieldname"><?= $modules->fieldName('weight') ?></td>
                    <td class="field"><?= $form->input('weight') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-status">
                    <td class="fieldname"><?= $modules->fieldName('status') ?></td>
                    <td class="field"><?= $form->checkbox('status') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-metatitle">
                    <td class="fieldname"><?= $modules->fieldName('metatitle') ?></td>
                    <td class="field"><?= $form->input('metatitle') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-metakeywords">
                    <td class="fieldname"><?= $modules->fieldName('metakeywords') ?></td>
                    <td class="field"><?= $form->textarea('metakeywords') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="<?= $class ?>-metadescription">
                    <td class="fieldname"><?= $modules->fieldName('metadescription') ?></td>
                    <td class="field"><?= $form->textarea('metadescription') ?></td>
                    <td class="required">*</td>
                    <td class="error">&nbsp;</td>
                </tr>
                <tr id="submit">
                    <td colspan="4"><input type="submit" value="Сохранить" /></td>
                </tr>
            </table>
            <?= $form->end(); ?>
        </div>
    </div>
</div>
<script>
    if(typeof CKEDITOR.instances['Shop_Category_text'] != 'undefined')
        delete(CKEDITOR.instances['Shop_Category_text']);
    CKEDITOR.replace( 'Shop_Category_text' );
</script>