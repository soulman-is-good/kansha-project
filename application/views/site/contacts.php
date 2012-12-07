<div class="vacancii other">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="/">Главная</a>
            <span class="divider">»</span>
            <span class="active">Адрес офиса</span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div>
            <div class="mistake_in_border">Контакты</div>
            <div class="clear-both">&nbsp;</div>
        </div>

        <div class="dblborder"><!----></div>

        <div class="mistake_list catalog">
            <ul>
                <li class="active">Адрес офиса</li>
                <li><a href="/feedback.html">Отправьте письмо</a></li>
            </ul>
        </div>
        <div class="mistake_inside adress">
            <div class="adress_one">Aдрec офиса</div>
            <div class="adress_two">
                <?=nl2br(Syssettings::getValue('Address','content','Адрес','Контакты','Казахстан, г. Алматы'));?>
            </div>
            <div class="map" style="border:2px solid #e37920;float:left;">
<?=Syssettings::getValue('Roadmap','html','Карта проезда','Контакты','');?>
            </div>
            <div class="clear-both"></div>



        </div>
        <div class="clear-both"></div>
        <?=$this->renderPartial('@layouts:footer.php');?>

    </div><!--вакансии-->

</div>