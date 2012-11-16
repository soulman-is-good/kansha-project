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
$addresses = X3::db()->fetchAll("SELECT * FROM data_address WHERE type=1 AND status ORDER BY weight");
$cities = X3::db()->fetchAll("SELECT * FROM data_region INNER JOIN data_address ON data_address.city=data_region.id WHERE data_address.type=1 AND data_region.status GROUP BY `name` ORDER BY data_region.title");
$services = X3::db()->fetchAll("SELECT id,name,title FROM data_service ds WHERE status ORDER BY title");
$cnt = X3::db()->fetchAll("SELECT COUNT(0) cnt FROM data_company WHERE status $query");
$paginator = new Paginator('Service',$cnt['cnt']);
$companies = X3::db()->fetchAll("SELECT * FROM data_company WHERE status $query ORDER BY isfree, weight, title LIMIT $paginator->offset, $paginator->limit");
$scomp = X3::db()->fetchAll("SELECT * FROM company_service");
$igr = array();
foreach ($scomp as $i => $sc) {
    $serv = json_decode($sc['services']);
    $grs = json_decode($sc['groups']);
    $igr = array_merge($igr,  array_diff($grs, $igr));
    foreach($companies as $j=>$comp){
        if($comp['id'] == $sc['company_id']){
            foreach($services as $k=>$ss){
                if(in_array($ss['id'], $serv))
                    $companies[$j]['services'][]=$services[$k];
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
$igr = implode(',', $igr);
$groups = X3::db()->fetchAll("SELECT * FROM shop_group WHERE status AND id IN ($igr)");

foreach($companies as $j=>$comp) if(empty($companies[$j]['services'])) unset($companies[$j]);
$ccount = count($companies);
?>
<noindex>
<div class="main_services">
    <table class="company_service" style="position:relative;width:100%">
        <tr>
            <td style="width:190px">
                <h2>Услуги компаний</h2>
            </td>
            <td style="width:92px" id="city_select">
                <a href="#select" class="city"><span class="with_border"><?=(X3::user()->region!=null?X3::user()->region[1]:'В Алматe')?></span><span class="without_border">&nbsp;</span></a>
            </td>
            <td>
                <div class="orange_company" style="margin-right:145px;width:auto;margin-left:8px;">
                    <div class="orange_left">&nbsp;</div>
                    <div class="orange_right">&nbsp;</div>
                </div>
                <a href="/page/add_service.html" class="add">Добавить услуги</a>
            </td>
        </tr>
    </table>


    <div class="clear" style="clear:left">&nbsp;</div>
    <table class="main_services_tbl" width="100%">
        <tr>
            <td width="163">
                <div class="service_branch">
                    <ul class="list serviceBranch" style="margin-top:5px">
                        <? foreach ($services as $z=>$value): 
                        if(X3::db()->count("SELECT id FROM company_service WHERE services LIKE '%\"{$value['id']}\"%'")==0) continue;
                            ?>
			<li><a href="/service/<?=$value['name']?>.html"><?=$value['title']?></a></li>
                        <? endforeach; ?>
                        <!--li><a href="#" class="last">Все услуги</a></li-->
                    </ul>

                    <div class="branch" style="margin-right:-26px">
                        <table class="branch_stripe">
                            <tr>
                                <td style="width:80px">
                                    <h2>Разделы</h2>
                                </td>
                                <td>
                                    <div class="branch_long_stripe">
                                        <div class="dark_left">&nbsp;</div>
                                        <div class="dark_right">&nbsp;</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <ul>
                            <? foreach ($groups as $group): ?>
                            <li><a href="/service/category<?=$group['id']?>.html"><?=$group['title']?></a></li>
                            <? endforeach; ?>
                            <!--li><a href="#" class="all_branch">Все разделы</a></li-->
                        </ul>
                    </div>
                </div>
            </td>
            <td>
                <div class="main_services_left indent">
                    <p class="text_main_service"><?=empty($description)?SysSettings::getValue('Service.Text', 'text', 'Раздел услуг', 'Текстовка', "Раздел \"Услуги\" портала Kansha.kz предназначен для быстрого поиска необходимой услуги среди большого числа компаний в Казахстане.
                        Для быстрого поиска услуги вы можете воспользоваться пунктом меню \"Найти объявление\".<br/>
                        Если Вас интересует конкретная услуга (например, ремонт), просто выберите ее в пункте меню \"Виды услуг\" и перейдите к списку компаний, оказывающих данную услугу. Услуги разбиты по видам товаров, для того чтобы увидеть список поставщиков услуг по определенному виду товара (например, мобильные телефоны), выберите название товарной категории в каталоге услуг.
                        Обязательно уточняйте у поставщиков услуг, оказывают ли они услуги именно для Вашего товара."):$description?></p>
                    <?if($ccount>0):?>
                    <div class="sale_selector service" style="float:right;">
               <?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
                    </div>
                    <div class="selector_shops service">
                        <form>
                            <span>Сортировать по:</span>
                            <select name="product" size="1">
                                <option value="#" >популярности</option>
                                <option value="#">Отзывам</option>
                                <option value="kurierom">Рейтингу</option>
                            </select>
                        </form>
                    </div>
                    <?endif;?>
                </div>
                <? foreach ($companies as $j=>$comp): 
                    $name = X3_String::create($comp['title'])->translit();
                    $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
                    $phones = explode(';;',$comp['address']['phones']);
                    ?>                    
                <div class="main_services_left indent">
                    <div class="services_inside_right">
                        <?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('company_id'=>$comp['id']),'url'=>"/$name-company{$comp['id']}/feedback.html"))?>

                        <div class="phone_number">
                            <ul>
                                <? if(!empty($phones)) foreach ($phones as $z => $phone): if($z>3) break; ?>
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
                    <div class="main_services_inside_left">
			<a href="/<?=$name?>-company<?=$comp['id']?>.html" class="name"><h3><?=$comp['title']?></h3></a>
                        <p><?=  strip_tags($comp['servicetext'])?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
			<div class="main_services_inside_left_link">
                        <? foreach ($comp['services'] as $i=>$value): ?>
                            <a href="/service/<?=$value['name']?>.html" class="main_orange_link<?if($i==0)echo' active';?>"><?=$value['title']?></a>
                        <? endforeach; ?>
			</div>
                    </div>
                    <div class="pustoi" style="height:15px;clear:right;">&nbsp;</div>
                </div><!--main_services_left-->
                <div class="clear-both" style="float:right">&nbsp;</div>
        <?endforeach;?>
                <?if($ccount>0):?>
                <div class="service_lower">
                    <div class="sale_selector" style="margin-top:20px">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
                    </div>
                    <div class="navi" style="float:none;left:43px">
<?=$paginator?>
                    </div>
                </div>
                <?endif;?>
            </td>
        </tr>
    </table>
</div><!--main_services-->
<?=X3_Widget::run('@layouts:widgets:BannerBottom.php')?>
<script type="text/html" id="popup_city">
<div id="city_list" style="background:#fff;position:absolute;left:192px;top:6px;border-radius:11px;box-shadow:0 0 5px #cacaca;padding:7px 0 0;display: none;">
    
        <? foreach ($cities as $city): ?>
        <div style="margin-bottom:7px;padding:0 17px 0 10px;"><a class="city" style="left:0;top:0;position:static" href="<?=X3::app()->request->parseUrl(array('city'=>$city['name']),X3::app()->request->url=='services'?'/service':null,true)?>"><span class="with_border">В <?=Address::verb($city['title'])?></span></a></div>
        <? endforeach; ?>
        <?if(X3::user()->region==null):?>
        <div style="height:7px;font-size:0px;line-height: 0;">&nbsp;</div>
        <?else:?>
        <div style="margin-bottom:4px;font-size:0px;line-height: 0;">&nbsp;</div>
        <div style="padding:3px 17px 7px 10px;background:#f5f5f5;border-top:1px solid #cacaca;"><a class="city" style="left:0;top:0;position:static" href="<?=X3::app()->request->parseUrl(array('city'=>'all'),'/service',true)?>"><span class="with_border">Везде</span></a></div>
        <?endif;?>
    
</div>
</script>
<script type="text/javascript">
    $('.showperpage').change(function(){
        $.post('/site/limit',{'module':'Service','val':$(this).val()},function(m){
            if(m=='OK'){
                location.reload();
            }
        })
    })
    
    $(function(){
        if(typeof $('#city_select #city_list')[0] == 'undefined')
            $('#city_select').append($('#popup_city').html());
        $('#city_select .city').click(function(){
            $('#city_list').slideDown();
            setTimeout(function(){$(document).one('click',function(){$('#city_list').fadeOut()})},500);
        })
    })
</script>
</noindex>