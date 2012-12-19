<?php
$items = X3::db()->query($models);
if(!is_resource($items)) die('DB ERROR!');
$i=0;
while($news = mysql_fetch_assoc($items)):
$model = new Article();
$model->getTable()->acquire($news);
$image = false;
if(is_file('uploads/Article/'.$model->image)){
    $image = '/uploads/Article/163x163xf/'.$model->image;
}
?>
<div class="mn_news news<?=$i==0?' first':''?>">
    <span><?=$model->date()?></span>
    <h3 style="margin-top:5px"><a style="margin:0" href="/article/<?=$model->id?>.html"><?=$model->title?></a></h3>
    <?if($image):?>
    <div style="background-image:url(<?=$image?>)" class="mn_news_photo">&nbsp;</div>
    <?endif;?>
    <p class="mn_news_text">
        <?=X3_String::create(strip_tags($model->text))->carefullCut(512);?>
    </p>
</div>
<?$i++;endwhile;?>