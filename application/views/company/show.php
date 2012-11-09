<?php
$address = $model->getAddress();
$name = X3_String::create($model->title)->translit();
$name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);    
$url = "/{$name}-company$model->id";
$stat = X3::db()->fetch("SELECT COUNT(0) cnt FROM company_stat WHERE company_id=$model->id");
$stat = $stat['cnt'];
$gcount = X3::db()->fetch("SELECT COUNT(0) cnt FROM company_item WHERE company_id=$model->id");
$gcount = $gcount['cnt'];
$image = is_file(X3::app()->basePath.'/uploads/Company/'.$model->image);
?>
<div class="product_expand">
    <table style="width:100%" class="blue_stripes shops">
        <tbody><tr>
                <td style="paddign-right:10px;white-space:nowrap;height:19px">
                    <h2 class="shops"><?=$model->title?></h2>
                </td>
                <td>
                    <div class="one_blue_stripe">
                        <div class="blue_left">&nbsp;</div>
                        <div class="blue_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <table width="100%" class="shops_about_upper">
        <tbody><tr>
                <?if($image!==false && true==($site = X3_String::create($model->site)->check_protocol('http'))):?>
                <td>
                    <?if($image):?>
                    <a href="#" onclick="return false"><img src="/uploads/Company/127x57xf/<?=$model->image?>"></a>
                    <?endif;?>
                    <?if($site):?>
                    <div class="shops_link"><a class="move" href="/company/go/to/<?=base64_encode($model->id)?>" target="_blank">Перейти на сайт продавца</a></div>
                    <?endif;?>
                </td>
                <?endif;?>
                <td>
                    <div class="shops_middle">
                        <table>
                            <tbody><tr>
                                    <td class="size">
                                        <span>Просмотров:</span>
                                    </td>
                                    <td style="text-align:right">
                                        <span><?=$stat?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="size">
                                        <span class="change">Рейтинг:</span>
                                    </td>
                                    <td>
                                        <div class="star_links">
                                            <?for($i=0;$i<$rank;$i++):?>
                                            <a class="stars" href="<?=$url?>/feedback.html"><img height="16" width="16" src="/images/ostar.png"></a>
                                            <?endfor;?>
                                            <?for($i=$rank;$i<5;$i++):?>
                                            <a class="stars" href="<?=$url?>/feedback.html"><img height="16" width="16" src="/images/gstar.png"></a>
                                            <?endfor;?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="size">
                                        <span>Отзывы:</span>
                                    </td>
                                    <td style="text-align:right">
                                        <span><?=$fcount?></span>
                                    </td>
                                </tr>
                                <?if(0 && $gcount>0):?>
                                <tr>
                                    <td class="size last">
                                        <span>Товары:</span>
                                    </td>
                                    <td style="text-align:right" class="last">
                                        <span><?=$gcount?></span>
                                    </td>
                                </tr>
                                <?endif;?>
                            </tbody></table>
                        <div class="ask_it"><a class="blue" href="#" onclick="$('.popup_float').slideDown();return false;"><span>Задать вопрос продавцу</span></a>
                            <div style="display:none;left:-16px;top:0px" class="popup_float">
                                <a onclick="$('.popup_float').slideUp();return false;" class="blue_question" href="#"><span>Задать вопрос продавцу</span></a>

                                <form action="/company/ask.php" method="post" name="ask_company" id="ask_company">
                                    <input type="hidden" name="Ask[id]" value="common" />
                                    <input name="Ask[shop]" id="shop" value="<?=$model->id?>" type="hidden" />
                                    <input type="text" placeholder="Ваше имя" class="fio_name" name="Ask[name]" id="name" />
                                    <input type="text" placeholder="Ваш e-mail" class="secret" name="Ask[email]" id="email" />
                                    <textarea id="question" placeholder="Ваш вопрос" name="Ask[question]"></textarea>
                                    <img src="/uploads/captcha.gif" /><input id="captcha" type="text" style="width:64px;margin-left:11px" name="Ask[captcha]" placeholder="Код" />
                                    <input type="submit" value="Отправить" class="send">
                                </form>

                            </div>
                        
                        </div>
                    </div>
                </td>
                <td>
                    <table class="shops_upper_right">
                        <tbody><tr>

                                <td class="first">
                                    <span>Телефоны:</span>
                                </td>
                                <td>
                                    <ul>
                                        <? foreach ($address->phones as $phone): ?>
                                        <li><?=$phone?></li>
                                        <? endforeach; ?>
                                    </ul>
                                </td>

                            </tr>
                            <?if($address->fax!=''):?>
                            <tr>
                                <td class="first">
                                    <span>Факс:</span>
                                </td>
                                <td>
                                    <ul>
                                        <li><?=$address->fax?></li>
                                    </ul>
                                </td>
                            </tr>
                            <?endif;?>
                            <tr class="pustoi">
                                <td colspan="2">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Адрес: </span>
                                </td>
                                <td class="second">
                                    <span>г. <?=($address->getRegion()->title).($address->address!=''?", $address->address":'')?></span>
                                </td>
                            </tr>
                            <tr class="pustoi"><td colspan="2">&nbsp;</td></tr>
                            <?if(0 && X3_String::create($model->site)->check_protocol()):?>
                            <tr>
                                <td class="first">
                                    <span>Сайт:</span>
                                </td>
                                <td>
                                    <a class="black" target="_blank" href="/company/go/to/<?=base64_encode($model->id)?>"><?=trim(str_replace('http://','',$model->site),' /')?></a>
                                </td>
                            </tr>
                            <?endif;?>
                            <?if($address->email):?>
                            <tr>
                                <td class="first">
                                    <span>E-mail:</span>
                                </td>
                                <td>
                                    <a class="black" href="mailto:<?=$address->email?>"><?=$address->email?></a>
                                </td>
                            </tr>
                            <?endif;?>
                            <?if($address->skype):?>
                            <tr>
                                <td class="first">
                                    <span>Skype:</span>
                                </td>
                                <td>
                                    <a class="black" href="skype:<?=$address->skype?>"><?=$address->skype?></a>
                                </td>
                            </tr>
                            <?endif;?>
                            <?if($address->icq):?>
                            <tr>
                                <td class="first">
                                    <span>ICQ:</span>
                                </td>
                                <td>
                                    <a class="black" href="icq:<?=$address->icq?>"><?=$address->icq?></a>
                                </td>
                            </tr>
                            <tr class="pustoi"><td colspan="2">&nbsp;</td></tr>
                            <?endif;?>
                            <?/*<tr>
                                <td class="first">
                                    <span>Время работы:</span>
                                </td>
                                <td>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">в</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day" href="#">ч</a>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day active" href="#">в</a>
                                </td>
                            </tr>*/?>


                        </tbody></table>
                </td>

            </tr>
        </tbody></table>

<?=X3_Widget::run("@views:company:__$type.php",array('model'=>$model,'url'=>$url,'fcount'=>$fcount,'scount'=>$scount,'srcount'=>$srcount,'F'=>$F));?>
<br/>
<br/>
<br/>
        <div class="brown_table" style="margin-top:20px">
            <div><span><img height="20" width="21" src="/images/star.png">Пожалуйста, при обращении в компанию ссылайтесь на сайт <a href="<?=X3::app()->baseUrl?>">Kansha.kz!</a> Заранее благодарим и удачных покупок :)</span></div>
        </div>
<?=X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:30px;margin-bottom:20px'))?>
</div>
<script type="text/javascript">
    $('#ask_company').submit(function(){
        var self = $(this);
        $.post($(this).attr('action'),$(this).serialize(),function(m){
            $('[name^="Ask["]').css({'color':'#B7B7B7','font-weight':'normal'});
            var pass = true
            for(i in m){
                pass = false;
                $('#'+i).css({'color':'#E60808','font-weight':'bold'});
            }
            if(pass){
                self.fadeOut(function(){$(this).replaceWith('<h2>Ваш вопрос отправлен продавцу. Спасибо!</h2>')})
            }
        },'json').error(function(){});
        return false;
    })    
</script>