<div class="x3-submenu">
    <span>Отзывы</span> :: <a href="/admin/feedcompany">Отзывы о компаниях</a> :: <a href="/admin/feedshop">Отзывы о товарах</a>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Отзывы о сайте",
            'labels'=>array(
                'ip'=>'IP',
                'created_at'=>'Время',
                'name'=>'Имя',
                'email'=>'E-Mail',
                'content'=>'Отзыв',
                'rank'=>'Оценка',
                'status'=>'Утвержден'
            ),
            'class'=>$class,
            'actions'=>$actions,
            'action'=>$action,
            'subaction'=>$subaction,
            'paginator'=>$paginator,
            'functional'=>''            
            )
        )?>