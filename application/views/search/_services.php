<?php
$companies = X3::db()->query($models);
$aquery = '';
if(is_resource($companies)):
$ccount = mysql_num_rows($companies);
$i=0;
$j=0;while($comp = mysql_fetch_assoc($companies)): 
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
<?endif;?>