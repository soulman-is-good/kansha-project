<?X3::profile()->start('servicesMain.widget');?>

<?php
$query = '';
$aquery = '';
if(X3::user()->region!=null && X3::user()->region[0]>0){
    $query.=' AND dc.id IN (SELECT company_id FROM data_address da WHERE type=1 AND city='.X3::user()->region[0].')';
    $aquery = " AND city='".X3::user()->region[0]."'";
}elseif(X3::user()->region!=null)
   X3::user()->region = null;
$addresses = X3::db()->fetchAll("SELECT * FROM data_address WHERE type=1 AND status ORDER BY ismain DESC, weight");
//$services = X3::db()->fetchAll("SELECT * FROM data_service WHERE status ORDER BY title");
$services = X3::db()->fetchAll("SELECT ds.id,ds.name,ds.title FROM data_service ds WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.services LIKE CONCAT('%\"',ds.id,'\"%'))>0 ORDER BY title");
//$companies = X3::db()->fetchAll("SELECT * FROM data_company WHERE status ORDER BY isfree, title LIMIT 3");
$companies = X3::db()->query("SELECT dc.* FROM data_company dc INNER JOIN company_service cs ON cs.company_id=dc.id WHERE dc.status AND (cs.services<>'[]' OR cs.groups<>'[]') $query ORDER BY isfree, weight, title LIMIT 10");
/*$scomp = X3::db()->fetchAll("SELECT * FROM company_service");
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
$ccount = count($companies);*/
$ccount = is_resource($companies)?mysql_num_rows($companies):0;
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
                <? $j=0;while($comp = mysql_fetch_assoc($companies)): 
                    $comp['services'] = X3::db()->fetchAll("SELECT name, title FROM `data_service` ds WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.company_id='{$comp['id']}' AND cs.services LIKE CONCAT('%\"',ds.id,'\"%'))>0");
                    $comp['address'] = X3::db()->fetch("SELECT * FROM data_address a WHERE status AND company_id={$comp['id']} AND type=1 $aquery ORDER BY ismain DESC, weight, id");
                    $name = X3_String::create($comp['title'])->translit(0,"'");
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
                        <p itemprop="description"><?=  strip_tags($comp['servicetext'],"<b><i><strong>")?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
			<div class="main_services_inside_left_link">
                        <? foreach ($comp['services'] as $i=>$value): ?>
                            <a href="/service/<?=$value['name']?>.html" class="main_orange_link<?if($i==0)echo' active';?>"><?=$value['title']?></a>
                        <? endforeach; ?>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right;">&nbsp;</div>
		</div><!--main_services_left-->
                <? $j++;endwhile; ?>
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
                <? $j=0;while($comp = mysql_fetch_assoc($companies)): 
                    $comp['services'] = X3::db()->fetchAll("SELECT name, title FROM `data_service` ds WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.company_id='{$comp['id']}' AND cs.services LIKE CONCAT('%\"',ds.id,'\"%'))>0");
                    $comp['address'] = X3::db()->fetch("SELECT * FROM data_address a WHERE status AND company_id={$comp['id']} AND type=1 $aquery ORDER BY ismain DESC, weight, id");
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
                        <p><?=  strip_tags($comp['servicetext'],"<b><i><strong>")?> <a href="/<?=$name?>-company<?=$comp['id']?>/services.html">Подробнее</a></p>
			<div class="main_services_inside_left_link">
                        <? foreach ($comp['services'] as $i=>$value): ?>
                            <a href="/service/<?=$value['name']?>.html" class="main_orange_link<?=$i==0?' active':''?>"><?=$value['title']?></a>
                        <? endforeach; ?>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right;">&nbsp;</div>
		</div><!--main_services_left-->
                <? endwhile; ?>
	</td></tr></table>	
	
	</div><!--main_services-->
<?php endif; ?>
<?php endif; ?>
<?X3::profile()->end('servicesMain.widget');?>
