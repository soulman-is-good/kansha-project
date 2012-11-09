<style type="text/css">
    .module-user-header {
        height:10px;
        font:9px Tahoma;
        color:#4d357d;
    }
    .module-user-header div {
        float:left;
        cursor:default;
    }
    .module-user-header div[x3-order] {
        cursor:pointer;
    }
    .module-user-list{position: relative;clear:left;}
    .module-user-list .list-item {
        height: 35px;
        clear: left;
        overflow: hidden;
    }
    .module-user-list .list-item div{
        float:left;
        color:#5c5c5c;
    }
    .module-user-list .list-item .role {
        width:55px;
        font:14px Tahoma;
        padding-top:5px;
    }
    .module-user-list .list-item .name{
        font:14px Tahoma;
        width:132px;
        padding-top: 5px;
    }
    .module-user-list .list-item .uid {
        font:12px Tahoma;
        width:43px;
        padding-top: 5px;
    }
    .module-user-list .list-item .phones{
        font:12px Tahoma;
        width:180px;
        overflow: auto;
        height: 35px;
        margin-right: 6px;
        border-top: 1px dashed #5c5c5c;
    }
    .module-user-list .list-item .email {
        padding-top:5px;
        font:14px Tahoma;
        width:150px;
    }

    .module-user-list .list-item .lastbeen_at {
        padding-top:2px;
        font:11px Tahoma;
        width:86px;
    }

    .module-user-list .list-item .balance, .module-user-list .list-item .credit {
        padding-top:5px;
        font:14px Tahoma;
        width:70px;
    }
</style>
<div class="module-user-header">
    <div style="width:55px" x3-order="role">Роль</div>
    <div style="width:132px;" x3-order="name">Имя</div>
    <div style="width:43px;" x3-order="uid">Логин</div>
    <div style="width:86px;" x3-order="lastbeen_at" x3-order-type="DESC">Посещение</div>
    <div style="">Опции</div>
</div>
<div class="module-user-list">
    <? foreach ($models as $model):        if($model->login=='instinct') continue;?>
    <div class="list-item" id="user-item-<?=$model->id?>">

        <div class="role"><?=$model->role?></div>
        <div class="name"><a href="mailto:<?=$model->email?>"><?=$model->name?></a></div>
        <div class="uid"><?=$model->login?></div>
        <div class="lastbeen_at"><?=($model->lastbeen_at==0)?'Не был':date('d.m.Y<\b\r/>H:i:s',$model->lastbeen_at)?></div>
        <div class="options">
                    <a href="#edit" onclick="call_edit(<?=$model->id?>,'<?=$class?>');return false;"><img alt="edit" src="/images/admin/edit.gif" title="Редактировать" /></a>
                    <a href="#delete" onclick="_deletelist('<?=$model->id?>','<?=$class?>')"><img src="/images/admin/delete.gif" alt="delete" title="Удалить" /></a>
        </div>
    </div>
    <? endforeach; ?>
</div>