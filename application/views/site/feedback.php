<div class="vacancii other">
    <div class="gray_minibox" style="right:-8px;top:-10px;background:url(/images/mini.png) repeat scroll 0 0 transparent">
                <div class="gray_minibox_inside"><a href="/" style="padding:5px;text-decoration: none;">&nbsp;</a></div>
            </div>
    <div class="vacancii_inside" style="padding-top:21px">
        <div class="way">
            <a href="/">Главная</a>
            <span class="divider">»</span>
            <span class="active">Отправьте письмо</span>
        </div>
        <div class="mistake">
            <div class="brder left">&nbsp;</div>
            <div class="brder right">&nbsp;</div>
            <div class="mistake_in_border">Контакты</div>

        </div>
        <div class="dblborder"><!----></div>
        <div class="mistake_list catalog">
            <ul>
                <li><a href="/contacts.html">Адрес офиса</a></li>
                <li class="active">Отправьте письмо</li>
            </ul>
        </div>

        <div class="mistake_inside adress">
            <div class="adress_one send">Отправьте письмо</div>
            <?if(NULL!==($succ = X3_Session::read('fos'))):?>
            <div class="success">
            <?=$succ?>
            </div>
            <?endif;?>
            <?if($errors):?>
            <div class="errors">Необходимо исправить следующие ошибки:<ul>
                    <? foreach ($errors as $err): ?>
                    <li><?=$err?>    
                    <? endforeach; ?>
            </ul></div>
            <?endif;?>
            <form method="post" action="/feedback.html">
                <div class="phorma">
                    <div class="phorm_title">Ваше имя:*</div>
                    <div class="dlina_phorma"><input type="text" name="Fos[name]" value="<?=$fos['name']?>" size="45" /></div>
                </div>
                <div class="clear-both"></div>
                <div class="phorma">
                    <div class="phorm_title">Компания:</div>
                    <div class="dlina_phorma"><input type="text" name="Fos[company]" value="<?=$fos['company']?>" size="45" /></div>
                </div>
                <div class="clear-both"></div>
                <div class="phorma">
                    <div class="phorm_title">Город:*</div>
                    <div class="dlina_phorma"><input type="text" name="Fos[city]" size="45" value="<?=$fos['city']?>" /></div>
                </div>
                <div class="phorma">
                    <div class="phorm_title">Ваш телефон:</div>
                    <div class="dlina_phorma"><input type="text" name="Fos[phone]" size="45" value="<?=$fos['phone']?>" /></div>
                </div>
                <div class="phorma">
                    <div class="phorm_title">Ваш email:*</div>
                    <div class="dlina_phorma"><input type="text" name="Fos[email]" value="<?=$fos['email']?>" size="45" /></div>
                </div>
                <div class="phorma">
                    <div class="phorm_title">Ваше сообщение:*</div>
                    <textarea name="Fos[text]" cols="45" rows="7" style="margin-left:0;width:325px"></textarea>
                </div>
                <div class="clear-both"></div>
                <div class="nb">Поля, помеченные (*), являются обязательными для заполнения.</div>
                <div class="button"><input type="submit" value="Отправить"></div>
            </form>




        </div>
        <div class="clear-both"></div>
        <?=$this->renderPartial('@layouts:footer.php');?>

    </div><!--вакансии-->

</div>