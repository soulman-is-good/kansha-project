<div class="main_popular">
    <table class="product_sale">
        <tbody><tr>
                <td style="width:240px">
                    <h2>Товары на распродаже<sup><?=$saleCount?></sup></h2>
                </td>
                <td>
                    <div class="popular_sale_stripe">
                        <div class="sale_left_stripe">&nbsp;</div>
                        <div class="sale_right">&nbsp;</div>
                    </div>

                </td>
            </tr>
        </tbody></table>
    <?/*
    <div class="sale_list">
        <ul>
            <li><a href="#">Мобильные телефоны</a><sup>12</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Ноутбуки</a><sup>35</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Фотоаппараты</a><sup>43</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Телевизоры</a><sup>16</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">MP3 плееры</a><sup>7</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Стиральные машины</a><sup>21</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Холодильники</a><sup>18</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Планшет</a>ы<sup>2</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Электроинструмент</a><sup>40</sup></li>
            <li><a href="#" hidefocus="true" style="outline: medium none;">Компьютеры</a><sup>55</sup></li>
        </ul>
    </div>*/?>
    <div class="sale_selector" style="float:right">
        <?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
    </div>

    <div class="sale_left" style="margin-left:0px;">
        <?$c=$models->count();foreach($models as $i=>$model):
        $company = Company::getByPk($model->company_id);
        ?>
        <div class="sale_box<?=($i==$c-1)?'last':''?>">
            <div style="background-image:url(/uploads/Sale/120x120/<?=$model->image?>);height:120px;width:120px" class="sale_box_pic">&nbsp;</div>
            <div class="sale_box_text">
                <h1><a href="/sale/<?=$model->id?>.html"><?=$model->title?></a></h1>
                <p>
                    <?=X3_String::create(strip_tags($model->text))->carefullCut(512)?>
                    <a href="/sale/<?=$model->id?>.html" class="more">подробнее...</a>
                </p>
                <?if($model->oldprice>0):?>
                <div class="old_price">
                    <span class="red_price"><span class="red_cost">Старая цена:<b><?=X3_String::create($model->oldprice)->currency();?></b></span><i>&nbsp;</i></span>
                </div>
                <?endif;?>
                <div class="green_white">
                    <span class="white_text"><?=X3_String::create($model->price)->currency();?><img width="7" height="9" src="/images/white_t.png"> <span class="white_text_text">до <?=$model->date()?></span></span>
                    <div class="angle">&nbsp;</div>
                </div>

                <div class="sale_box_text_image">
                    <img src="/uploads/Company/88x31xh/<?=$company->image?>" alt="<?=$company->title?>" title="<?=$company->title?>" />
                    <a class="go_next" href="/company/go/to/<?=base64_encode($company->id)?>">Перейти на сайт продавца</a>
                </div>
            </div>
            <div style="height:17px" class="pustoi">&nbsp;</div>
        </div>
        <?endforeach;?>

        <div style="float:right;margin-top:20px" class="sale_selector">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
        </div>
        <div style="float:none" class="navi">
            <?=$paginator?>
        </div>
    </div>
<?=X3_Widget::run('@layouts:widgets:BannerBottom.php')?>
</div>