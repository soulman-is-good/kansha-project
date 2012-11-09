<?php
$models = X3::db()->fetchAll("SELECT * FROM company_feedback WHERE status AND company_id=$model->id ORDER BY created_at DESC LIMIT 0, 5");
$mark = explode("\n",SysSettings::getValue('FeedbackMarks', 'content', 'Подписи отзывов', null, "ужасно\nплохо\nнормально\nхорошо\nотлично"));
$errors = $F->getTable()->getErrors();
$c = count($models);
?>
<table class="three_inset">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;">
                <a class="nero" href="<?=$url?>.html"><span>О Компании</span></a>
                <a class="blu active" href="#" onclick="return false;"><span>Отзывы</span><sup><?=$fcount?></sup><i>&nbsp;</i></a>
                <?if($scount>0):?>
                <a class="rosso" href="<?=$url?>/sale.html"><span>Распродажи</span><sup><?=$scount?></sup></a>
                <?endif;?>
                <?if($srcount>0):?>
                <a class="arancione" href="<?=$url?>/services.html"><span>Услуги</span></a>
                <?endif;?>                
            </td>
            <td>
                <div class="blue_blue_stripe">
                    <div class="blue_left">&nbsp;</div>
                    <div class="blue_right">&nbsp;</div>
                </div>
            </td>
        </tr>
    </tbody></table>
<?if(!isset($_COOKIE[Company_Feedback::KEY]) || !in_array($model->id,json_decode($_COOKIE[Company_Feedback::KEY])) || null === ($x=Company_Feedback::get(array('company_id'=>$model->id,'ip'=>$_SERVER['REMOTE_ADDR']),1))):?>
<div class="on_the_side_add">
    <a onclick="$('#form-feed').slideToggle();return false;" class="extra" href="#">Оставить отзыв</a>
</div>
<div class="product_comment" id="form-feed" style="<?if($c>0):?>display:none;<?endif;?>margin-bottom:10px">
<table width="100%">
    <tbody><tr>
            <td width="300px">
                <form action="<?=$url?>/feedback.html" method="post" id="feedback-form">
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
                        <input name="Feedback[rank]" value="<?=$F->rank?>" type="hidden" id="rank" />
                        <span><?=($F->rank>-1)?$mark[$F->rank]:'оцените продукт'?></span>
                    </div>
                    <div class="gray">
                        <i>*</i><span class="gray_inside"><b>Ваше имя</b> (Введите ваше настоящее имя)</span>
                    </div>
                    <input type="text" name="Feedback[name]" id="realname" value="<?=$F->name?>" />
                    <div class="gray">
                        <i>*</i><span class="gray_inside"><b>E-mail</b> (Укажите адрес вашей электронной почты)</span>
                    </div>
                    <input type="text" name="Feedback[email]" id="plus" value="<?=$F->email?>" />
                    <div class="gray">
                        <i>*</i><span class="gray_inside"><b>Общее впечатление</b></span>
                    </div>
                    <textarea rows="6" cols="150" name="Feedback[content]" id="exp" /><?=$F->content?></textarea>
                    <div class="send"><input type="submit" value="Отправить" class="photo"></div>
                </form>
            </td>
            <td style="">
                <!--div class="with_links">
                    <a onclick="$('#form-feed').slideToggle();return false;" class="roal_up" href="#">Свернуть форму</a><a class="up" href="#">&nbsp;</a><a class="down" href="#">&nbsp;</a>
                </div-->
            </td>
            <td style="padding-left:25px">
                <?if(empty($errors)):?>
                <div class="orange_popup">
                    <h4>Правила</h4>
                    <?=  SysSettings::getValue('Company.FeedbackRules', 'text', 'Правила отзыва', 'Текстовка', "<p>Отзыв о продукте следует основывать только на собственном опыте эксплуатации.
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
</div>
<?else:?>
<p>Спасибо за оставленный Вами отзыв.</p>
<?endif;?>
<?foreach ($models as $i=>$feed): ?>
<div class="shops_comment first<?=($i==0?' top':($i<$c?' first':''))?>top">

    <table width="50%" class="up">
        <tbody><tr>
                <td>
                    <span class="name"><?=$feed['name']?></span>
                </td>
                <td>
                    <span class="date">  <?=date('j.m.Y в H:i',$feed['created_at'])?></span>
                </td>
                <td>
                    <div class="star_links">
                        <?for($i=0;$i<$feed['rank'];$i++):?>
                        <a class="stars"><img width="16" height="16" src="/images/ostar.png"></a>
                        <?endfor;?>
                        <?for($i=$feed['rank'];$i<5;$i++):?>
                        <a class="stars"><img width="16" height="16" src="/images/gstar.png"></a>
                        <?endfor;?>
                    </div>
                </td>
                <td>
                    <span class="date"> <?=$mark[$feedback['rank']]?></span>
                </td>
            </tr>
        </tbody></table>
    <p><?=nl2br($feed['content'])?></p>

    <?/*<table class="lower">
        <tbody><tr>
                <td>
                    <span class="name">Доставка:</span> <span class="date"><?=$feed['delivery']?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="name">Цена:</span> <span class="date"><?=$feed['price']?></span>
                </td>
            </tr>
        </tbody></table>*/?>
</div>
<? endforeach; ?>
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
    </script>    