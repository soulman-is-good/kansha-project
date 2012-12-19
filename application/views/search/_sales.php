<?php
$sales = X3::db()->query($models);
if(is_resource($sales)):
while($sale = mysql_fetch_assoc($sales)):
$company = Company::getByPk($sale['company_id']);
    if($company!=null && is_file('uploads/Company/'.$company->image)){
        $image = "/uploads/Company/88x31xh/$company->image";
    }
?>
<div class="sale_box">
    <div style="background-image:url(/uploads/Sale/122x122xw/<?=$sale['image']?>);height:122px;width:122px" class="sale_box_pic">&nbsp;</div>
    <div class="sale_box_text">
        <h1><a href="/sale/<?=$sale['id']?>.html"><?=$sale['title']?></a></h1>
        <p><?=X3_String::create(strip_tags($sale['text']))->carefullCut(512)?> <a class="more" href="/sale/<?=$sale['id']?>.html">подробнее...</a></p>
        <?if($sale['oldprice']>0):?>
        <div class="old_price">
            <span class="red_price"><span class="red_cost">Старая цена:<b><?=$sale['oldprice']?></b></span><i>&nbsp;</i></span>
        </div>
        <?endif;?>
        <div class="green_white">
            <span class="white_text"><?=$sale['price']?><i>&nbsp;</i><span class="white_text_text">до <?=Sale::getInstance()->date($sale['ends_at'])?></span></span>
            <div class="angle">&nbsp;</div>
            <?if($sale['title']!=''):?>
            <a class="gift" href="/sale/<?=$sale['id']?>.html">+ Подарок</a>
            <?endif;?>
        </div>
<?if($company!=null):?>
        <div class="sale_box_text_image">
            <a style="text-decoration: none;" href="<?=$company->getLink()?>.html">
            <img src="<?=$image?>" title="<?=  addslashes($company->title)?>" />
            </a>
            <noindex>
                <a class="go_next" rel="nofollow" target="_blank" href="/company/go/to/<?=  base64_encode($company->id)?>">Перейти на сайт продавца</a>
            </noindex>
            <?/*<span>135 просмотров</span>*/?>
        </div>
<?endif;?>        
    </div>
    <div style="height:17px" class="pustoi">&nbsp;</div>
</div>
<?endwhile?>
<?endif;?>