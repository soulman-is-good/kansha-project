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
        С выделенными: <a href="#" onclick="delete_items();return false;" style="padding:2px;background:#ff9999;color:#FFF;text-decoration: none">Удалить</a>
    </div>
    <form id="filter">
        <?/*Фильтр: от <input type="text" value="<?=date("d.m.Y",$from)?>" id="from" name="from" /> до <input type="text" value="<?=date("d.m.Y",$till)?>" id="till" name="till" />*/?>
        <a href="#" style="margin: 0 5px; text-decoration:none;border-bottom: 1px dashed #660066;color:#660066" onclick="$(this).siblings('div').slideToggle();return false;">Пользователи</a>
        <div style="position:absolute;width:120px;background: #BFB2D9;display:none;border: 1px solid #cccccc;line-height: 18px;padding: 5px;left:280px">
        </div>
        <input type="button" value="Ок" onclick="filterform()" />
        <img id="load" src="/images/loader.gif" style="display:none;" />
    </form>
</div>
<table style="width:100%">
    <tr class="head">
        <td><input type="checkbox" onclick="checkall(this);" /></td>
        <td>Марка</td>
        <td>Партномер</td>
        <td>Наименование</td>
        <td>Кол-во</td>
        <td>Вес</td>
        <td>Цена</td>
        <td>Склад</td>
        <td>Срок</td>
        <td>&nbsp;</td>
    </tr>
    <?if(empty($models)):?>
    <tr>
        <td colspan="15" style="text-align:center">Нет запчастей</td>
    </tr>
    <?else:
        $items = array();
        $makers = array();
    foreach ($models as $model):
        if(!isset($items[$model->item_id]))
            $items[$model->item_id] = Item::getByPk($model->item_id);
        $item = $items[$model->item_id];
        if($item->maker_id>0 && !isset($makers[$item->maker_id]))
            $makers[$item->maker_id] = Maker::getByPk($item->maker_id);
        if(isset($makers[$item->maker_id]))
            $maker = $makers[$item->maker_id];
        if(NULL!==$maker){
            $makert = $maker->title;
        }else{
            $makert="-";
        }
        if(NULL!==($store = $model->store_id())){
            $storet = $store->title;
        }else
            $storet = "-";
    ?>
    <tr class="body" price_id="<?=$model->id?>" item_id="<?=$item->id?>">
        <td><?='<input oid="'.$model->id.'" type="checkbox"  />'?></td>
        <td><?=$makert?></td>
        <td><?=$item->partno?></td>
        <td><?=$item->title?></td>
        <td><?=$model->quantity?></td>
        <td><?=$model->weight?></td>
        <td><?=$model->price?></td>
        <td><?=$storet?></td>
        <td><?=$model->time?></td>
        <td>
        </td>
    </tr>
    <? endforeach; endif;?>
</table>