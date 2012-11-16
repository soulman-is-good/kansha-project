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
if(X3::user()->region!=null)
    $query.=' AND id IN (SELECT company_id FROM data_address WHERE type=1 AND city='.X3::user()->region[0].')';
$addresses = X3::db()->fetchAll("SELECT * FROM data_address WHERE status AND type=1 ORDER BY weight");
$cities = X3::db()->fetchAll("SELECT * FROM data_region INNER JOIN data_address ON data_address.city=data_region.id WHERE data_address.type=1 AND data_region.status GROUP BY `name` ORDER BY data_region.title");
$cnt = X3::db()->fetchAll("SELECT COUNT(0) cnt FROM data_company WHERE status $query");
$paginator = new Paginator('Service',$cnt['cnt']);
$companies = X3::db()->fetchAll("SELECT * FROM data_company WHERE status $query AND id IN (SELECT company_id FROM company_service WHERE groups LIKE '%\"$model->group_id\"%') ORDER BY isfree, title LIMIT $paginator->offset, $paginator->limit");
$scomp = X3::db()->fetchAll("SELECT * FROM company_service WHERE groups LIKE '%\"$model->group_id\"%'");
$services = X3::db()->fetchAll("SELECT * FROM data_service WHERE status ORDER BY title");
$igr = array();
$_servs = array();
foreach ($scomp as $i => $sc) {
    $serv = json_decode($sc['services']);
    $grs = json_decode($sc['groups']);
    $igr = array_merge($igr,  array_diff($grs, $igr));
    foreach($companies as $j=>$comp){
        if($comp['id'] == $sc['company_id']){
            foreach($services as $k=>$ss){
                if(in_array($ss['id'], $serv)){
                    $companies[$j]['services'][]=$services[$k];
                    $_servs[$ss['id']] = $ss['title'];
                }
            }
        }
        if(!empty($companies[$j]['services'])){
            if(!isset($comp['address']))
            foreach($addresses as $k=>$addr){
                if($addr['company_id'] == $comp['id'])
                    $companies[$j]['address']=$addresses[$k];
            }
        }
    }
}
unset($services);
$igr = implode(',', $igr);
$groups = X3::db()->fetchAll("SELECT * FROM shop_group WHERE status AND id IN ($igr)");
//foreach($companies as $j=>$comp) if(!empty($companies[$j]['services'])) $services[] = $companies[$j]['services'];
$ccount = count($companies);
?>
<div class="table_des">
    <table class="des_header change">

        <tbody><tr>
                <td style="padding-right:10px;white-space:nowrap;height:19px">
                    <div class="des_header_links" style="margin-top: 0px">
                                <a class="green_green" href="<?=$url?>/prices.html"><span>Цены</span><sup><?=$mm['cnt']?></sup><i>&nbsp;</i></a>
                                <a class="black_black" href="<?=$url?>.html"><span>Характеристики</span><i>&nbsp;</i></a>
                                <a class="blue_blue" href="<?=$url?>/feedback.html"><span>Отзывы</span><sup><?=$fcount?></sup><i>&nbsp;</i></a>
                                <a class="orange_orange active" href="#"><span>Услуги<i>&nbsp;</i></span><sup><?=$scount?></sup></a>
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
                                    <td width="190px">
                                        <?/*
                                        <?if(count($_servs)>1):?>
                                        <span>Вид услуги:</span>
                                        <select name="product">
                                            <option value="0">Все</option>
                                            <? foreach ($_servs as $id=>$ss): ?>
                                            <option value="<?=$id?>"><?=$ss?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <?endif;?>
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
<? foreach ($companies as $j=>$comp): 
    $name = X3_String::create($comp['title'])->translit();
    $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
    $phones = explode(';;',$comp['address']['phones']);
    ?>
    <div class="main_services_left<?if($j==0)echo' first'?>"><!--main_services_left-->
        <table class="inside_main_services_left" width="100%">
            <tbody><tr>
                    <td>
                        <div class="main_services_inside_left<?if($j==0)echo' one';?>">
                            <a href="/<?=$name?>-company<?=$comp['id']?>.html" class="name"><h3><?=$comp['title']?></h3></a>
                            <p><?=  strip_tags($comp['servicetext'])?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
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
                                <?if($comp['address']['email']!=''):?>
                                    <span>e-mail: <a href="mailto:<?=$comp['address']['email']?>"><?=$comp['address']['email']?></a></span>
                                <?endif;?>
                                <?if($comp['address']['skype']!=''):?>
                                    <span>skype: <a href="skype:<?=$comp['address']['skype']?>"><?=$comp['address']['skype']?></a></span>
                                <?endif;?>
                                <?if($comp['address']['icq']!=''):?>
                                <span>icq: 
                                <?$icqs = explode(',',$comp['address']['icq']);
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
<?endforeach;?>
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