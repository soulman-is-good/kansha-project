<style type="text/css">
    .module-menu-header {
        height:10px;
        font:9px Tahoma;
        color:#4d357d;
    }
    .module-menu-header div {
        float:left;
    }
    .module-menu-list{position: relative;clear:left;}
    .module-menu-list .list-item {
        height: 35px;
        clear: left;
        overflow: hidden;
    }
    .module-menu-list .list-item div{
        float:left;
    }
    .module-menu-list .list-item .status {
        width:55px;
        padding-top: 8px;
    }
    .module-menu-list .list-item .title {
        width:265px;
        padding-left:20px;
        font:20px Tahoma;
        color:#5c5c5c;
    }
</style>
<div class="module-menu-header">
    <div style="width:55px">Видимость</div>
    <div style="width:265px;padding-left: 20px">Заголовок</div>
    <div style="">Опции</div>
</div>
<div class="module-menu-list">
    <? foreach ($models as $model): ?>
    <div class="list-item" id="menu-item-<?=$model->id?>">
        <div class="status"><div ondblclick="status(<?=$model->id?>,'<?=$class?>',this);return false;" class="admin-status-<?=$model->status?>">&nbsp;</div></div>
        <div class="title"><?=$model->title?></div>
        <div class="options">
                    <a href="#edit" onclick="call_edit(<?=$model->id?>,'<?=$class?>');return false;"><img alt="edit" src="/images/admin/edit.gif" title="Редактировать" /></a>
                    <a href="#delete" onclick="_deletelist('<?=$model->id?>','<?=$class?>')"><img src="/images/admin/delete.gif" alt="delete" title="Удалить" /></a>
        </div>
    </div>
    <? endforeach; ?>
</div>