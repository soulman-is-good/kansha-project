<?php
//USER SESSION VARIABLES
$Tprop = "prop_$model->id";
$manu = "manu_$model->id";
//////////////////////////
$qman = array('@condition'=>array('manufacturer.status','group_id'=>$model->id,'ci.price'=>array(' > '=>'0')),
    '@join'=>"INNER JOIN shop_item ON manufacturer.id = shop_item.manufacturer_id INNER JOIN company_item ci ON ci.item_id=shop_item.id INNER JOIN prop_$model->id p ON p.id=shop_item.id",
    '@order'=>'manufacturer.weight, manufacturer.title');
if(X3::user()->$Tprop!=null) {
    if(isset(X3::user()->{$Tprop}['string'])){
        foreach(X3::user()->{$Tprop}['string'] as $key=>$val){
            if(is_array($val))
                $qman['@condition'][$key] = array('IN'=>"(".implode(',',$val).")");
            elseif($val>0)
                $qman['@condition'][$key] = $val;
        }
    }
    if(isset(X3::user()->{$Tprop}['boolean'])){
        foreach(X3::user()->{$Tprop}['boolean'] as $key=>$val)
           $qman['@condition'][$key]=1;
    }
    if(isset(X3::user()->{$Tprop}['scalar'])){
        foreach(X3::user()->{$Tprop}['scalar'] as $key=>$val){
            if(is_array($val)){
                $qman['@condition'][$key] = array('@@'=>"(`$key` BETWEEN {$val['from']} AND {$val['till']} OR `$key` IS NULL)");
            }elseif($val>0)
                $qman['@condition'][$key] = $val;
        }
    }
}
$ms = Manufacturer::get($qman);
$vman = array();
$hman = array();
$cache = X3::app()->groupTitle!=''?X3_String::create(X3::app()->groupTitle)->translit():'filterItems'.$model->id;
foreach ($ms as $i => $man) {
    if($i<20)
        $vman[]=$ms[$i];
    else
        $hman[]=$ms[$i];
}
$half = ceil(count($vman)/2);
$sdesc = '';
if(!empty(X3::app()->group_description)){
    $sdesc = (string)X3_String::create(trim(strip_tags(X3::app()->group_description)))->carefullCut(512);
}
?>
<div class="product_expand">
    <table class="production">
        <tbody><tr>
                <td style="white-space:nowrap;padding-right:10px;height:19px">
                    <h2><?=X3::app()->groupTitle?><sup><?=$count?></sup></h2>
                </td>
                <td style="width:100%">
                    <div class="black_stripe">
                        <div class="left_stripe_black">&nbsp;</div>
                        <div class="right_stripe_black">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <div class="product_left_side">
        <div class="product_left">
        <form method="post" id="filter-form" name="filter-form">
            <div class="product_inside_upper">
                <input type="submit" class="form_find" value="Подобрать">
                <noindex>
                <a class="second" rel="nofollow" href="/shop_Group/reset/id/<?=$model->id?>.html"><span class="cross">&nbsp;</span><span class="clear">Очистить</span></a>
                </noindex>
            </div>
            <?if($maxPrice > 0):?>
            <div class="product_inside_upper">
                <div class="slider_price">
                    <table>
                        <tbody><tr>
                                <td>
                                    <h4>Цена:</h4>
                                </td>
                                <td class="change_height">
                                    <span>от</span><input type="text" id="Price_min" name="Price[min]" value="<?=$priceMin?>">
                                </td>
                                <td class="change_height">
                                    <span>до</span><input type="text" id="Price_max" name="Price[max]" value="<?=$priceMax?>"><span><img height="8" width="7" src="/images/tenge3.png"></span>
                                </td>
                            </tr>
                        </tbody></table>
                </div>

                <div class="range">
                    <div class="numbers">
                        <div class="lnum"><?=X3_String::create($minPrice)->currency()?></div><div class="rnum"><?=X3_String::create($maxPrice)->currency()?></div>
                        <div class="cnum"><?=  X3_String::create((int)($maxPrice-$minPrice)/2)->currency()?></div>
                    </div>
                    <div class="lines"><div></div></div>
                </div>
                <div id="slider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a href="#" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 5.63063%;"></a><a href="#" class="ui-slider-handle ui-state-default ui-corner-all right" style="left: 28.1532%;"></a></div>
            </div>
            <?endif;?>
            <div class="product_inside_upper">
                <h4 style="margin-bottom:12px;margin-top:14px">Производитель:</h4>
                    <table width="100%">
                        <tbody><tr>
                                <td class="left_side_next">
                                    <?for($i=0;$i<$half;$i++):?>
                                    <input type="checkbox" value="<?=$vman[$i]->id?>"<?=(!is_null(X3::user()->$manu) && in_array($vman[$i]->id,X3::user()->$manu)) || $_GET['manuf']==$vman[$i]->name?' checked':''?> name="Manufacturer[]"><span><?=$vman[$i]->title?></span><br>
                                    <?endfor;?>
                                </td>
                                <td class="left_side_next">
                                    <?for($i=$half;$i<count($vman);$i++):?>
                                    <input type="checkbox" class="right_side" value="<?=$vman[$i]->id?>"<?=(!is_null(X3::user()->$manu) && in_array($vman[$i]->id,X3::user()->$manu)) || $_GET['manuf']==$vman[$i]->name?' checked':''?> name="Manufacturer[]"><span><?=$vman[$i]->title?></span><br>
                                    <?endfor;?>
                                </td>
                            </tr>
                        </tbody></table>

                </div>
            <?=  X3_Widget::run('@layouts:widgets:filterItems.php',array('model'=>$model),array('cache'=>true ,'cacheConfig'=>array('filename'=>$cache.'.cache')));?>
            <script type="text/javascript">
            <?if(!is_null(X3::user()->$Tprop)):?>
                var filters = <?=json_encode(X3::user()->$Tprop)?>;
                $(function(){
                    if(typeof filters['string'] != 'undefined')
                    for(i in filters['string'])
                        if(typeof filters['string'][i] == 'object')
                            for(j in filters['string'][i])
                                $("[name='Filter[string]["+i+"][]'][value='"+filters['string'][i][j]+"']").attr('checked',true);
                        else
                            $("[name='Filter[string]["+i+"]']").val(filters['string'][i])
                    if(typeof filters['scalar'] != 'undefined')
                    for(i in filters['scalar'])
                        $("[name='Filter[scalar]["+i+"]']").val(filters['scalar'][i])
                    if(typeof filters['boolean'] != 'undefined')
                    for(i in filters['boolean']){
                        $("[name='Filter[boolean]["+i+"]'][value='"+filters['boolean'][i]+"']").attr('checked',true);
                    }
                })
            <?endif;?>
                $(function(){
                    //$('.product_inside_upper a.second').click(function(){
                        //$.get($(this).attr('href'),function(m){
                            //location.href = m;//location.href.replace(/#.*$/,'');
                        //})
                        //return false;
                    //})
                    var maxh = $('.product_left').height();
                    var hresult = false;
                    var statusdiv = $('<div />').append(
                        $('<div></div>').css({'position':'absolute','right':'-14px','top':'20px','height':'0','width':'0','border':'6px solid transparent','border-left-color':'#2E6B99'})
                    )
                    .append('<span></span>')
                    .css({'position':'fixed','left':'8px','top':($(window).height()/2-40)+'px','padding':'5px',
                        'border':'2px solid #2E6B99','width':'70px','text-align':'center',
                    'border-radius':'10px','background':'#E0EAF2','color':'#AB8C4F','font':'12px Verdana, serif','display':'none'});
                    $('body').prepend(statusdiv);
                    $('#filter-form input, #filter-form select').change(function(e){
                        var top = $(e.target).position().top
                        $.post('/shop_Group/countup/id/<?=$model->id?>',$('#filter-form').serialize(),function(m){
                            statusdiv.css({'display':'block'}).children('span').html(m);
                            hresult = true;
                        })
                    })
                    $(window).scroll(function(){
                        if(!hresult) return true;
                        if($(document).scrollTop()>maxh && statusdiv.is(':visible'))
                            statusdiv.fadeOut();
                        else if($(document).scrollTop()<maxh && statusdiv.is(':hidden'))
                            statusdiv.fadeIn();
                    })
                })
            </script>
            <div class="product_inside_upper last">
                <input type="submit" class="form_find" value="Подобрать">
                <noindex>
                <a rel="nofollow" class="second" href="/shop_Group/reset/id/<?=$model->id?>.html"><span class="cross">&nbsp;</span><span class="clear">Очистить</span></a>
                </noindex>
            </div>

        </form>
        </div><!--product_left-->

<?//X3_Widget::run('@layouts:widgets:nowFind.php',array(),array('cache'=>false));?>

    </div><!--product_left_side-->

    <div class="product_right">
        <div class="product_right_blue" style="display: none">
            <table>
                <tbody><tr>
                        <td>
                            <div style="width:114px" class="with_blue_words">
                                <h4>Искать:</h4>
                                <ul>
                                    <li><a href="#">Смартфон</a></li>
                                    <li><a href="#">Сенсорный экран</a></li>
                                    <li><a href="#">2 SIM</a></li>
                                    <li><a href="#">WiFi</a></li>
                                    <li><a href="#">GPS</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="with_blue_words">
                                <h4>По цене:</h4>
                                <ul>
                                    <li><a href="#">дешевле 100$</a></li>
                                    <li><a href="#">100$ &ndash; 200$</a></li>
                                    <li><a href="#">200$ &ndash; 300$</a></li>
                                    <li><a href="#">300$ &ndash; 400$</a></li>
                                    <li><a href="#">дороже 400$</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="with_blue_words">
                                <h4>Платформа:</h4>
                                <ul>
                                    <li><a href="#">Android</a></li>
                                    <li><a href="#">Symbian</a></li>
                                    <li><a href="#">Windows</a></li>
                                    <li><a href="#">iPhone OS</a></li>
                                    <li><a href="#">Bada</a></li>
                                </ul>
                            </div>
                        </td>
                        <td width="68px">
                            <div class="with_blue_words">
                                <a class="hide" href="#">Cкрыть</a>
                                <a class="up" href="#">&nbsp;</a>
                                <a class="down" href="#">&nbsp;</a>
                            </div>
                        </td>
                    </tr>
                </tbody></table>
        </div><!--product_right_blue-->
        <?if(X3::app()->group_description!=''):?>
            <div id="shortdesc1" class="under_right_blue"><?=$sdesc?>
                    <a href="#" onclick="$('#shortdesc1, #longdesc1').toggle();return false;">читать дальше</a></div>
            <div id="longdesc1" class="under_right_blue" style="display: none"><?=X3::app()->group_description?>&nbsp;&nbsp;
            <a href="#" style="float:right" onclick="$('#shortdesc1, #longdesc1').toggle();return false;">свернуть</a>
            </div>
        <?endif;?>


        <div class="compare">
            <!--div style="height:12px" class="pustoi">&nbsp;</div--><table width="100%">
                <tbody><tr>
                        <td width="200px">
        <div style="float:none" class="navi">
            <?=$paginator?>
        </div>                            
                            <!--a class="compare_type" href="/compare.html"><span>Сравнить модели</span></a-->
                        </td>
                        <td>
                            <a style="display:none" class="single_line" href="#">Однострочный</a>
                        </td>
                        <td>
                            <div style="float:right;margin-top:20px" class="sale_selector expand">
                                <?=  X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
                            </div>
                        </td>


                    </tr>
                </tbody></table>
        </div>


            <? 
            X3::profile()->start('test02');
            $items = X3::db()->query($items);
            if(!is_resource($items)) die('DB ERROR!');
            $c = mysql_num_rows($items);
            $i = 0;
            $fs = X3::db()->fetch("SELECT SUM(`rank`) `summ` FROM shop_feedback WHERE item_id IN (SELECT id FROM shop_item WHERE group_id=$model->id)");
            if((int)$fs['summ']==0) $fs['summ']=1;
            while($item = mysql_fetch_array($items)):
            //foreach ($items as $i=>$item): 
                $mdl = new Shop_Item();
                $mdl->getTable()->acquire($item);
                $mdl->getTable()->setIsNewRecord(false);
                $item = (object)$item;
                $image = false;
                if(strpos($item->image,'http://')===0){
                    $image = $item->image;
                    $size = getimagesize($image);
                }elseif(is_file('uploads/Shop_Item/'.$item->image)){
                    $image = 'uploads/Shop_Item/122x122xf/'.$item->image;
                    if(is_file($image))
                        $size = getimagesize($image);
                    else
                        $size = array(122,122);
                    $image = "/$image";
                }
                $stat = X3::db()->fetch("SELECT MAX(price) as `max`, MIN(price) as `min`, COUNT(0) as `cnt` FROM company_item INNER JOIN data_company dc ON dc.id=company_item.company_id WHERE dc.status AND item_id=$item->id AND price>0");
                $fc = X3::db()->fetch("SELECT COUNT(0) `cnt`, SUM(`rank`) `summ` FROM shop_feedback WHERE item_id=$item->id");
                $sc = X3::db()->fetch("SELECT COUNT(0) AS `cnt` FROM company_service WHERE groups REGEXP '(,|\[)$item->group_id(,|\])'");
                $url = '/'.strtolower(X3_String::create($item->title)->translit()).($item->articule!=''?"-($item->articule)-":'-').$item->id;
                $rank = floor(5*$fc['summ']/$fs['summ']);
                $_s = 5-$rank;
                $itemtitle = $model->allheader==''?(I18n::single(X3::app()->groupTitle)." ".$item->title.($item->articule!=''?" ($item->articule)":'')):$mdl->prepareAttr($model->allheader);
                ?>
        <div class="expand_box<?=($i==$c-1)?' last':''?>" itemscope itemtype="http://schema.org/Offer">
            <?if($image):?>
            <div class="expand_box_pic">
                <div style="background-image:url(<?=$image?>);width:<?=$size[0]?>px;height:<?=$size[1]?>px;" class="expand_box_picture">&nbsp;</div>
                <div style="width:74px;" class="expand_box_pic_pic">
                    <div style="float:left;padding:5px 5px 5px 0"><input type="checkbox" value="<?=$item->id?>" name="compare[]"></div><span><a href="#" onclick="var k = $(this).parent().siblings('div').children('input');k.attr('checked',!k.is(':checked'));return false;">Добавить к сравнению</a></span><br>
                </div>
            </div>
            <?endif;?>

            <div class="expand_box_text">
                <h2 class="expand_header"><a itemprop="name" href="<?=$url?>.html"><?=($model->usename?$itemtitle:$item->suffix)?></a></h2>
                <h3><?=$item->properties_text//$item->propstext?>...</h3>
                <?/*if($stat['cnt']>0):?>
                <a class="blue" href="<?=$url?>/prices.html"><span>Цены</span><sup><?=$stat['cnt']?></sup></a>
                <?endif;?>
                <a class="blue" href="<?=$url?>.html"><span>Характеристики</span></a>
                <a class="blue" href="<?=$url?>/feedback.html"><span>Отзывы</span><sup><?=(int)$fc['cnt']?></sup></a>
                <a class="blue" href="<?=$url?>/services.html"><span>Услуги</span><sup><?=$sc['cnt']?></sup></a>*/?>
                <br>
                <?if($stat['cnt']>0):?>
                <?if($stat['min']>0 && $stat['min']<$stat['max']):?>
                <span itemprop="price" class="red_cost"><?=X3_String::create($stat['min'])->currency()?><i class="money">&nbsp;</i><i class="dash">-</i></span>
                <?endif;?>
                <span class="red_cost"><?=X3_String::create($stat['max'])->currency()?><i class="money">&nbsp;</i></span><br>
                <?endif;?>
                <div class="star_compar">
                    <div class="star_links">
                        <meta itemprop="ratingValue" content="<?=$rank?>" />
                        <?for($j=0;$j<$rank;$j++):?>
                        <a class="stars" href="<?=$url?>/feedback.html"><img height="16" width="16" src="/images/ostar.png"></a>
                        <?endfor;?>
                        <?for($j=0;$j<$_s;$j++):?>
                        <a class="stars" href="<?=$url?>/feedback.html"><img height="16" width="16" src="/images/gstar.png"></a>
                        <?endfor;?>
                        <a class="comment" href="<?=$url?>/feedback.html"><?=(int)$fc['cnt']?> <?=X3_String::create("Отзыва")->numeral($fc['cnt'],array("Отзыв","Отзыва","Отзывов"))?></a>
                    </div>
                    <?if($stat['cnt']>0):?>
                    <a class="product_info_price" href="<?=$url?>/prices.html">Сравнить цены</a>
                    <span class="black_text">В <?=$stat['cnt']?> <?=X3_String::create("магазинах")->numeral($stat['cnt'],array("магазине","магазинах","магазинах"))?></span>
                    <?else:?>
                    <a class="product_info_price" href="<?=$url?>.html">Подробнее</a>
                    <?endif;?>
                </div>
            </div>
        </div>
        <?
        $i++; endwhile;
        //endforeach; 
        X3::profile()->end('test02');
        ?>
        <div class="sale_selector expand">
            <?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
        </div>
        <div style="float:none" class="navi">
            <?=$paginator?>
        </div>
        <?=  X3_Widget::run('@layouts:widgets:news.php',array('model'=>$model),array('cache'=>false));?>

    </div><!--product_right-->
    <div class="clear-both">&nbsp;</div>
</div>