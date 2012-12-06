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
    .module-menu-list .list-item .trig {
        width:20px;
        padding-top: 8px;
        text-align: center;
    }
    .module-menu-list .list-item .trig i{
        font-size:0px;
        cursor:pointer;
        border:5px solid transparent;
        border-left-color:#000;
    }
    .module-menu-list .list-item .trig i.active{
        border-left-color:transparent;
        border-top-color:#000;
    }
    .module-menu-list .list-item .status {
        width:55px;
        padding: 8px 0 0 20px;
    }
    .module-menu-list .list-item .title {
        width:265px;
        padding-left:20px;
        font:20px Tahoma;
        color:#5c5c5c;
    }
</style>
<div class="module-menu-header">
    <div style="width:65px;padding-left:20px">Видимость</div>
    <div style="width:265px;padding-left: 20px">Заголовок</div>
    <div style="">Опции</div>
</div>
<div class="module-menu-list">
    <? foreach ($models as $model): 
         $childs = Catalog::get(array('section_id'=>$model->id));
        ?>
    <div class="list-item" id="section-item-<?=$model->id?>">
        <div class="trig" style="width:10px"><?if(!empty($childs)):?><i></i><?endif?></div>
        <div class="status"><div ondblclick="status(<?=$model->id?>,'<?=$class?>',this);return false;" class="admin-status-<?=$model->status?>">&nbsp;</div></div>
        <div class="title"><?=$model->title?></div>
        <div class="options">
                    <a href="#edit" onclick="call_edit(<?=$model->id?>,'<?=$class?>');return false;"><img alt="edit" src="/images/admin/edit.gif" title="Редактировать" /></a>
                    <a href="#delete" onclick="_deletelist('<?=$model->id?>','<?=$class?>')"><img src="/images/admin/delete.gif" alt="delete" title="Удалить" /></a>
                    <a href="#add" onclick="call_cat('<?=$model->id?>','catalog','section')"><img src="/images/admin/play.gif" alt="Add" title="Добавить" /></a>
        </div>
    </div>
    <? foreach ($childs as $child): ?>
    <div parent-id="section-item-<?=$child->section_id?>" class="list-item" id="catalog-item-<?=$child->id?>" style="display:none">
        <div class="trig" style="width:40px">&nbsp;</div>
        <div class="status"><div ondblclick="status(<?=$child->id?>,'<?=$class?>',this);return false;" class="admin-status-<?=$child->status?>">&nbsp;</div></div>
        <div class="title"><?=$child->title?></div>
        <div class="options">
                    <a href="#edit" onclick="call_edit('<?=$child->id?>','catalog',{'section_id':<?=$model->id?>},'section');return false;"><img alt="edit" src="/images/admin/edit.gif" title="Редактировать" /></a>
                    <a href="#delete" onclick="_deletelist('<?=$child->id?>','catalog')"><img src="/images/admin/delete.gif" alt="delete" title="Удалить" /></a>
        </div>
    </div>
    <? endforeach; ?>
    <? endforeach; ?>
</div>
<script type="text/javascript">
    $('.trig i').click(function(){
        $('[parent-id="'+$(this).parent().parent().attr('id')+'"]').slideToggle();
        $(this).toggleClass('active')
    })
</script>