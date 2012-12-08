<?X3::profile()->start('newsMain.widget');?>

<?php
$models = News::get(array('@condition'=>array('status'),'@order'=>'created_at DESC','@limit'=>4));
$articles = Article::get(array('@condition'=>array('status'),'@order'=>'created_at DESC','@limit'=>4));
$ac = $articles->count();
if(($nc = $models->count())>0 || ($ac)>0):
?>
<table class="main">
    <tr>
        <?if($nc>0):?>
        <td class="wnews" width="50%" style="<?=$ac>0?'padding-right:20px':''?>">
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
                <span style="margin-top:0px" class="<?=($i==$nc-1?'last':($i==0?'first':''))?>"><?=$model->date()?></span>
                <h3 data-id="#n<?=$model->id?>"><a href="/news/<?=$model->id?>.html"><?=$model->title?></a></h3>
                <div id="n<?=$model->id?>" class="news-content-widget">
                    <?if(is_file('uploads/News/'.$model->image)):?>
                    <div class="mn_news_pic"><img src="/uploads/News/139x83xf/<?=$model->image?>" /></div>
                    <?endif;?> 
                    <p style="margin-bottom:0px"><?=X3_String::create(str_replace('\r\n',"",strip_tags($model->text)))->carefullCut()?>
                        &nbsp;<a href="/news/<?=$model->id?>.html">читать далее</a>
                    </p>
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
                <span style="margin-top:0px" class="<?=($i==$nc-1?'last':($i==0?'first':''))?>"><?=$model->date()?></span>
                <h3 data-id="#a<?=$model->id?>"><a href="/article/<?=$model->id?>.html"><?=$model->title?></a></h3>
                <div id="a<?=$model->id?>" class="news-content-widget">
                    <?if(is_file('uploads/Article/'.$model->image)):?>
                    <div class="mn_news_pic"><img src="/uploads/Article/139x83xf/<?=$model->image?>" alt="" /></div>
                    <?endif;?>
                    <p><?=X3_String::create(str_replace("\r\n","",strip_tags($model->text)))->carefullCut()?>&nbsp;
                    <a href="/article/<?=$model->id?>.html">читать далее</a></p>
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
        //$('.mn_news h3 a').click(function(){
            //if($(this).hasClass('active')) return false;
            //$(this).parent().parent().find('a.active').removeClass('active');
            //$(this).addClass('active');
            //$('.news-content-widget').slideUp();
            //$($(this).parent.data('id')).slideDown();
            //return false;
        //})
    })
</script>
<?endif;?>
<?X3::profile()->end('newsMain.widget');?>
