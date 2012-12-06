<?php
$class = $class . "_Feedback";
$model = X3_Module::getInstance($class);
$table = $model->tableName;
$q = '1';
if(isset($query)){
    $q = $model->getTable()->formCondition($query);
}
$total = X3::db()->fetch("SELECT SUM(`rank`) `summ`, COUNT(`id`) `cnt` FROM $table WHERE status AND $q");

$count = $total['cnt'];
$text = X3_String::create("Отзыв")->numeral($count,array('Отзыв','Отзыва','Отзывов'));
$stars = ($total['cnt']>0)?ceil($total['summ']/$total['cnt'])+1:0;
$left = 5 - $stars;
?>
<div class="star_links" rank="<?=$count?>">
    <meta itemprop="ratingValue" content="<?=$stars?>" />
    <?for($i=0;$i<$stars;$i++):?>
    <a href="<?=$url?>" class="stars"><img src="/images/ostar.png" width="16" height="16"></a>
    <?endfor;?>
    <?for($i=0;$i<$left;$i++):?>
    <a href="<?=$url?>" class="stars"><img src="/images/gstar.png" width="16" height="16"></a>
    <?endfor;?>
    <a href="<?=$url?>" class="comment"><?=$count?> <?=$text?></a>
</div>