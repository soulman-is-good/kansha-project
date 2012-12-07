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
    .list-item {
        width: 107px;
        background: #660066;
        float: left;
        overflow: hidden;
        position: relative;
        margin-right: 10px;
    }
    .list-item div{
        color:#5c5c5c;
    }
    .list-item .name{
        font:12px Tahoma;
        color:#fff;
        padding: 2px;
        text-align: center;
    }
    .list-item .lastbeen_at {
        /*position:absolute;
        bottom:26px;
        right:0px;*/
        text-align: center;
        padding:2px;
        font:10px Tahoma;
        background: #660066;
        color:#fff;
    }
    .ola{position: relative}
    .ola .options{
        display:none;
        position:absolute;
        right:0px;
        top:0px;
        width:30px;
    }
    .ola:hover .options{
        display:block;
    }

</style>
    <?
    if(isset($_GET['id']))
        X3::app()->user->clients_id = (int)$_GET['id'];
    $models = Photo::newInstance()->table->select('*')->where('clients_id='.X3::app()->user->clients_id)->order('created_at DESC')->asObject();
    foreach ($models as $model): ?>
    <div class="list-item" id="photo-item-<?=$model->id?>">
        <div class="lastbeen_at"><?=($model->created_at==0)?'-':date('d.m.Y H:i:s',$model->created_at)?></div>
        <div class="ola" style="background:url(/uploads/Photo/<?=$model->image?>) no-repeat 50% 50%;width: 107px;height: 73px;">
            <div class="options">
                    <a href="#edit" onclick="call_edit(<?=$model->id?>,'<?=$class?>');return false;"><img alt="edit" src="/images/admin/edit.gif" title="Редактировать" /></a>
                    <a href="#delete" onclick="_deletelist('<?=$model->id?>','<?=$class?>')"><img src="/images/admin/delete.gif" alt="delete" title="Удалить" /></a>                    
            </div>
        </div>
        <div class="name"><?=$model->title?></div>
        
    </div>
    <? endforeach; ?>

