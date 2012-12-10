<?X3::profile()->start('news.widget');?>

<?php
$models = News::get(array('@condition'=>array('status'),'@order'=>'created_at DESC','@limit'=>4));
$arts = Article::get(array('@condition'=>array('status'),'@order'=>'created_at DESC','@limit'=>4));
$nc = $models->count();
$ac = $arts->count();
if($nc>0 || $ac>0):
?>
<div class="news_round_up">
            <table style="width:100%">
                <tbody><tr>
                        <?if($nc>0 && $ac>0):?>
                        <td width="220">
                            <div class="news_round_up_inside product">
                                <a class="news active" href="#anews"><span>Новости</span><i>&nbsp;</i></a>
                                <a class="news" href="#art"><span>Обзоры</span><i>&nbsp;</i></a>
                            </div>
                        </td>
                        <?elseif($nc>0):?>
                        <td width="110">
                            <div class="news_round_up_inside product">
                                <a class="news active" href="#"><span>Новости</span><i>&nbsp;</i></a>
                            </div>
                        </td>
                        <?elseif($ac>0):?>
                        <td width="110">
                            <div class="news_round_up_inside product">
                                <a class="news active" href="#"><span>Обзоры</span><i>&nbsp;</i></a>
                            </div>
                        </td>
                        <?endif;?>
                        <td>
                            <div class="blue_blue_stripe">
                                <div class="blue_left">&nbsp;</div>
                                <div class="blue_right">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </tbody></table>
        <?if($nc>0):?>
        <div id="anews" class="nnewsm">
        <? foreach ($models as $i=>$model): 
            $image = false;
            if(is_file('uploads/News/'.$model->image)){
                $image = "/uploads/News/139x83xf/".$model->image;
            }
            ?>
            <div class="mn_news<?=($i==$nc-1)?' last':''?>">
                <span><?=$model->date()?></span>
                <h3 style="margin-top:0px"><a style="display: inline;" class="active" href="/news/<?=$model->id?>.html"><?=$model->title?></a></h3>
                <div>
                <?if($image):?>
                    <div class="mn_news_pic"><img src="<?=$image?>"></div>
                <?endif;?>
                    <p><?=X3_String::create(str_replace('\r\n',"",strip_tags($model->text)))->carefullCut()?></p>
                </div>
            </div>
        <? endforeach; ?>
        </div>
        <?endif;?>
        <?if($ac>0):?>
        <div id="art" <?if($nc>0)echo ' style="display:none"'?> class="nnewsm">
        <? foreach ($arts as $i=>$model): 
            $image = false;
            if(is_file('uploads/Article/'.$model->image)){
                $image = "/uploads/Article/139x83xf/".$model->image;
            }
            ?>
            <div class="mn_news<?=($i==$nc-1)?' last':''?>">
                <span><?=$model->date()?></span>
                <h3 style="margin-top:0px"><a style="display: inline;" class="active" href="/article/<?=$model->id?>.html"><?=$model->title?></a></h3>
                <div>
                <?if($image):?>
                    <div class="mn_news_pic"><img src="<?=$image?>"></div>
                <?endif;?>
                    <p><?=X3_String::create(str_replace('\r\n',"",strip_tags($model->text)))->carefullCut()?></p>
                </div>
            </div>
        <? endforeach; ?>
        </div>
        <?endif;?>
    </div>
    
    <script type="text/javascript">
    $('.news_round_up_inside a.news').click(function(){
        if($(this).hasClass('active')) return false;
        $(this).siblings('a.news').removeClass('active');
        $(this).addClass('active');
        $('.nnewsm').css('display','none');
        $($(this).attr('href')).css('display','block')
        return false;
    })
    $('.mn_news h3 a').each(function(){$(this).click(function(){
            if(!$(this).hasClass('active')){
                $('.mn_news h3 a.active').removeClass('active').parent().siblings('div').slideUp();
                $(this).parent().siblings('div').slideDown();
                $(this).addClass('active');
                return false;
            }
        })
    })
    </script>
<?endif;?>
<?X3::profile()->end('news.widget');?>
