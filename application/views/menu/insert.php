<?php
    $form = new X3_Form($model);
?>

    <?=$form->start()?>
    <div style="">
        <label>Заголовок</label>
        <?=$form->input('title')?>
    </div>
    <div style="">
        <label>Текст</label>
        <?=$form->textarea('text')?>
    </div>
    <div style="">
        <label>Дата создания</label>
        <?=$form->input('created_at')?>
    </div>
    <?=$form->select('parent_id',array('%select'=>array('id','title','parent_id IS NULL')));//array(array('%content'=>'ok','value'=>'1'),array('%content'=>'ok1','value'=>'2'),array('%content'=>'ok2','value'=>'3')));?>
    <?=$form->input('OK',array('type'=>'submit'))?>
    <?=$form->end()?>