<div class="vacancii">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="/">Главная</a>
            <span class="divider">»</span>
            <span class="active">FAQ</span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div>
            <div class="mistake_in_border">FAQ</div>

        </div>
        <div class="dblborder"><!----></div>
        <? foreach ($models as $model): ?>
        <div class="position">
            <span>&nbsp;</span><a href="#" x3="faq:<?=$model->id?>:question:@"><?=$model->question?></a>
            <div style="display:none" class="brown_list2 faq" x3="faq:<?=$model->id?>:answer:$">
                <?=nl2br($model->answer)?>
            </div>
        </div>
        <? endforeach; ?>
        <div id="pustoi" style="height:86px">&nbsp;</div>


        <?=$this->renderPartial('@layouts:footer.php');?>
        
    </div><!--вакансии-->

</div>