<div class="x3-submenu">
    <a href="/admin/feedcompany">Отзывы о компаниях</a> :: <span>Отзывы о товарах</span>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Отзывы о товарах",
            'labels'=>array(
                'item_id'=>array('@value'=>'Товар','@wrapper'=>'<a href="{{=$module->getItem()->getLink()}}/feedback.html" target="_blank">{{=$module->getItem()->title}}</a>'),
                'created_at'=>'Время',
                'name'=>'Имя',
                'exp'=>'Отзыв',
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