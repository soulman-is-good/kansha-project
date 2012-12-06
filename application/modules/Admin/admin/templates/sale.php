<div class="x3-submenu">
    <a href="/admin/company">Компании</a> :: <span>Распродажи</span>
</div>
<?=$this->renderPartial("@views:admin:templates:default.kansha.php",
        array(
            'modules'=>$modules,
            'moduleTitle'=>"Распродажи",
            'labels'=>array(
                'type'=>'Тип',
                'starts_at'=>array('@value'=>'Дата начала','@wrapper'=>'{{=date("d.m.Y",$modules->starts_at)}}'),
                'ends_at'=>array('@value'=>'Дата окончания','@wrapper'=>'{{=date("d.m.Y",$modules->ends_at)}}'),
                'title'=>array('@value'=>'Название','@wrapper'=>'<a href="/admin/sale/edit/{@id}">{@title}</a>'),
                'image'=>array('@value'=>'Изображение','@wrapper'=>'<img src="/uploads/Sale/120x120/{@image}" />'),
                'gifttitle'=>array('@value'=>'Подарок','@wrapper'=>'{{=$module->gifttitle!=""?$module->gifttitle:"-"}}'),
                'price'=>array('@value'=>'Цена','@wrapper'=>'{{=$module->oldprice>0?"(<s>".$module->oldprice."</s>) ".$module->price:$module->price}}'),
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