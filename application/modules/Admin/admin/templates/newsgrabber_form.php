<div class="x3-main-content">
<style>
    table {
        width:100%;
    }
    table td:first-child{
           width:100px;
    }
    table td{
        text-align: left;
    }
    table td input[type="text"],table.formtable td select{
        width:100%;
    }
    table td textarea{
        width:100%;
    }
</style>
<?/*STANDART TEMPLATE*/
if($modules instanceOf X3_Module_Table):
$form = new Form($modules);
?>
<div id="x3-header" x3-layout="header"><a href="/admin/<?=$action?>"><?=$moduleTitle;?></a> > <?if($subaction=='add'):?>Добавить<?else:?>Редактировать<?endif;?></div>
<div class="x3-paginator" x3-layout="paginator"></div>
<div class="x3-functional" x3-layout="functional"><a href="#test" class="button" id="test">Тестировать</a></div>
<div class="x3-main-content" style="clear:left">
<?=$form->start(array('action'=>"/admin/{$action}/save"));?>
<?
$errs = $modules->table->getErrors();
if(X3::db()->getErrors()!='' || !empty($errs)):?>
    <div class="x3-errors">
        <ul>
            <?foreach($errs as $err)
                foreach($err as $er):?>
            <li><?=$er?></li>
            <?endforeach;?>
            <li><?=X3::db()->getErrors()?></li>
            
        </ul>
    </div>
<?endif;
echo $form->render();?>
</div>
    <script type="text/javascript">
        $('#test').click(function(){
            $.get('/site/test',{
                url:$('#News_Grabber_url').val(),
                type:$('#News_Grabber_type').val(),
                link:$('#News_Grabber_tag').val(),
                title:$('#News_Grabber_regtitle').val(),
                image:$('#News_Grabber_regimage').val(),
                date:$('#News_Grabber_regdate').val(),
                text:$('#News_Grabber_regtext').val()
            },function(m){
                if(typeof $('#123123')[0] != 'undefined')
                    $('#123123').remove();
                $('.x3-main-content').eq(0).append($('<div />').attr("id",'123123').append(m));
            })
            return false;
        })
    </script>
<?endif;?>
</div>