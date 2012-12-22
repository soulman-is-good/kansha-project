<style>
    .file {
        padding:10px 20px;
        background: #fdf5ce;
    }
    .file:nth-child(odd){
    }
    .file .title {
        margin: 10px 0 0 0;
        float:left;
    }
    .title span {
        font:italic 11px Verdana,serif;
        color:#4D357D;
        margin-right:10px;
    }
    .cross {
        float:right;
    }
</style>
<div class="x3-submenu">
    <?=$modules->menu('restore')?>
</div>
   <div class="x3-main-content">
       <div id="x3-buttons" x3-layout="buttons" style="float:right">
           <?if(is_file(X3::app()->basePath . DIRECTORY_SEPARATOR . 'dump/site.stop')):?>
           <a href="/admin/tools/startwork" class="x3-button">Восстановить работу сайта</a>
           <?else:?>
           <a href="/admin/tools/stopwork" class="x3-button red">Остановить работу сайта</a>
           <?endif;?>
       </div>
        <div id="x3-header" x3-layout="header">Обитель времени</div>
        <div class="x3-paginator" x3-layout="paginator"></div>
        <div class="x3-functional" x3-layout="functional"></div>
        <div id="x3-container">
            <br/>
            <? foreach ($files as $name=>$_files): ?>
            <div class="visio">
                <h2><?=$tables[$name]?></h2>
            <? foreach ($_files as $date=>$file): ?>
                <div class="file" style="color:#d2d2fe ">
                <div class="title"><span><?=$file['time']?></span><a href="#" data-table="<?=$name?>" data-date="<?=$date?>">Откатить</a></div>
                <br clear="both"/>
            </div>
            <? endforeach; ?>
            </div>
            <? endforeach; ?>
        </div>
    </div>
<script type="text/javascript">
    var last = null;
    function status(table,tries){
        if(tries>3) {
            $('#'+table).parent().fadeOut(function(){$(this).remove()});
            $.growl('Возникла ошибка. Либо файл статуса не найден на сервере (что может сведетельствовать так же и об успешном завершении работы) или произошла ошибка его чтения либо сервер не доступен в данный момент.');
            return false;
        }
        $.get('/application/log/restore.'+table+'.status',function(m){
            if(m.status == 'loading')
                $('#'+table).html(m.message)
            else if(typeof m.status != 'undefined'){
                if(m.status == 'warn'){
                    $.growl(m.message,'#FFCB93',10000);
                }else{
                    $('#'+table).parent().remove();
                    if(m.status == 'OK')
                        $.growl(m.message,'ok');
                    else
                        $.growl(m.message);
                }
            }
        },'json').error(function(){setTimeout(function(){status(table,tries+1);},1000);});
    }
    $(function(){
        $('.title a').click(function(){
            var self = $(this);
            $.post('/Admin_Tools/restore',{'table':self.data('table'),'date':self.data('date')},function(m){
                $.growl($('<div />').attr('id',table).html('Подготовка...'),'#d2d2fe',10000000);
                setTimeout(function(){status(self.data('table'),0);},1000)
            })
            return false;
        })
    })
</script>