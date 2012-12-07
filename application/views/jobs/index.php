<div class="vacancii other">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="/">Главная</a>
            <span class="divider">»</span>
            <span class="active">Вакансии</span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div><div class="mistake_in_border">Вакансии</div></div>
        <div class="dblborder"><!----></div>
        <? foreach ($models as $model): ?>
        <div class="position">
            <span>&nbsp;</span><a href="#"><?=$model->title?></a>
            <div class="inner_text" style="padding:20px 0;display: none;">
                <?=$model->text?>
            </div>
        </div>
        <? endforeach; ?>


        <div id="pustoi" style="height:44px">&nbsp;</div>
        <?=$this->renderPartial('@layouts:footer.php');?>
    </div>
</div>