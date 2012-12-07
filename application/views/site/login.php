<div class="vacancii other">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="#">Главная</a>
            <span class="divider">»</span>
            <span class="active">Администрирование</span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div>
            <div class="mistake_in_border">Вход</div>

        </div>
        <div class="dblborder"><!----></div>

        <div class="mistake_inside adress" style="margin:0 auto;float:none">
            <div class="adress_one send"><?=$error?></div>
            <form method="post">
                <div class="phorma">
                    <div class="phorm_title">Логин:*</div>
                    <div class="dlina_phorma"><input type="text" name="User[login]" size="12"></div>
                </div>
                <div class="clear-both"></div>
                <div class="phorma">
                    <div class="phorm_title">Пароль:*</div>
                    <div class="dlina_phorma"><input type="password" name="User[password]" size="12"></div>
                </div>
                <div class="clear-both"></div>
                <div class="nb">Поля, помеченные (*), являются обязательными для заполнения.</div>
                <div class="button"><input type="submit" value="Войти"></div>
            </form>

        </div>
        <div class="clear-both"></div>
        <?=$this->renderPartial('@layouts:footer.php');?>

    </div>

</div>