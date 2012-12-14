<div class="x3-submenu">
    <?if($this->allowed('Shop_Item')):?>
    <a href="/admin/shop">Товары</a> :: 
    <?endif;?>
    <?if($this->allowed('Shop_Group')):?>
    <a href="/admin/shopgroup">Группы</a> :: 
    <?endif;?>
    <span>Категории</span>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Категории",
            'labels'=>array(
                'image'=>array('@value'=>'Иконка','@wrapper'=>'<img src="/uploads/Shop_Category/{@image}" />'),
                'title'=>array('@value'=>'Название','@wrapper'=>'<a href="/admin/shopcategory/edit/{@id}">{@title}</a>'),
                'weight'=>'Порядок',
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