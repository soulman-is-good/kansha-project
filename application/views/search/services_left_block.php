<?php
?>
<div style="margin-top:-22px" class="product_left_side search">
    <?if(!empty($models['services'])):   ?>
    <table class="search_list">
        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px">
                    <span>Название</span>
                </td>
                <td width="100%">
                    <div class="orange_company">
                        <div class="orange_left">&nbsp;</div>
                        <div class="orange_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
        </tbody></table>
    <ul style="margin-top:5px" class="list serviceBranch">
        <? foreach ($models['services'] as $z=>$value): if($z>19) break; ?>        
        <li><a href="/service/<?=$value['name']?>.html"><?=$value['title']?></a></li>
        <? endforeach; ?>
        <li><a href="/services.html" class="last">Все услуги</a></li>
    </ul>
<?endif;?>
<?if(!empty($models['groups'])):?>
    <div style="margin-right:0px" class="branch">
        <table class="branch_stripe">
            <tbody><tr>
                    <td style="padding-right:10px;white-space:nowrap;height:19px">
                        <h2>Разделы</h2>
                    </td>
                    <td width="100%">
                        <div class="branch_long_stripe">
                            <div class="dark_left">&nbsp;</div>
                            <div class="dark_right">&nbsp;</div>
                        </div>
                    </td>
                </tr>
            </tbody></table>
        <ul>
            <?foreach($models['groups'] as $g):
                $link = Shop_Group::getLink($g['id'], null, $g['title']);
            ?>
            <li><a href="/<?=$link?>.html"><?=$g['title']?></a></li>
            <?endforeach;?>
            <li><a class="all_branch" href="/vse-razdely.html">Все разделы</a></li>
        </ul>
    </div>
<?endif;?>

</div>