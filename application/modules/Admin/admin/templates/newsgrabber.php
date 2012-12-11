<div class="x3-submenu">
    <a href="/admin/news">Новости</a> :: <a href="/admin/article">Обзоры</a> :: <span>Граббер</span>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Грабберы",
            'labels'=>array(
                'data'=>'К модулю',
                'type'=>'Тип',
                'url'=>'Ссылка списка новостей',
                'status'=>'Видимость'
            ),
            'class'=>$class,
            'actions'=>$actions,
            'action'=>$action,
            'subaction'=>$subaction,
            'paginator'=>$paginator,
            'functional'=>'<a id="grab" href="/site/grabnews" class="x3-button">Сграбить последние записи</a>'
            )
        )?>