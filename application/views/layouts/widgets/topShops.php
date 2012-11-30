<?X3::profile()->start('topShop.widget');?>

<?php
$mnth = (int)date('n');
$day = (int)date('d');
if($day-1==0)
    $day = date("t",mktime(0, 0, 0, ($mnth-1), 1));
$k1 = mktime(0, 0, 0, $mnth, $day-1);
$k2 = mktime(23, 59, 29, $mnth, $day-1);
$d1 = mktime(0, 0, 0, $mnth, $day);
$d2 = mktime(23, 59, 29, $mnth, $day);
$d3 = mktime(0, 0, 0, $mnth, 1);
$d4 = mktime(23, 59, 29, $mnth, date("t"));
$companies = X3::db()->fetchAll("SELECT id, title, image, login, 
        (SELECT COUNT(0) FROM company_itemstat WHERE company_id=data_company.id) AS k, 
        (SELECT COUNT(0) FROM company_itemstat WHERE company_id=data_company.id AND time BETWEEN $d1 AND $d2) AS d1, 
        (SELECT COUNT(0) FROM company_itemstat WHERE company_id=data_company.id AND time BETWEEN $d3 AND $d4) AS d2 
    FROM data_company WHERE status ORDER BY d1 DESC, k DESC, weight, title LIMIT 3");
?>
<div class="top_markets">
    <table class="market_stripe">
        <tr>
            <td>
                <h2>Топ-магазины</h2>
            </td>
            <td>
                <div class="blue_stripe">
                    <div class="radius_left">&nbsp;</div>
                    <div class="radius_right">&nbsp;</div>
                </div>
            </td>
        </tr>
    </table>
    <div class="clear-both">&nbsp;</div>
    <a href="/page/start_selling.html" class="red_link_offer">Хотите добавить свой интернет-магазин?</a>
    <? $c = count($companies);foreach ($companies as $i=>$comp): 
        $name = X3_String::create($comp['title'])->translit();
        $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
        ?>
    <div class="market_box<?=($i==$c-1)?' last':''?>">
        <a href="/<?=$name?>-company<?=$comp['id']?>.html" title="<?=$comp['title']?>"><div class="market_box_picture" style="background-image:url(/uploads/Company/80x27xw/<?=$comp['image']?>)">&nbsp;</div></a>
        <p class="market_text">Просмотрено товаров:<br/>
            Сегодня <span><?=$comp['d1']?></span><br/>
            <?=I18n::months($mnth-1)?> <span><?=$comp['d2']?></span> </p>
        <div class="clear-both">&nbsp;</div>
    </div>
    <? endforeach; ?>
    <a href="/shops.html">Все магазины</a>
</div>
<?X3::profile()->end('topShop.widget');?>
