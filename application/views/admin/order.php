<?php
$till = X3::app()->db->fetch("SELECT MAX(created_at) FROM data_order");
$till = array_shift($till)+86400;
$from = $till-date("t")*86400;
$q = "";
if(isset($_GET['from'])) $from = strtotime($_GET['from']);
if(isset($_GET['till'])) $till = strtotime($_GET['till']);
if(isset($_GET['users'])) $q = " AND user_id IN ('".implode("','",$_GET['users'])."')";
$models = Order::newInstance()->table->select('*')->where("created_at>=$from AND created_at<=$till".$q)->order('submitted AND created_at')->asObject();
$users = X3::app()->db->fetchAll("SELECT u.id,uid FROM data_user u INNER JOIN data_order o ON o.user_id = u.id GROUP BY uid");
?>
<style type="text/css">
.head td {
        background: #cccccc;
}
.filter-admin {
    border-bottom:1px solid #660066;
    padding:5px 0px;
}
.filter-admin input {
    border:1px solid #cccccc;
    font:12px Verdana;
    padding:2px;
    width:78px;
}
.deliver{
    background: url(/images/spryte-ico.png) no-repeat;
    display: inline-block;
}
.deliver.fly{
    width:24px;
    height:24px;
    background-position: -300px -97px
}
.deliver.cont{
    width:24px;
    height:24px;
    background-position: -250px -97px
}
.deliver.status0 {
    background:none;
    width:16px;
    height:22px;
}
.deliver.status1 {
    width:16px;
    height:22px;
    background-position: 0px -92px
}
.deliver.status2 {
    width:16px;
    height:22px;
    background-position: -50px -92px
}
.deliver.status3 {
    width:16px;
    height:22px;
    background-position: -100px -92px
}
.deliver.status4 {
    width:16px;
    height:22px;
    background-position: -150px -92px
}
</style>
<div class="filter-admin">
    <div style="float:right;">
        С выделенными: <a href="#" onclick="update_status();return false;" style="padding:2px;background:#BFB2D9;color:#FFF;text-decoration: none">Изменить статус</a>
    </div>
    <form id="filter">
        Фильтр: от <input type="text" value="<?=date("d.m.Y",$from)?>" id="from" name="from" /> до <input type="text" value="<?=date("d.m.Y",$till)?>" id="till" name="till" />
        <a href="#" style="margin: 0 5px; text-decoration:none;border-bottom: 1px dashed #660066;color:#660066" onclick="$(this).siblings('div').slideToggle();return false;">Пользователи</a>
        <div style="position:absolute;width:120px;background: #BFB2D9;display:none;border: 1px solid #cccccc;line-height: 18px;padding: 5px;left:280px">
            <? foreach ($users as $u): ?>
            <input <?=(strpos($q,"'{$u['id']}'")!==false)?'checked="checked"':''?> style="width:16px;padding-left:6px" type="checkbox" name="users[]" value="<?=$u['id']?>" /><?=$u['uid']?><br/>
            <? endforeach; ?>
        </div>
        <input type="button" value="Ок" onclick="filterform()" />
        <img id="load" src="/images/loader.gif" style="display:none;" />
    </form>
</div>
<table style="width:100%">
    <tr class="head">
        <td><input type="checkbox" onclick="checkall(this);" /></td>
        <td>Item#</td>
        <td>UID</td>
        <td>Марка</td>
        <td>Партномер</td>
        <td>Название</td>
        <td>Кол-во</td>
        <td>Вес</td>
        <td>Цена</td>
        <td>Сумма</td>
        <td>Склад</td>
        <td>Срок</td>
        <td style="width:250px">Комментарий</td>
        <td>N</td>
        <td>B</td>
        <td>&nbsp;</td>
    </tr>
    <?if(empty($models)):?>
    <tr>
        <td colspan="15" style="text-align:center">Нет записей за данный период</td>
    </tr>
    <?else:        
    foreach ($models as $model):        
        if(NULL!==($item = $model->item_id())){
            $maker = Maker::newInstance()->table->where('id='.$item->maker_id)->asObject(true)->title;
            $partno = $item->partno;
            $title = $item->title;
        }else{
            $title="-";
            $partno="-";
            $maker="-";
        }        
        if(NULL!==($store = $model->store_id())){
            $store = $store->title;
        }else
            $store = "-";
    ?>
    <tr class="body" status="<?=$model->status?>">
        <td><?=($model->submitted)?'<input disabled type="checkbox" checked="checked"  />':'<input oid="'.$model->id.'" type="checkbox"  />'?></td>
        <td><?=$model->id?></td>
        <td><?=$model->user_id()->uid?></td>
        <td><?=$maker?></td>
        <td><?=$partno?></td>
        <td><?=$title?></td>
        <td><?=$model->count?></td>
        <td><?=$model->weight?></td>
        <td><?=$model->price?></td>
        <td><?=$model->count*$model->price?></td>
        <td><?=$store?></td>
        <td><?=$model->time?></td>
        <td><?=$model->comment?></td>
        <td><?=$model->n_only?'V':'-'?></td>
        <td><?=$model->b_only?'V':'-'?></td>
        <td>
            <span class="deliver <?=($model->type?'fly':'cont')?>" onclick="" style="cursor:pointer;margin-left:3px;">&nbsp;</span>
            <span class="deliver status<?=($model->status)?>" onclick="" style="cursor:pointer;margin-left:3px;">&nbsp;</span>
        </td>
    </tr>
    <? endforeach; endif;?>
</table>
<script type="text/javascript">
    function checkall(el){
        if($(el).is(':checked')){
            $('tr.body td input:not(:disabled)').attr('checked','checked');
        }else
            $('tr.body td input:not(:disabled)').removeAttr('checked');
    }
    function filterform(){
        $("#load").toggle();
        $.get('/admin/list/module/order.html',$("#filter").serialize(),function(m){
            $("#load").css('display','none');
            panel.scape.data.body.html(m);
        }).complete(function(){
            $("#load").css('display','none');
        })
    }
    function update_status(){        
        var oids = [];
        $('tr.body input:not(:disabled):checked').each(function(){
            if($(this).attr('oid'))
            oids.push($(this).attr('oid'));
        })
        if(oids.length==0) alert('Нужно выбрать хотя бы один заказ');
        else
        panel.scape.dialog('<h6>Заказы '+oids.join(', ')+'</h6>',{'width':'90%','z-index':'10000','buttons':{
                'На рассмотрении':function(){send_status(oids,0);},
                'В пути':function(){send_status(oids,1);},
                'Готово Алматы':function(){send_status(oids,2);},
                'Отправлено клиенту':function(){send_status(oids,3);},
                'Возврат':function(){send_status(oids,4);}
            }});
    }

    function send_status(oids,status){
        $.post('/order/status.html',{ids:oids.join(','),status:status},function(){
            call_list('order');
        })
    }
</script>