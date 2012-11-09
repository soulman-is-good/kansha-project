<?X3::profile()->start('newsMain.widget');?>

<?php
$models = News::get(array('@condition'=>array('status'),'@order'=>'created_at DESC','@limit'=>4));
$articles = Article::get(array('@condition'=>array('status'),'@order'=>'created_at DESC','@limit'=>4));
if(($nc = $models->count())>0 || ($ac = $articles->count())>0):
?>
<table class="main">
    <tr>
        <?if($nc>0):?>
        <td class="wnews" width="50%">
            <div class="mn_news">
                <table class="blue_stripes" style="width:100%">
                    <tr>
                        <td style="width:75px">
                            <h2>Новости</h2>
                        </td>
                        <td>
                            <div class="one_blue_stripe">
                                <div class="blue_left">&nbsp;</div>
                                <div class="blue_right">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </table>
                <? foreach ($models as $i=>$model): ?>            
                <span class="<?=($i==$nc-1?'last':($i==0?'first':''))?>"><?=$model->date()?></span>
                <h3><?=$model->title?></h3>
                <div class="news-content-widget" <?if($nc>1):?>style="display:none;"<?endif;?>>
                    <div class="mn_news_pic"><img src="/uploads/News/139x83xf/<?=$model->image?>" />
                    </div>
                    <p><?=X3_String::create(str_replace('\r\n',"",strip_tags($model->text)))->carefullCut()?></p>
                    <div class="clear-both">&nbsp;</div>
                    <div class="pustoi" style="height:8px">&nbsp;</div>
                </div>
                <? endforeach; ?>
            </div>
            <a href="/news.html" class="all_news">Все новости</a>
        </td>
        <?endif;?>
        <?if($ac>0):?>
        <td class="woverlook" width="50%">
                <table class="one_black_stripe">
                    <tr>
                        <td style="width:72px">
                            <h2>Обзоры</h2>
                        </td>
                        <td>
                            <div class="black_stripe_long">
                                <div class="black_left">&nbsp;</div>
                                <div class="black_right">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </table>

            <div class="clear-both">&nbsp;</div>
            <? foreach ($articles as $i=>$model): ?>            
            <div class="overlook">
                <span class="<?=($i==$nc-1?'last':($i==0?'first':''))?>"><?=$model->date()?></span>
                <h3><?=$model->title?></h3>
                <div class="news-content-widget" style="display:none;">
                    <div class="mn_news_pic"><img src="/uploads/Article/139x83xf/<?=$model->image?>" alt="o" />
                    </div>
                    <p><?=X3_String::create(str_replace('\r\n',"",strip_tags($model->text)))->carefullCut()?></p>
                    <div class="clear-both">&nbsp;</div>
                </div>
            </div>
            <div class="clear-both">&nbsp;</div>
            <? endforeach; ?>            
            <a href="/articles.html" class="all_news">Все обзоры</a>
        </td>
        <?endif;?>
    </tr>
</table>
<script type="text/javascript">
    $(function(){
        $('.wnews h3, .woverlook h3').each(function(){
            $(this).css('cursor','pointer').click(function(){
                $(this).siblings('.news-content-widget').slideToggle();
            })
        })
    })
</script>
<?endif;?>
<?X3::profile()->end('newsMain.widget');?>
