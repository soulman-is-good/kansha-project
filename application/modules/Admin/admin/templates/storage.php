<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Хранилище адресов",
            'labels'=>array(
                'ip'=>'IP',
                'data'=>array('@value'=>'Данные','@wrapper'=>'<a href="#data" onclick=\'show_data({@data});return false;\'>посмотреть</a>'),
                'name'=>'Имя',
                'email'=>'E-mail',
                'action'=>'Действие',
                'referer'=>'Ссылка',
                'time'=>'Время запроса',
                'message'=>'Статус',
            ),
            'class'=>$class,
            'actions'=>$actions,
            'action'=>$action,
            'subaction'=>$subaction,
            'paginator'=>$paginator,
            'functional'=>''            
            )
        )?>
<script type="text/javascript">
    function show_data(json){
        var div = $('<div />').attr({'title':'Данные'});
        var table = $('<table />');
        for(i in json){
            table.append('<tr><td>'+i+'</td><td>'+json[i]+'</td></tr>');
        }
        div.append(table);
        div.dialog({modal:true});
    }
</script>