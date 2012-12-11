<div class="x3-submenu">
    <a href="/admin/news">Новости</a> :: <span>Обзоры</span> :: <a href="/admin/newsgrabber">Граббер</a>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Обзоры",
            'labels'=>array(
                'kavziid'=>array('@value'=>'Сграблена','@wrapper'=>'{{=($module->kvaziid!=""?"*":"")}}'),
                'created_at'=>array('@value'=>'Название','@wrapper'=>'{{=$modules->date()}}'),
                'title'=>array('@value'=>'Название','@wrapper'=>'<a href="/admin/article/edit/{@id}">{@title}</a>'),
                'status'=>'Видимость'
            ),
            'class'=>$class,
            'actions'=>$actions,
            'action'=>$action,
            'subaction'=>$subaction,
            'paginator'=>$paginator,
            'functional'=>''            
            )
        )?>