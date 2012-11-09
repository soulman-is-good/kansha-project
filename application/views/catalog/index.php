<?php
$url = "/catalog-".$section['id'];
?>
<div class="vacancii other">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="/">Главная</a>
            <span class="divider">»</span>
            <span class="active">Каталог</span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div>
            <div class="mistake_in_border">Каталог</div>

        </div>
        <div class="dblborder"><!----></div>
        <div class="mistake_list catalog">
            <ul>
                <? foreach ($sections as $s): 
                    if(Catalog::newInstance()->table->where('section_id='.$s['id'])->count()==0) continue;
                    ?>
                <?if($s['id']==$section['id']):?>
                <li class="active"><?=$s['title']?></li>
                <?else:?>
                <li><a href="/catalog-<?=$s['id']?>.html"><?=$s['title']?></a></li>
                <?endif;?>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="mistake_inside catalog">
            <? foreach ($models as $model): ?>
            <div class="whitebox">
                <div class="whitebox_orange" style="position:relative;z-index:1">
                    <div class="whitebox_orange_inside" style="background:url(/uploads/Catalog/107x73/<?=$model['image']?>) no-repeat 50% 50%;height:73px;">
                        <a href="<?=$url?>/<?=$model['id']?>.html"><img width="107" height="73" src="/images/_zero.gif" /></a>
                    </div>
                </div>
                <div class="whitebox_ssilka" style="height:35px"><a href="<?=$url?>/<?=$model['id']?>.html"><?=$model['title']?></a></div>
            </div>
            <? endforeach; ?>            


        </div>
        <div class="clear-both"></div>
        <?=$this->renderPartial('@layouts:footer.php');?>

    </div><!--вакансии-->

</div>