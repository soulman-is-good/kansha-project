<?php
$url = '/'.strtolower(X3_String::create($model->title)->translit()).($model->articule!=''?"-($model->articule)-":'-').$model->id;
$trank = X3::db()->fetch("SELECT SUM(`rank`) rank FROM shop_feedback sf WHERE sf.item_id IN (SELECT id FROM shop_item WHERE status AND group_id=$model->group_id)");
$trank = (int)$trank['rank'];
if($trank==0)$trank=1;
$rank = floor(5*$rank/$trank);
$_s = 5-$rank;

//var_dump($pvals);exit;
//var_dump($pgroups[5],$pvals[6]);exit;
?>
<div class="product_expand">

    <table class="product_des">
        <tbody><tr>
                <td style="white-space:nowrap;height:19px;padding-right:10px">
                    <h1 class="long"><?= $title ?><sup><?=$mm['cnt']?></sup></h1>
                </td>
                <td width="100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>

    <div class="des_info">
        <table>
            <tbody><tr>
                    <td style="vertical-align: middle">
                        <div style="" class="des_info_pic">
                            <img src="<?=$image?>" />
                        </div>
                    </td>
                    <td width="100%">
                        <div class="des_info_text">
                            <table width="100%" class="rechange">
                                <tbody><tr>
                                        <td>
                                            <?if($mm['max']>0):?>
                                            <span class="red_cost"><?=  X3_String::create($mm['min'])->currency()?><i class="money">&nbsp;</i><i class="dash">&ndash;</i></span>
                                            <span class="red_cost"><?=  X3_String::create($mm['max'])->currency()?><i class="money">&nbsp;</i></span>
                                            <?endif;?>
                                        </td>
                                        <td>
                                            <div class="popup_one">
                                                <a onclick="$(this).siblings('.popup_swim').slideDown();return false;" class="des_green" href="#"><span>Сообщить когда цена снизится</span></a>
                                                <div style="display:none" class="popup_swim">
                                                    <a onclick="$('.popup_swim').slideUp();return false;" class="des_green" href="#"><span>Сообщить когда цена снизится</span></a>
                                                    <h3><?=$title?></h3>
                                                    <form action="/user_Price/set.html" method="post" id="notifyme">

                                                        <input type="hidden" value="<?=$model->id?>" name="item_id">
                                                        <input type="text" placeholder="E-mail" class="fio_name" size="12" name="email">
                                                        <input type="text" placeholder="Ожидаемая цена" class="secret" name="price">
                                                        <input type="submit" value="Отправить" class="send">


                                                    </form>

                                                </div>
                                            </div><!--popup_one-->
                                        </td>


                                        <td>
                                            <div class="star_links">
                                                <?for($i=0;$i<$rank;$i++):?>
                                                <a class="stars" href="#"><img width="16" height="16" src="/images/ostar.png"></a>
                                                <?endfor;?>
                                                <?for($i=0;$i<$_s;$i++):?>
                                                <a class="stars" href="#"><img width="16" height="16" src="/images/gstar.png"></a>
                                                <?endfor;?>
                                                <a class="comment" href="/<?=$url?>/feedback.html"><?=$fcount?> <?=  X3_String::create('Отзывов')->numeral($fcount,array('Отзыв','Отзыва','Отзывов'))?></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody></table>


                            <div class="clear-both">&nbsp;</div>
                            <span class="blue-text"><?=$mm['cnt']?> <?=  X3_String::create("Предложений")->numeral($mm['cnt'],array('Предложение','Предложения','Предложений'))?></span>
                            <p><?=$model->propstext?></p>

                            <table width="100%" class="des_lower">
                                <tbody><tr>
                                        <td>
                                            <input type="checkbox" value="yes" name="#"><a class="gray" href="#">Добавить к сравнению</a>
                                        </td>
                                        <td>
                                            <div class="ask_it"><a onclick="$(this).siblings('.popup_float').slideDown();return false;" class="blue_question" href="#"><span>Задать вопрос продавцу</span></a>
                                                <?$cms = X3::db()->query("SELECT c.id,c.title FROM data_company c INNER JOIN company_item ci ON ci.company_id=c.id WHERE ci.item_id=$model->id");
                                                if(is_resource($cms)):
                                                ?>
                                                <div style="display:none;" class="popup_float">
                                                    <a onclick="$('.popup_float').slideUp();return false;" class="blue_question" href="#"><span>Задать вопрос продавцу</span></a>

                                                    <form action="/company/ask.php" method="post" name="ask_company" id="ask_company">
                                                        <input type="hidden" name="Ask[id]" value="<?=$model->id?>" />
                                                        <input type="text" placeholder="Ваше имя" class="fio_name" name="Ask[name]" id="name" />
                                                        <input type="text" placeholder="Ваш e-mail" class="secret" name="Ask[email]" id="email" />
                                                        <select name="Ask[shop]" id="shop" />
                                                            <option value="0"> Выбрать продавца </option>
                                                            <?while($co = mysql_fetch_assoc($cms)):?>
                                                            <option value="<?=$co['id']?>"> <?=$co['title']?> </option>
                                                            <?endwhile;?>
                                                        </select>
                                                        <textarea id="question" placeholder="Ваш вопрос" name="Ask[question]"></textarea>
                                                        <img src="/uploads/captcha.gif" /><input id="captcha" type="text" style="width:64px;margin-left:11px" name="Ask[captcha]" placeholder="Код" />
                                                        <input type="submit" value="Отправить" class="send">
                                                    </form>

                                                </div>
                                                <?endif;?>

                                            </div>
                                        </td>
                                        <td>
                                            <?=  X3_Widget::run('@layouts:widgets:tellAFriend.php',array('url'=>$url,'model'=>$model))?>
                                        </td>
                                    </tr>
                                </tbody></table>




                        </div>
                    </td>
                </tr>
            </tbody></table>
<?if($type == 'chars'):?>
        <?=  X3_Widget::run('@views:shop_Item:_chars.php',array('url'=>$url,'title'=>$title,'mm'=>$mm,'model'=>$model,'fcount'=>$fcount,'scount'=>$scount));?>
<?elseif($type == 'prices'):?>
        <?=  X3_Widget::run('@views:shop_Item:_prices.php',array('url'=>$url,'title'=>$title,'mm'=>$mm,'model'=>$model,'fcount'=>$fcount,'scount'=>$scount));?>
<?elseif($type == 'feedback'):?>
        <?=  X3_Widget::run('@views:shop_Item:_feedback.php',array('url'=>$url,'title'=>$title,'mm'=>$mm,'model'=>$model,'fcount'=>$fcount,'scount'=>$scount,'nofeed'=>$nofeed,'F'=>$feedback,'errors'=>isset($errors)?$errors:array()));?>
<?elseif($type == 'services'):?>
        <?=  X3_Widget::run('@views:shop_Item:_services.php',array('url'=>$url,'title'=>$title,'mm'=>$mm,'model'=>$model,'fcount'=>$fcount,'scount'=>$scount));?>
<?endif;?>

        <div style="padding:10px 0 10px 10px;font:12px Tahoma, sans-serif;color:#222">Смотреть другие <a href="#TODO"><?=X3::app()->groupTitle?> <?=$model->manufacturerTitle()?></a></div>

        <div class="brown_table">
            <div><span><img width="21" height="20" src="/images/star.png">Увидели ошибку - выделите слово или предложение мышкой и нажмите <b>Ctrl+Enter</b></span></div>
        </div>
<?=$this->renderPartial('@layouts:widgets:servicesFor.php',array('model'=>$model))?>
<?=$this->renderPartial('@layouts:widgets:buyWith.php',array('model'=>$model))?>

        <div class="red_info">
            <img width="39" height="29" src="/images/tungle.png">
            <p><?=SysSettings::getValue('BuyerText', 'content', 'Ответственность', null, '')?></p>

        </div>



    </div><!--product_expand-->




    <div class="clear-both">&nbsp;</div>
</div><!--main_services-->


<?=  X3_Widget::run('@layouts:widgets:BannerBottom.php')?>

<script type="text/javascript">
    $('#notifyme').submit(function(){
        var div = $('<div />').css({'left':'0px','top':'0px','width':($(this).parent().width()+16)+'px','height':($(this).parent().height()+24)+'px','position':'absolute'
            ,'background':'#cacaca url(/images/loader.gif) no-repeat 50% 50%','opacity':'0.5'});
        $(this).parent().append(div);
        var self = $(this);
        $.post($(this).attr('action'),$(this).serialize(),function(m){
            div.remove();
            if(m.status == 'OK'){
                self.replaceWith('<div>'+m.message+'</div>')
            }else
                alert(m.message)
        },'json').error(function(){div.remove()});
        return false;
    })
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