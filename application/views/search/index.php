<div class="product_expand">
    <div style="margin-top:20px;" class="news_round_up">
        <?if(($numbers[0] | $numbers[1] | $numbers[2] | $numbers[3] | $numbers[4]) !== 0):?>
        <table width="100%" style="margin-bottom:6px">
            <tbody><tr>
                    <td width="<?=$width?>" style="padding-right:10px;white-space:nowrap;height:19px">
                        <div class="des_header_links search">
                            <?if($numbers[0]>0):?>
                            <a style="margin-left:0px" class="green_green<?=$type=='items'?' active':''?>" href="/search/type/items.html?q=<?=$_GET['search']?>"><span>Товары<i>&nbsp;</i></span><sup><?=$numbers[0]?></sup></a>
                            <?endif;?>
                            <?if($numbers[1]>0):?>
                            <a class="blue_blue<?=$type=='news'?' active':''?>" href="/search/type/news.html?q=<?=$_GET['search']?>"><span>Новости<i>&nbsp;</i></span><sup><?=$numbers[1]?></sup></a>
                            <?endif;?>
                            <?if($numbers[2]>0):?>
                            <a class="black_black<?=$type=='articles'?' active':''?>" href="/search/type/articles.html?q=<?=$_GET['search']?>"><span>Обзоры<i>&nbsp;</i></span><sup><?=$numbers[2]?></sup></a>
                            <?endif;?>
                            <?if($numbers[3]>0):?>
                            <a style="margin-left:15px" class="orange_orange<?=$type=='services'?' active':''?>" href="/search/type/services.html?q=<?=$_GET['search']?>"><span>Услуги<i>&nbsp;</i></span><sup><?=$numbers[3]?></sup></a>
                            <?endif;?>
                            <?if($numbers[4]>0):?>
                            <a style="margin-left:15px" class="pink_pink<?=$type=='sales'?' active':''?>" href="/search/type/sales.html?q=<?=$_GET['search']?>"><span>Распродажи<i>&nbsp;</i></span><sup><?=$numbers[4]?></sup></a>
                            <?endif;?>
                        </div>
                    </td>
                    <td>
                        <?if($type=='items'):?>
                        <div class="green_green_stripe" style="margin-top:15px;">
                            <div class="green_green_left">&nbsp;</div>
                            <div class="green_green_right">&nbsp;</div>
                        </div>
                        <?elseif($type=='news'):?>
                        <div class="blue_blue_stripe" style="margin-top:15px;">
                            <div class="blue_left">&nbsp;</div>
                            <div class="blue_right">&nbsp;</div>
                        </div>
                        <?elseif($type=='articles'):?>
                        <div class="black_stripe">
                            <div class="left_stripe_black">&nbsp;</div>
                            <div class="right_stripe_black">&nbsp;</div>
                        </div>
                        <?elseif($type=='services'):?>
                        <div class="orange_company">
                            <div class="orange_left">&nbsp;</div>
                            <div class="orange_right">&nbsp;</div>
                        </div>
                        <?elseif($type=='sales'):?>
                        <div class="rosso_stripe">
                            <div class="left_stripe_rosso">&nbsp;</div>
                            <div class="right_stripe_rosso">&nbsp;</div>
                        </div>
                        <?endif;?>
                    </td>
                </tr>
            </tbody></table>
        <div class="search">
<?= ($block!=false)?X3_Widget::run("@views:search:{$type}_left_block.php",array('models'=>$block)):''?>
            <div class="product_right"<?=(empty($block))?' style="margin-left:0"':''?>>
                <table width="100%" class="for_selector">
                    <tbody><tr>
                            <td>
                                <div style="margin-top:-38px" class="sale_selector expand">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
                                </div>
                            </td>
                        </tr>
                    </tbody></table>

<?=  X3_Widget::run("@views:search:_$type.php",array('models'=>$models))?>

                <div style="margin-top:22px" class="sale_selector expand">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
                </div>
                <div style="float:none" class="navi">
<?=$paginator?>
                </div>
            </div>


        </div>
<?else:?>
        <div class="search">
            Ничего не найдено. Повторите поиск с другими параметрами.
        </div>
<?endif;?>

    </div>

    <?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:25px;margin-bottom:20px'));?>


</div>