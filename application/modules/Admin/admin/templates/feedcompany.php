<div class="x3-submenu">
    <span>Отзывы о компаниях</span> :: <a href="/admin/feedshop">Отзывы о товарах</a>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Отзывы о компаниях",
            'labels'=>array(
                'company_id'=>array('@value'=>'Компании','@wrapper'=>'<a href="{{=$module->getCompany()->getLink()}}/feedback.html" target="_blank">{{=$module->getCompany()->title}}'),
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