<?php
if(!isset($query)) $query = '';
if(isset($_GET['city'])){
    if($_GET['city']=='all'){
        X3::user()->region = null;
    }else{
        $c = mysql_real_escape_string($_GET['city']);
        $city = Region::get(array('name'=>$c),1);
        if($city!=null){
            X3::user()->region = array($city->id,'В '.Address::verb($city->title));
        }
    }
}
$aquery = '';
if(X3::user()->region!=null && X3::user()->region[0]>0){
    $query.=' AND dc.id IN (SELECT company_id FROM data_address da WHERE type=1 AND city='.X3::user()->region[0].')';
    $aquery = " AND city='".X3::user()->region[0]."'";
}elseif(X3::user()->region!=null)
   X3::user()->region = null;
$cities = X3::db()->fetchAll("SELECT data_region.id, data_region.title FROM data_region INNER JOIN data_address ON data_address.city=data_region.id INNER JOIN data_company dc ON data_address.company_id=dc.id WHERE dc.status AND data_address.type=1 AND data_region.status GROUP BY data_region.id ORDER BY data_region.title");
$services = X3::db()->query("SELECT ds.id,ds.name,ds.title FROM data_service ds WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.services LIKE CONCAT('%\"',ds.id,'\"%'))>0 ORDER BY title");
//$cnt = X3::db()->count("SELECT 0 FROM data_company WHERE status $query");
//$paginator = new Paginator('Service',$cnt['cnt']);
$companies = X3::db()->query("SELECT dc.* FROM data_company dc INNER JOIN company_service cs ON cs.company_id=dc.id WHERE dc.status AND (cs.services<>'[]' OR cs.groups<>'[]') AND cs.groups LIKE '%\"$model->group_id\"%' $query ORDER BY isfree, weight, title");// LIMIT $paginator->offset, $paginator->limit");
$scomp = X3::db()->fetchAll("SELECT * FROM company_service");
$igr = array();
foreach ($scomp as $i => $sc) {
    $grs = json_decode($sc['groups']);
    $igr = array_merge($igr,  array_diff($grs, $igr));
}
$igr = implode(',', $igr);
$groups = X3::db()->query("SELECT * FROM shop_group WHERE status AND id IN ($igr)");

//foreach($companies as $j=>$comp) if(empty($companies[$j]['services'])) unset($companies[$j]);
$ccount = 0;
if(is_resource($companies))
    $scount = $ccount = mysql_num_rows($companies);
?>
<div class="table_des">
    <table class="des_header change" width="100%">

        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px;width:460px">
                    <div class="des_header_links" style="margin-top: 0px">
                                <a style="margin-left:0px" class="black_black" href="<?=$url?>.html"><span>Характеристики</span><i>&nbsp;</i></a>
                                <a style="margin-left:10px" class="green_green" href="<?=$url?>/prices.html"><span>Где купить?</span><sup><?=$mm['cnt']?></sup><i>&nbsp;</i></a>
                                <a class="blue_blue" href="<?=$url?>/feedback.html"><span>Отзывы</span><sup><?=$fcount?></sup><i>&nbsp;</i></a>
                                <a class="orange_orange active" href="<?=$url?>/services.html"><span>Услуги<i>&nbsp;</i></span><sup><?=$scount?></sup></a>
                    </div>
                </td>
                <td>
                    <div class="orange_company" style="margin-top: 11px">
                        <div class="orange_left">&nbsp;</div>
                        <div class="orange_right">&nbsp;</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="one_more_text">
                        <?=SysSettings::getValue('Shop_Item.Text', 'text', 'Услуги к товару', 'Текстовка', "<p>Раздел \"Услуги\" портала Kansha.kz предназначен для быстрого поиска необходимой услуги среди большого числа компаний в Казахстане.
                        Для быстрого поиска услуги вы можете воспользоваться пунктом меню \"Найти объявление\".</p>
                        <p>Если Вас интересует конкретная услуга (например, ремонт), просто выберите ее в пункте меню \"Виды услуг\" и перейдите к списку компаний, оказывающих данную услугу. Услуги разбиты по видам товаров, для того чтобы увидеть список поставщиков услуг по определенному виду товара (например, мобильные телефоны), выберите название товарной категории в каталоге услуг.
                        Обязательно уточняйте у поставщиков услуг, оказывают ли они услуги именно для Вашего товара.</p>")?>
                    </div>
                </td>
            </tr>



            <tr>
                <td style="padding-top:15px;top:-4px;position:relative">
                    <a class="product_price" href="/page/add_service.html">Как сюда попасть?</a>
                </td>
                <td width="578px">

                    <form>
                        <table width="100%">
                            <tbody><tr>
                                    <td width="190" align="right">
                                        <span>География услуг:</span>
                                        <select name="region" id="region">
                                            <option value="0">Весь Казахстан</option>
                                            <? foreach ($cities as $city): ?>
                                            <?if(X3::user()->region!=null && X3::user()->region[0]==$city['id']):?>
                                            <option value="<?=$city['id']?>" selected="selected"><?=$city['title']?></option>
                                            <?else:?>
                                            <option value="<?=$city['id']?>"><?=$city['title']?></option>
                                            <?endif?>
                                            <? endforeach; ?>
                                        </select>
                                        <?/*
                                    </td>
                                    <td width="216px">
                                        <span>Сортировать по:</span>
                                        <select name="product">
                                            <option value="#">популярности</option>
                                            <option value="#">популярности</option>
                                            <option value="kurierom">популярности</option>
                                        </select>
                                    </td>

                                    <td width="130px" style="text-align:right">
                                        <div style="margin:0px" class="sale_selector">

                                            <span>Показать по:</span>
                                            <select size="1" name="product">
                                                <option value="#">10 </option>
                                                <option value="#">5</option>
                                                <option value="kurierom">20</option>
                                            </select>

                                        </div>
                                         */?>
                                    </td>
                                </tr>
                            </tbody></table>
                            

                    </form></td>
            </tr>
        </tbody></table>
<? $j=0;if(is_resource($companies)) while ($comp = mysql_fetch_assoc($companies)): 
                    $services = X3::db()->query("SELECT name, title FROM `data_service` ds WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.company_id='{$comp['id']}' AND cs.services LIKE CONCAT('%\"',ds.id,'\"%'))>0");
                    $address = X3::db()->fetch("SELECT * FROM data_address a WHERE status AND company_id={$comp['id']} AND type=1 $aquery ORDER BY ismain DESC, weight, id");
                    if($address == null)
                        $address = X3::db()->fetch("SELECT * FROM data_address a WHERE status AND company_id={$comp['id']} $aquery ORDER BY ismain DESC, weight, id");
                    if($address == null)
                        $address = X3::db()->fetch("SELECT * FROM data_address a WHERE status AND company_id={$comp['id']} ORDER BY ismain DESC, weight, id");
                    if($address == null)
                        continue;
                    $name = X3_String::create($comp['title'])->translit();
                    $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
                    $phones = explode(';;',$address['phones']);
                    ?> 
    <div class="main_services_left<?if($j==0)echo' first'?>"><!--main_services_left-->
        <table class="inside_main_services_left" width="100%">
            <tbody><tr>
                    <td>
                        <div class="main_services_inside_left<?if($j==0)echo' one';?>">
                            <a href="/<?=$name?>-company<?=$comp['id']?>.html" class="name"><h3><?=$comp['title']?></h3></a>
                            <p><?=  strip_tags($comp['servicetext'],"<b><i><strong>")?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
                            <div class="main_services_inside_left_link">
                            <? if(!empty($comp['services'])) foreach ($comp['services'] as $l=>$value): ?>
                                <a href="/service/<?=$value['name']?>.html" class="main_orange_link<?if($l==0)echo' active';?>"><?=$value['title']?></a>
                            <? endforeach; ?>
                            </div>
                        </div>
                        <div style="height:15px;" class="pustoi">&nbsp;</div>
                    </td>

                    <td width="200">
                        <div class="services_inside_right">
                            <?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('id'=>$comp['id']),'url'=>"/$name-company{$comp['id']}/feedback.html"))?>
                            <div class="clear-both">&nbsp;</div>
                            <div class="phone_number">
                                <ul>
                                    <? foreach ($phones as $z => $phone): if($z>3) break; ?>
                                    <li><?=$phone?></li>
                                    <? endforeach; ?>
                                </ul>
                                <div class="clear-both">&nbsp;</div>
                            </div>
                            <div class="clear-both">&nbsp;</div>
                            <div class="m_adress">
                                <?if($address['email']!=''):?>
                                    <span>e-mail: <a href="mailto:<?=$address['email']?>"><?=$address['email']?></a></span>
                                <?endif;?>
                                <?if($address['skype']!=''):?>
                                    <span>skype: <a href="skype:<?=$address['skype']?>"><?=$address['skype']?></a></span>
                                <?endif;?>
                                <?if($address['icq']!=''):?>
                                <span>icq: 
                                <?$icqs = explode(',',$address['icq']);
                                foreach($icqs as $icq):
                                $icq = trim($icq);
                                ?>
                                    <a href="icq:<?=$icq?>"><?=$icq?></a><br/>
                                <?endforeach;?>
                                </span>
                                <?endif;?>
                            </div>
                            <div class="clear-both">&nbsp;</div>
                        </div>
                        <div style="height:15px;clear:right" class="pustoi">&nbsp;</div>
                    </td>
                </tr>
            </tbody></table>
    </div><!--main_services_left-->
<?endwhile;?>
<?/*    
    <div style="float:right;margin-top:23px" class="sale_selector">
        <form>
            <span>Показать по:</span>
            <select size="1" name="product">
                <option value="#">10 </option>
                <option value="#">5</option>
                <option value="kurierom">20</option>
            </select>
        </form>
    </div>
    <div style="float:none" class="navi">
        <span>Страницы: </span>
        <a href="#">1</a>
        <a class="active" href="#">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <span class="ellipsis">...</span>
        <a href="#">25</a>
    </div>

*/?>

</div>
<script>
    $(function(){
        $('#region').click(function(e){e.stopPropagation();}).change(function(){
            $.post('<?=$url?>/prices.html',{'city':$(this).val()},function(m){
                if(m=='OK'){
                    location.reload();
                }
            })
        })
    })
</script>