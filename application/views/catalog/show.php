<div class="vacancii other">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="/">Главная</a>
            <span class="divider">»</span>
            <a href="/catalog.html">Каталог</a>
            <span class="divider">»</span>
            <a href="/catalog-<?=$section['id']?>.html"><?=$section['title']?></a>
            <span class="divider">»</span>
            <span class="active"><?=$model->title?></span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div>
            <div class="mistake_in_border"><?=$model->title?></div>

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
        <div class="mistake_inside board">

            <div class="board_inside">
                <img src="/uploads/Catalog/510x265xw/<?=$model->image?>" />
                <p>&nbsp;</p>
                <?=$model->text?>
            </div>
        </div>

        <div class="clear-both"></div>
        <?=$this->renderPartial('@layouts:footer.php');?>

    </div><!--вакансии-->

</div>