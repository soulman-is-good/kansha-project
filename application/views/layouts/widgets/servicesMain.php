<?X3::profile()->start('servicesMain.widget');?>

<?php
$addresses = X3::db()->fetchAll("SELECT * FROM data_address WHERE type=1 AND status ORDER BY weight");
$services = X3::db()->fetchAll("SELECT * FROM data_service WHERE status ORDER BY title");
$companies = X3::db()->fetchAll("SELECT * FROM data_company WHERE status ORDER BY isfree, title LIMIT 3");
$scomp = X3::db()->fetchAll("SELECT * FROM company_service");
foreach ($scomp as $i => $sc) {
    $serv = json_decode($sc['services']);
    $grs = json_decode($sc['groups']);
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

foreach($companies as $j=>$comp) if(empty($companies[$j]['services'])) unset($companies[$j]);
$ccount = count($companies);
if($ccount>0):
if($type==1):
?>
<div class="main_services">
		<table class="company_service" style="position:relative;width:100%;top:10px">
			<tr>
				<td style="width:162px">
					<h2>Услуги компаний</h2>
				</td>
				<td>
					<div class="orange_company" style="margin-right:145px;width:auto">
						<div class="orange_left">&nbsp;</div>
						<div class="orange_right">&nbsp;</div>
					</div>
					<a href="/page/add_service.html" class="add">Добавить услуги</a>
				</td>
			</tr>
		</table>

		<table><tr>
		<td style="width:193px;">
		<ul class="list">
                        <? foreach ($services as $z=>$value): if($z>19) break; ?>
			<li><a href="/service/<?=$value['name']?>.html"><?=$value['title']?></a></li>
                        <? endforeach; ?>
			<li><a href="/services.html" class="last">Все услуги</a></li>
		</ul>
		</td>
		<td>
	<div class="ask">
			<span class="under">Справочник услуг по ремонту и сервису товаров в Казахстане</span>
	</div>
                <? foreach ($companies as $j=>$comp): 
                    $name = X3_String::create($comp['title'])->translit();
                    $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
                    $phones = explode(';;',$comp['address']['phones']);
                    ?>
		<div itemscope itemtype="http://schema.org/Organization" class="main_services_left<?=($j==$ccount-1)?' last':''?>">
		
			<div class="services_inside_right">
			<?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('id'=>$comp['id']),'url'=>"/$name-company{$comp['id']}/feedback.html"))?>
			
				<div class="phone_number">
				<ul itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                <? foreach ($phones as $z => $phone): if($z>3) break; ?>
				<li itemprop="telephone"><?=$phone?></li>
                                <? endforeach; ?>
				</ul>
				<div class="clear-both">&nbsp;</div>
				</div>
				<div class="clear-both">&nbsp;</div>
				<div class="m_adress">
                                    <?if($comp['address']['email']!=''):?>
					<span itemprop="email">e-mail: <a href="mailto:<?=$comp['address']['email']?>"><?=$comp['address']['email']?></a></span>
                                    <?endif;?>
                                    <?if($comp['address']['skype']!=''):?>
					<span itemprop="skype">skype: <a href="skype:<?=$comp['address']['skype']?>"><?=$comp['address']['skype']?></a></span>
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
			
			
			<div class="main_services_inside_left one">
                        <meta itemprop="url" content="http://<?=$_SERVER['HTTP_HOST']?>/<?=$name?>-company<?=$comp['id']?>.html" />
			<a href="/<?=$name?>-company<?=$comp['id']?>.html" class="name"><h3 itemprop="name"><?=$comp['title']?></h3></a>
                        <p itemprop="description"><?=  strip_tags($comp['servicetext'])?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
			<div class="main_services_inside_left_link">
                        <? foreach ($comp['services'] as $i=>$value): ?>
                            <a href="/service/<?=$value['name']?>.html" class="main_orange_link<?if($i==0)echo' active';?>"><?=$value['title']?></a>
                        <? endforeach; ?>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right;">&nbsp;</div>
		</div><!--main_services_left-->
                <? endforeach; ?>
	</td></tr></table>	
	
	</div><!--main_services-->
<?php else: ?>
<div class="main_services">
		<table class="company_service" style="position:relative;width:100%">
			<tr>
				<td style="width:162px">
					<h2>Услуги компаний</h2>
				</td>
				<td>
					<div class="orange_company" style="margin-right:145px;width:auto">
						<div class="orange_left">&nbsp;</div>
						<div class="orange_right">&nbsp;</div>
					</div>
					<a href="/page/add_service.html" class="add">Добавить услуги</a>
				</td>
			</tr>
		</table>
		
		
		
		<div class="clear" style="clear:left;height:0px">&nbsp;</div>
		<table width="100%"><tr>
		<td style="width:193px;">
		<ul class="list">
                        <? foreach ($services as $z=>$value): if($z>19) break; ?>
			<li><a href="/service/<?=$value['name']?>.html"><?=$value['title']?></a></li>
                        <? endforeach; ?>
			<li><a href="/services.html" class="last">Все услуги</a></li>
		</ul>
		</td>
		<td>
	<div class="ask">
			<span class="under">Справочник услуг по ремонту и сервису товаров в Казахстане</span>
	</div>
                <? foreach ($companies as $j=>$comp): 
                    $name = X3_String::create($comp['title'])->translit();
                    $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);
                    $phones = explode(';;',$comp['address']['phones']);
                    ?>
		<div class="main_services_left<?=($j==$ccount-1)?' last':''?>">
		
			<div class="services_inside_right">
			<?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('id'=>$comp['id']),'url'=>"/$name-company{$comp['id']}/feedback.html"))?>
			
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
			
			
			<div class="main_services_inside_left one">
			<a href="/<?=$name?>-company<?=$comp['id']?>.html" class="name"><h3><?=$comp['title']?></h3></a>
                        <p><?=  strip_tags($comp['servicetext'])?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
			<div class="main_services_inside_left_link">
                        <? foreach ($comp['services'] as $value): ?>
                            <a href="/service/<?=$value['name']?>.html" class="main_orange_link active"><?=$value['title']?></a>
                        <? endforeach; ?>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right;">&nbsp;</div>
		</div><!--main_services_left-->
                <? endforeach; ?>
	</td></tr></table>	
	
	</div><!--main_services-->
<?php endif; ?>
<?php endif; ?>
<?X3::profile()->end('servicesMain.widget');?>
