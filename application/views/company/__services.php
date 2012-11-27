<?php
$service = Company_Service::get(array('company_id'=>$model->id));
$sids = str_replace(array("[","]",'"'),array("(",")","'"),$service->services);
$gids = str_replace(array("[","]",'"'),array("(",")","'"),$service->groups);
$servs = X3::db()->fetchAll("SELECT * FROM data_service WHERE id IN $sids");
$grs = X3::db()->fetchAll("SELECT * FROM shop_group WHERE id IN $gids");
$grps = array();
if($servs == null) $servs = array();
if(!empty($grs))
foreach($grs as $g){
    $gg = explode(',',$g['category_id']);
    foreach($gg as $cat){
        if(!isset($grps[$cat]['model'])){
            $grps[$cat]['model'] = X3::db()->fetch("SELECT * FROM shop_category WHERE id=$cat");
        }
        $grps[$cat]['models'][]=$g['title'];
    }
}
$width = 320;
$width += $scount>0?140:0;
?>
<table class="three_inset change" width="100%">
    <tbody><tr>
            <td style="padding-right:10px;white-space:nowrap;width:<?=$width?>px">
                <a class="nero" href="<?= $url ?>.html"><span>О Компании</span></a>
                <a class="blu" href="<?= $url ?>/feedback.html"><span>Отзывы</span><sup><?= $fcount ?></sup></a>
                <? if ($scount > 0): ?>
                    <a class="rosso" href="<?= $url ?>/sale.html"><span>Распродажи</span><sup><?= $scount ?></sup></a>
                <? endif; ?>
                <a class="arancione active" onclick="return false;" href="#"><span>Услуги</span><i>&nbsp;</i></a>              
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
    <div class="upper_text_text">
        <p><?=$model->servicetext?></p>
        <div class="main_services_inside_left_link">
            <? foreach ($servs as $i=>$serv): ?>
            <a class="main_orange_link<?=$i==0?' active':''?>" href="/service/<?=$serv['name']?>.html"><?=$serv['title']?></a>
            <? endforeach; ?>
        </div>
        <ul class="mini_color_pic">
            <? foreach ($grps as $grp): ?>
            <li style="background-image:url(/uploads/Shop_Category/19x15xh/<?=$grp['model']['image']?>)"><b><?=$grp['model']['title']?>:</b> <span><?=implode(', ',$grp['models'])?></span></li>
            <? endforeach; ?>
        </ul>
    </div>


    <!--table width="100%" class="why_table">
        <tbody><tr>
                <td>
                    <table class="shops_upper_right">
                        <tbody><tr>
                                <td colspan="2">
                                    <h4>Контакты в Алмате</h4>
                                </td>
                            </tr>
                            <tr>

                                <td class="first">
                                    <span>Телефоны:</span>
                                </td>
                                <td>
                                    <ul>
                                        <li>8 (727) 297 11 01</li>
                                        <li>8 (727) 297 11 00</li>

                                    </ul>
                                </td>

                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Факс:</span>
                                </td>
                                <td>
                                    <ul>
                                        <li>8 (727) 297 11 02</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr class="pustoi">
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Адрес: </span>
                                </td>
                                <td class="second">
                                    <span>г. Алматы, Коктем-2, дом 19а </span>
                                </td>
                            </tr>
                            <tr class="pustoi"><td>&nbsp;</td></tr>
                            <tr>
                                <td class="first">
                                    <span>Сайт:</span>
                                </td>
                                <td>
                                    <a class="black" href="#">www.alsi.kz</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>E-mail:</span>
                                </td>
                                <td>
                                    <a class="black" href="#">sales@alsi.kz</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Skype:</span>
                                </td>
                                <td>
                                    <a class="black" href="#">alsi.kz</a>
                                </td>
                            </tr>
                            <tr class="pustoi"><td>&nbsp;</td></tr>
                            <tr>
                                <td class="first">
                                    <span>Время работы:</span>
                                </td>
                                <td>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">в</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day" href="#">ч</a>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day active" href="#">в</a>
                                </td>
                            </tr>	
                        </tbody></table>
                </td>

                <td>
                    <table class="shops_upper_right">
                        <tbody><tr>
                                <td colspan="2">
                                    <h4>Контакты в Астане</h4>
                                </td>
                            </tr>
                            <tr>

                                <td class="first">
                                    <span>Телефоны:</span>
                                </td>
                                <td>
                                    <ul>
                                        <li>8 (727) 297 11 01</li>
                                        <li>8 (727) 297 11 00</li>

                                    </ul>
                                </td>

                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Факс:</span>
                                </td>
                                <td>
                                    <ul>
                                        <li>8 (727) 297 11 02</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr class="pustoi">
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Адрес: </span>
                                </td>
                                <td class="second">
                                    <span>г. Алматы, Коктем-2, дом 19а </span>
                                </td>
                            </tr>
                            <tr class="pustoi"><td>&nbsp;</td></tr>
                            <tr>
                                <td class="first">
                                    <span>Сайт:</span>
                                </td>
                                <td>
                                    <a class="black" href="#">www.alsi.kz</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>E-mail:</span>
                                </td>
                                <td>
                                    <a class="black" href="#">sales@alsi.kz</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="first">
                                    <span>Skype:</span>
                                </td>
                                <td>
                                    <a class="black" href="#">alsi.kz</a>
                                </td>
                            </tr>
                            <tr class="pustoi"><td>&nbsp;</td></tr>
                            <tr>
                                <td class="first">
                                    <span>Время работы:</span>
                                </td>
                                <td>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">в</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day" href="#">ч</a>
                                    <a class="day" href="#">п</a>
                                    <a class="day" href="#">с</a>
                                    <a class="day active" href="#">в</a>
                                </td>
                            </tr>	
                        </tbody></table>
                </td>
            </tr>
        </tbody></table><!--why_table-->

</div>