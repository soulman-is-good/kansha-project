<style>
    .x3-informer{
        width:400px;
        height:300px;
        border: 1px solid #330033;
        margin:5px;
        float:left;
    }
    .x3-informer h3 {
        margin:0;
        padding: 5px 0;
        text-align: center;
        color:#330033;
        border-bottom: 1px solid #330033;
        font-size: 16px;
        height: 20px;
    }
    .x3-informer .informer-content{
        height: 270px;
        overflow: auto;        
    }
    .informer-content .inf{
        margin: 5px;
        border-bottom: 1px dotted #ccccff;
    }
</style>
<div style="margin-left:10px;">
<h1>Добро пожаловать <?=X3::user()->name?>!</h1>
<?if(X3::user()->isAdmin()):?>
<div class="x3-informer">
    <h3>Seo на главной странице</h3>
    <div class="informer-content new_property-content">
        <form id="seomain" action="/sysSettings/seoupdate" method="post" >
        <div class="inf">
            <input name="SysSettings[0][name]" type="hidden" value="SeoTitleSite" />
            Заголовок:
            <input name="SysSettings[0][value]" type="text" style="width:100%" value="<?=  SysSettings::getValue('SeoTitleSite')?>" />
            <input name="SysSettings[1][name]" type="hidden" value="SeoKeywordsSite" />
            Ключевые слова:
            <input name="SysSettings[1][value]" type="text" style="width:100%" value="<?=  SysSettings::getValue('SeoKeywordsSite')?>" />
            <input name="SysSettings[2][name]" type="hidden" value="SeoDescriptionSite" />
            Описание:
            <input name="SysSettings[2][value]" type="text" style="width:100%" value="<?=  SysSettings::getValue('SeoDescriptionSite')?>" />
            <button type="submit">Сохранить</button>
        </div>
        </form>
    </div>
</div>
<?php
$noinfo = true;
$informers = Informer::get(array('@condition'=>array('category'=>'new_property','status'=>'0'),'@order'=>'created_at DESC','@limit'=>50));
if($informers->count()>0):
$noinfo = false;
?>
<div class="x3-informer">
    <h3>Новые свойства <a onclick="clearall('new_property')" href="#clear" title="Очистить" style="float:right;margin-right: 10px" class="ui-widget-content ui-corner-all ui-icon ui-icon-trash">&nbsp;</a></h3>
    <div class="informer-content new_property-content">
        <?foreach($informers as $inf):?>
        <div class="inf"><?=$inf->message?></div>
        <?endforeach;?>
    </div>
</div>
<script type="text/javascript">
    function clearall(cat){
        $.get('/informer/clearall/cat/'+cat,function(){
            $('.'+cat+'-content').html('');
        });
        return false;
    }
    $('#seomain').submit(function(){
        $.post($(this).attr('action'),$(this).serialize(),function(){$.growl('Сохранено','ok')}).error(function(){$.growl('Ошибка сохранения')})
        return false;
    })
</script>
<?endif;?>
<div style="clear:both;"><!----></div>
<?if($noinfo):?>
<i>Пока нет никакой информации</i>
<? endif; ?>
<? endif; ?>
<?php
$noinfo = true;
$informers = Informer::get(array('@condition'=>array('category'=>'tasks','status'=>'0'),'@order'=>'created_at DESC'));
if($informers->count()>0):
$noinfo = false;
?>
<div class="x3-informer">
    <h3>Ваши задания</h3>
    <div class="informer-content new_property-content">
        <?foreach($informers as $inf):
            $msg = json_decode($inf->message);
            if(is_object($msg) && $msg->user_id == X3::user()->id):
            ?>
        <div class="inf" id="cat<?=$inf->id?>"><?=$msg->message?><a onclick="done('<?=$inf->id?>')" href="#clear" title="Решить" style="float:right;margin-right: 10px" class="ui-widget-content ui-corner-all ui-icon ui-icon-flag">&nbsp;</a></div>
        <?endif;endforeach;?>
    </div>
</div>
<script type="text/javascript">
    function done(id){
        $.get('/informer/clearbyid/id/'+id,function(){
            $('#cat'+id).fadeOut();
        });
        return false;
    }
</script>
<?endif;?>
<div style="clear:both;"><!----></div>
<?if($noinfo):?>
<i>Пока нет никакой информации</i>
<? endif; ?>
</div>