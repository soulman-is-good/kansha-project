<?php
    $models = X3::db()->fetchAll("SELECT * FROM shop_feedback WHERE status AND item_id=$model->id ORDER BY created_at DESC LIMIT 0, 5");
    $mark = explode("\n",SysSettings::getValue('FeedbackMarks', 'content', 'Подписи отзывов', null, "ужасно\nплохо\nнормально\nхорошо\nотлично"));
?>
<div class="table_des">
    <table class="des_header">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px">
                    <div style="margin-top:0px" class="des_header_links">
                                <a class="green_green" href="<?=$url?>/prices.html"><span>Цены<i>&nbsp;</i></span><sup><?=$mm['cnt']?></sup></a>
                                <a class="black_black" href="<?=$url?>.html"><span>Характеристики<i>&nbsp;</i></span></a>
                                <a class="blue_blue active" href="<?=$url?>/feedback.html"><span>Отзывы<i>&nbsp;</i></span><sup><?=$fcount?></sup></a>
                                <a class="orange_orange" href="<?=$url?>/services.html"><span>Услуги<i>&nbsp;</i></span><sup><?=$scount?></sup></a>
                    </div>
                </td>
                <td>
                    <div class="blue_blue_stripe">
                        <div class="blue_left">&nbsp;</div>
                        <div class="blue_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div class="product_comment">
        <?if(!isset($_COOKIE[Shop_Feedback::KEY]) || !in_array($model->id,json_decode($_COOKIE[Shop_Feedback::KEY]))):?>
        <table width="100%" id="form-feed">
            <tbody><tr>
                    <td width="300px">
                        <form action="/<?=X3::app()->request->url?>.html" method="post" id="feedback-form">
                            <div class="raiting">
                                <h4>Рейтинг</h4>
                                <div class="star_links">
                                    <?for($i=0;$i<$F->rank+1;$i++):?>
                                    <a class="stars" href="#"><img width="16" height="16" src="/images/ostar.png"></a>
                                    <?endfor;?>
                                    <?for($i=$F->rank+1;$i<5;$i++):?>
                                    <a class="stars" href="#"><img width="16" height="16" src="/images/gstar.png"></a>
                                    <?endfor;?>
                                </div>
                                <input name="rank" value="<?=$F->rank?>" type="hidden" id="rank" />
                                <span><?=($F->rank>-1)?$mark[$F->rank]:'оцените продукт'?></span>
                            </div>
                            <div class="gray">
                                <i>*</i><span class="gray_inside"><b>Ваше имя</b> (Введите ваше настоящее имя)</span>
                            </div>
                            <input type="text" name="realname" id="realname" value="<?=$F->name?>" />
                            <div class="gray">
                                <i>*</i><i class="with">&nbsp;</i><span class="gray_inside"><b>Плюсы</b> (Не более 255 символов)</span><span class="right"> Символов: 0</span>
                            </div>
                            <input type="text" name="plus" id="plus" value="<?=$F->plus?>" />
                            <div class="gray">
                                <i>*</i><i class="with red">&nbsp;</i><span class="gray_inside"><b>Минусы</b> (Не более 255 символов)</span><span class="right"> Символов: 0</span>
                            </div>
                            <input type="text" name="minus" id="minus" value="<?=$F->minus?>" />
                            <div class="gray">
                                <i>*</i><span class="gray_inside"><b>Продолжительность использования</b></span>
                            </div>
                            <input type="text" name="long" id="long" value="<?=$F->long?>" />
                            <div class="gray">
                                <i>*</i><span class="gray_inside"><b>Общее впечатление</b></span>
                            </div>
                            <textarea rows="6" cols="150" name="exp" id="exp" /><?=$F->exp?></textarea>
                            <div class="send"><input type="submit" value="Отправить" class="photo"></div>
                        </form>
                    </td>
                    <td style="float:right">
                        <div class="with_links">
                            <a onclick="$('#form-feed').slideToggle();return false;" class="roal_up" href="#">Свернуть форму</a><a class="up" href="#">&nbsp;</a><a class="down" href="#">&nbsp;</a>
                        </div>
                    </td>
                    <td style="float:right">
                        <?if(empty($errors)):?>
                        <div class="orange_popup">
                            <h4>Правила</h4>
                            <?=  SysSettings::getValue('Shop_Item.FeedbackRules', 'text', 'Правила отзыва', 'Текстовка', "<p>Отзыв о продукте следует основывать только на собственном опыте эксплуатации.
                                Постарайтесь своими словами описать, почему вы рекомендуете либо не рекомендуете данный продукт к покупке.</p>
                            <p>Официальный либо рекламный стиль, в том числе включение в отзыв адресов сайтов, телефонов и прочей рекламной информации запрещены.
                                Подобные отзывы будут удаляться.</p>
                            <p>Запрещено копировать материалы из других источников.</p>")?>
                        </div>
                        <?else:?>
                        <div class="orange_popup" style="background:#FFC1C1;color:#660202;border-color: #660202">
                            <h4>Возникли следующие ошибки:</h4>
                            <? foreach ($errors as $error): ?>
                                <p><?=$error[0]?></p>
                            <? endforeach; ?>
                        </div>
                        <?endif;?>
                    </td>
                </tr>
            </tbody></table>
        <?else:?>
        <p>Спасибо за оставленный Вами отзыв.</p>
        <?endif;?>
        <? foreach ($models as $j=>$feedback):?>
        
        <div class="comment_box<?if($j==0)echo ' top';?>">
            <table class="upper">
                <tbody><tr>
                        <td>
                            <b><?=$feedback['name']?></b>
                        </td>
                        <td>
                            <span class="date"> <?=date("j.m.Y в H:i",$feedback['created_at'])?> </span>
                        </td>
                        <td>
                            <div class="star_links">
                                <?for($i=0;$i<$feedback['rank'];$i++):?>
                                <a class="stars"><img width="16" height="16" src="/images/ostar.png"></a>
                                <?endfor;?>
                                <?for($i=$feedback['rank'];$i<5;$i++):?>
                                <a class="stars"><img width="16" height="16" src="/images/gstar.png"></a>
                                <?endfor;?>
                            </div>
                        </td>
                        <td>
                            <span class="date"><?=$mark[$feedback['rank']]?></span>
                        </td>
                    </tr>
                </tbody></table>
            <span class="color_gray">Продолжительность использования: <b><?=$feedback['long']?></b></span>
            <p><?=nl2br($feedback['exp'])?></p>
            <table width="100%" class="red_green">
                <tbody><tr>
                        <td width="370px">
                            <table class="red_green_inside">
                                <tbody><tr>
                                        <td>
                                            <div style="background-image:url(/images/hands.png)" class="hand">&nbsp;</div>
                                        </td>
                                        <td>
                                            <p><?=$feedback['plus']?></p>
                                        </td>
                                    </tr>
                                </tbody></table>
                        </td>
                        <td>
                            <table class="red_red_inside">
                                <tbody><tr>
                                        <td>
                                            <div style="background-image:url(/images/hands.png)" class="hand red">&nbsp;</div>
                                        </td>
                                        <td>
                                            <p><?=$feedback['minus']?></p>
                                        </td>
                                    </tr>
                                </tbody></table>
                        </td>

                    </tr>
                </tbody></table>
        </div><!--comment_box-->
        <? endforeach; ?>
        <div style="height:10px" class="pustoi">&nbsp;</div>
    </div>

    <script type="text/javascript">
        var sel_i = -1;
        var marks = <?=json_encode($mark)?>;
        $('.raiting .stars').hover(function(){
            var stars = $(this).parent().children('.stars');
            var i = stars.index(this);
            stars.slice(0, i+1).find('img').attr('src','/images/ostar.png');
        },function(){
            var stars = $(this).parent().children('.stars');
            stars.find('img').attr('src','/images/gstar.png');
            if(sel_i>-1)
                stars.slice(0, sel_i+1).find('img').attr('src','/images/ostar.png');
        })
        .click(function(){
            var stars = $(this).parent().children('.stars');
            sel_i = stars.index(this);
            $('#rank').val(sel_i);
            $('.raiting span').html(marks[sel_i]);
            return false;
        });
        $('.gray .right').each(function(){
            var self = $(this);
            var last = '';
            $(this).parent().next().keydown(function(e){
                var l = $(this).val().length;
                last = $(this).val();
                if(l>254 && e.keyCode != 8 && e.keyCode != 46 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 16) return false;
            }).keyup(function(){
                var l = $(this).val().length;
                if(l>255) {
                    $(this).val(last);
                    return false;
                }
                self.html('Символов: '+l);
            })
        })
    </script>
</div>