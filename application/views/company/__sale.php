<?
$acnt = X3::db()->count("SELECT 0 FROM data_address WHERE status");
$width = 360;
$width += $srcount>0?80:0;
$width += $acnt>0?90:0;
$models = X3::db()->query("SELECT * FROM data_sale WHERE status AND ends_at>'".time()."' AND company_id='$model->id'");
$groups = X3::db()->query("SELECT id, title FROM shop_group WHERE status AND id IN (SELECT group_id FROM data_sale WHERE status AND ends_at>'".time()."' AND company_id='$model->id') ORDER BY title");
?>
<table class="three_inset" width="100%">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;width:<?=$width?>px">
                <a class="nero" href="<?= $url ?>.html"><span>О Компании</span></a>
                <?if($acnt>0):?>
                <a class="des_green" href="<?=$url?>/address.html"><span>Адреса<i>&nbsp;</i></span></a>
                <?endif;?>                
                <a class="blu" href="<?= $url ?>/feedback.html"><span>Отзывы</span><sup><?= $fcount ?></sup></a>
                <a class="rosso active" href="#" onclick="return false;"><span>Распродажи<i>&nbsp;</i></span><sup><?= $scount ?></sup></a>
                <? if ($srcount > 0): ?>
                <a class="arancione" href="<?=$url?>/services.html"><span>Услуги</span></a>
                <? endif; ?>
            </td>
            <td>
                <div class="orange_company">
                    <div class="orange_left">&nbsp;</div>
                    <div class="orange_right">&nbsp;</div>
                </div>
            </td>
        </tr>
    </tbody></table>
<div class="upper_profile_text">
    <div style="height:17px" class="pustoi">&nbsp;</div>
    <?if(is_resource($models) && $scount>0):?>
<?/*    
<table width="100%" class="with_select" style="margin-bottom:19px">
        <tbody><tr>
                <td>
                        <div class="rank">
                        <form type="post" action="/company/sale.html">
                        <span>Категория:</span>
                        <select name="group">
                            <?while($group = mysql_fetch_assoc($groups)):
                                $props = X3::db()->query("SELECT id, title FROM shop_proplist sp WHERE group_id='{$group['id']}' status AND  id IN (SELECT property_id FROM data_sale WHERE status AND ends_at>'".time()."' AND company_id='$model->id') ORDER BY title");
                                if(is_resource($props) && mysql_fetch_assoc($props)>0):
                                while($prop = mysql_fetch_assoc($props)):
                                ?>
                                <?if(X3::user()->SalePropid == $prop['id'] && X3::user()->SaleGroupid==$group['id']):?>
                                <option selected="selected" value="<?=$group['id']?>-<?=$prop['id']?>"><?=$prop['title']?></option>
                                <?else:?>
                                <option value="<?=$group['id']?>-<?=$prop['id']?>"><?=$prop['title']?></option>
                                <?endif;?>
                                <?endwhile;else:?>
                                <?if(X3::user()->SalePropid == 0 && X3::user()->SaleGroupid==$group['id']):?>
                                <option selected="selected" value="<?=$group['id']?>-0"><?=$group['title']?></option>
                                <?else:?>
                                <option value="<?=$group['id']?>-0"><?=$group['title']?></option>
                                <?endif;?>
                                <?endif;?>
                            <?endwhile;?>
                        </select>
                        </form>
                        </div>

                </td>
                <td width="213px">
                        <div class="selector_shops sort_by" style="display:none">
                                <form>
                                <span>Сортировать по:</span>
                                <select size="1" name="product">
                                        <option value="#">популярности</option>
                                        <option value="#">Отзывам</option>
                                        <option value="kurierom">Рейтингу</option>
                                </select>
                                </form>
                        </div>
                </td>
                <td width="120px">
                        <div class="sale_selector" style="display:none;margin-bottom:0">
                                <form>
                                <span>Показать по:</span>
                                <select size="1" name="product">
                                        <option value="#">10 </option>
                                        <option value="#">5</option>
                                        <option value="kurierom">20</option>
                                </select>
                                </form>
                        </div>
                </td>
        </tr>
</tbody></table>    */?>
        <? while ($sale = mysql_fetch_assoc($models)): 
$company = Company::getByPk($sale['company_id']);
    if($company!=null && is_file('uploads/Company/'.$company->image)){
        $image = "/uploads/Company/88x31xh/$company->image";
    }
    ?>
    <div class="sale_box">
            <div style="background-image:url(/uploads/Sale/122x122xw/<?=$sale['image']?>);height:122px;width:122px;" class="sale_box_pic">&nbsp;</div>
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
        <? endwhile; ?>
    <?else:?>
    <div class="upper_text_text">
        <p>Пока нет распродаж</p>
    </div>    
    <?endif;?>
</div>