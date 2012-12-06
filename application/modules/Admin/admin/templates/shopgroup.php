<div class="x3-submenu">
    <a href="/admin/shop">Товары</a> :: <span>Группы</span> :: <a href="/admin/shopcategory">Категории</a>
</div>
<?
$metas = array();
foreach($modules as $mm)
    $metas[$mm->id] = array('title'=>$mm->metatitle,'keywords'=>$mm->metakeywords,'description'=>$mm->metadescription);
$opt = X3_Html::form_tag('option', array('%content'=>'30')).X3_Html::form_tag('option', array('%content'=>'50')).X3_Html::form_tag('option', array('%content'=>'100'));
$perpage = X3_Html::form_tag('select', array('%content'=>$opt,'style'=>'float:right'));
?>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Группы",
            'labels'=>array(
                'title'=>array('@value'=>'Название','@wrapper'=>'<a href="/admin/shopgroup/edit/{@id}">{@title}</a> ({{=$modules->countGoods()}})'),
            ),
            'class'=>$class,
            'actions'=>$actions,
            'action'=>$action,
            'subaction'=>$subaction,
            'paginator'=>$paginator,
            'functional'=>'<div id="genstat" style="margin:0 10px;"></div><a class="x3-button" href="#" id="gen">Генерировать модели</a>'.$perpage            
            )
        )?>
<script type="text/javascript">
    var meta = <?= json_encode($metas)?>;
    bIsRunning = <?=(is_file('uploads/gen_all.stat')?'true':'false')?>;
    var div = null;    
    $("#gen").click(function(){
        if(bIsRunning) return false;
        $(this).addClass('red');
        $.get('/shop/generateall',function(){
            gen_status();
        });
        return false;
    })
    function gen_status(){
        $.get('/uploads/gen_all.stat',function(m){
            if(m!=null){
                $.get('/uploads/status-models'+m.id+'.log',function(n){
                    if(n!=null){
                        $('#genstat').html(m.title+' ('+n.percent+'%)');
                        setTimeout(function(){gen_status()},1500);
                    }else{
                        bIsRunnong = false;
                        $('#gen').removeClass('red');
                    }
                },'json').error(function(){bIsRunning = false;$('#gen').removeClass('red');})
            }else {
                bIsRunning = false;$('#gen').removeClass('red');
            }
        },'json').error(function(){bIsRunning = false;$('#gen').removeClass('red');})
    }
    if(bIsRunning){
        $('#gen').addClass('red');
        gen_status();
    }
    $(function(){
        $('.action-meta a').each(function(){
            $(this).click(function(){
                if(div == null)
                    div = $('<div title="Мета-инормация" />').data('id',$(this).attr('href').split('/').pop())
                    .append($('<form />')
                            .append($('<span />').css({'display':'block','width':'200px'}).html('Мета-заголовок'))
                            .append($('<input />').attr({'name':'Meta[metatitle]'}).css({'width':'300px'}))
                            .append($('<span />').css({'display':'block','width':'200px'}).html('Мета-ключевые слова'))
                            .append($('<input />').attr({'name':'Meta[metakeywords]'}).css({'width':'300px'}))
                            .append($('<span />').css({'display':'block','width':'200px'}).html('Мета-описание'))
                            .append($('<textarea />').attr({'name':'Meta[metadescription]'}).css({'width':'300px'}))
                    )
                    .dialog({modal:true,width:'350px',
                        open:function(){
                            var iiid = $(this).data('id');
                            $(this).find('[name="Meta[metatitle]"]').val(meta[iiid].title);
                            $(this).find('[name="Meta[metakeywords]"]').val(meta[iiid].keywords);
                            $(this).find('[name="Meta[metadescription]"]').val(meta[iiid].description);
                        },
                        close:function(){
                            $(this).find('input').val('');
                            $(this).find('textarea').val('');
                        },
                        buttons:{
                            'Сохранить':function(){
                                var iiid = $(this).data('id');
                                var t = $(this).find('[name="Meta[metatitle]"]').val();
                                var k = $(this).find('[name="Meta[metakeywords]"]').val();
                                var d = $(this).find('[name="Meta[metadescription]"]').val();
                                $.post('/meta/save/id/'+iiid,{Meta:{metatitle:t,metakeywords:k,metadescription:d},module:'Shop_Group'},function(m){
                                    if(m.status=='ERROR'){
                                        if(typeof m.message == 'object'){
                                            for(i in m.message)
                                                $.growl(m.message[i]);
                                        }else
                                            $.growl(m.message)
                                    }else{
                                        meta[iiid].title = t;
                                        meta[iiid].keywords = k;
                                        meta[iiid].description = d;
                                        $.growl('Сохранено!','ok');
                                        div.dialog('close');
                                    }
                                },'json').error(function(){$.growl('Ошибка сохранения!')})
                            },
                            'Отмена':function(){
                                $(this).dialog('close');
                            }
                    }});
                else
                    div.data('id',$(this).attr('href').split('/').pop()).dialog('open');
                return false;
            })
        })
    })
</script>