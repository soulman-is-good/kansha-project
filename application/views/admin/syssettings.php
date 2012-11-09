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
<div class="module-menu-list">
<?
if(!empty($models)):
//$group = current($models);
//$group = $group->group;
$group = "";
$j=0;
foreach ($models as $i=>$model):
?>
<?if($group!=$model->group):$j=$i;?>
    <div class="list-item" id="sys-item-<?=$j?>">
        <div class="trig" style="width:10px"><i></i></div>
        <div class="title"><?=$model->group?></div>
    </div>
<?endif;?>
    <div parent-id="sys-item-<?=$j?>" class="list-item" id="syssettings-item-<?=$model->id?>" style="display:none">
        <div class="trig" style="width:40px">&nbsp;</div>
        <div class="title"><?=$model->title?></div>
        <div class="options">
                    <a href="#edit" onclick="call_edit(<?=$model->id?>,'<?=$class?>');return false;"><img alt="edit" src="/images/admin/edit.gif" title="Редактировать" /></a>
        </div>
    </div>
<?if($group!=$model->group)$group=$model->group;?>
<? endforeach;?>
<?endif;?>
</div>
<script type="text/javascript">
    $('.trig i').click(function(){
        $('[parent-id="'+$(this).parent().parent().attr('id')+'"]').slideToggle();
        $(this).toggleClass('active')
    })
</script>