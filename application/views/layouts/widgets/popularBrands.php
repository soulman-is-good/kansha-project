<?X3::profile()->start('popularBrands.widget');?>

<?
$manus = Manufacturer::get(array('@condition'=>array('status'),'@order'=>'weight, title','@limit'=>10));
?>
<div class="popular_brand">
            <table>
                <tbody><tr>
                        <td width="146px">
                            <h2>Популярные производители</h2>
                        </td>
                        <td width="100%">
                            <div class="article_stripe">
                                <div class="article_stripe_left">&nbsp;</div>
                                <div class="article_stripe_right">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </tbody></table>

            <ul>
                <? foreach ($manus as $man): ?>
                <li><a href="/<?=strtolower(str_replace(' ', '_', $man->name))?>.html"><?=$man->title?></a></li>
                <? endforeach; ?>
            </ul>
            <a class="all_manufac" href="/manufactirers.html">Все производители</a>
        </div>
<?X3::profile()->end('popularBrands.widget');?>
