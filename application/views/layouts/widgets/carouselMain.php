<?X3::profile()->start('carouselMain')?>

<?php
$gq = X3::db()->query("SELECT shop_group.id,shop_group.title, COUNT(shop_stat.id) k FROM shop_group INNER JOIN shop_item ON shop_item.group_id = shop_group.id INNER JOIN shop_stat ON shop_stat.item_id=shop_item.id WHERE shop_group.status GROUP BY shop_group.id ORDER BY istop DESC, k DESC, shop_group.weight, id DESC LIMIT 5;");
$qgr = X3::db()->query("SELECT shop_group.id,shop_group.title, COUNT(shop_stat.id) k FROM shop_group INNER JOIN shop_item ON shop_item.group_id = shop_group.id INNER JOIN shop_stat ON shop_stat.item_id=shop_item.id WHERE shop_item.isnew AND shop_group.status GROUP BY shop_group.id ORDER BY istop DESC, k DESC, shop_group.weight, id DESC LIMIT 5;");
//echo X3::db()->getErrors();
$items = array();
$newitems = array();
$newgroups = array();
$groups = array();
$f = 0;
if(is_resource($qgr))
while($g = mysql_fetch_assoc($qgr)){
     $tq = X3::db()->query("SELECT DISTINCT shop_item.id,shop_item.title,shop_item.model_name,suffix,shop_item.group_id,shop_item.image,shop_item.articule,
        (SELECT COUNT(0) FROM shop_stat WHERE item_id=shop_item.id) AS `count` ,(SELECT MIN(price) as c FROM company_item ci WHERE ci.item_id = shop_item.id AND ci.price>0) AS `price`
        FROM shop_item INNER JOIN company_item ON company_item.item_id = shop_item.id
        WHERE company_item.price>0 AND isnew AND status AND group_id={$g['id']} ORDER BY shop_item.created_at DESC, `count` DESC, weight,  id DESC LIMIT 10");
    $tmp = array();
    if(is_resource($tq))
    while($i = mysql_fetch_assoc($tq)){
        $i['link'] = '/'.strtolower(X3_String::create($i['title'])->translit()).($i['articule']!=''?"-({$i['articule']})-":'-').$i['id'];
        $tmp[] = $i;
    }
    if(!empty($tmp)){
        $newitems[$g['id']] = $tmp;
        $newgroups[]=$g;
    }
    $f++;
}
$f=0;
if(is_resource($gq))
while($g = mysql_fetch_assoc($gq)){
     $tq = X3::db()->query("SELECT DISTINCT shop_item.id,shop_item.title,shop_item.model_name,suffix,shop_item.group_id,shop_item.image,shop_item.articule,
        (SELECT COUNT(0) FROM shop_stat WHERE item_id=shop_item.id) AS `count` ,(SELECT MIN(price) as c FROM company_item ci WHERE ci.item_id = shop_item.id AND ci.price>0) AS `price`
        FROM shop_item INNER JOIN company_item ON company_item.item_id = shop_item.id
        WHERE company_item.price>0 AND status AND group_id={$g['id']} ORDER BY istop DESC, `count` DESC, weight, shop_item.created_at DESC, id DESC LIMIT 10");
    $tmp = array();
    if(is_resource($tq))
    while($i = mysql_fetch_assoc($tq)){
        $i['link'] = '/'.strtolower(X3_String::create($i['title'])->translit()).($i['articule']!=''?"-({$i['articule']})-":'-').$i['id'];
        $tmp[] = $i;
    }
    if(!empty($tmp)){
        $items[$g['id']] = $tmp;
        $groups[$f] = $g;
    }
    $f++;
}
?>
<?if(!empty($items)):?>
			<table class="product_new">
				<tr>
                                                <?if(count($newitems)>0):?>
					<td class="first">
						<a href="#top" class="m_green active">Топ-товары<span>&nbsp;</span></a>
						<a href="#new" class="m_green">Новое<span>&nbsp;</span></a>
					</td>
                                                <?else:?>
					<td class="first" style="width:109px">
						<a href="#top" class="m_green active">Топ-товары<span>&nbsp;</span></a>
					</td>
                                                <?endif;?>
					<td>
						<div class="popular_green">
							<div class="green_left">&nbsp;</div>
							<div class="green_right">&nbsp;</div>
						</div>
					</td>
				</tr>
			</table>
		
		<div class="popular_right_green_link">
                    <? $i=0;foreach ($groups as $grp): ?>
                        <?if($i==0):?>
			<a href="#group<?=$grp['id']?>" class="green_link active" style="margin-left:6px"><?=$grp['title']?></a>
                        <?else:?>
			<a href="#group<?=$grp['id']?>" class="green_link"><?=$grp['title']?></a>
                        <?endif;?>
                    <? $i++;endforeach; ?>
		</div>
                    <script type="text/html" id="product_tmpl">
			<div class="mgr_product">
				<a href="{@link}.html" class="pic"><div class="mgr_box_picture" style="background-image:url({@image})">&nbsp;</div></a>
				<div class="main_product_info">
				<a href="{@link}.html" title="{@title}" class="product_info">{@title}</a>
					<span>от <b>{@price}<i>&nbsp;</i></b></span>
				<a href="{@link}/prices.html" class="product_info_price">Сравнить цены</a>
				</div>
			</div>
                    </script>
                    <script type="text/javascript">
                        var items = null;
                        var anim = false;
                        var topitems = <?=  json_encode($items)?>;
                        <?if(!empty($newitems)):?>
                        var newitems = <?=  json_encode($newitems)?>;
                        <?endif;?>
                        var topgroups = <?=  json_encode($groups)?>;
                        <?if(!empty($newgroups)):?>
                        var newgroups = <?=  json_encode($newgroups)?>;
                        <?endif;?>
                        var grp_id = <?=key($items)?>;
                        var isnew = false;
                        function update_caru(){
                            var caru = $('#caru');
                            caru.html('');
                            var j = 0;
                            if(items[grp_id].length*170 - 38 >$('.merry_go_round').width()-80){
                                $('.m_strelka, .l_strelka').css('display','block');
                            }else
                                $('.m_strelka, .l_strelka').css('display','none');
                            for(i in items[grp_id]){
                                var tmpl = $('#product_tmpl').html();
                                var price = parseFloat(items[grp_id][i].price).toFixed(2).toString().replace(/[0]+$/,'').replace(/\.$/,'');
                                tmpl=tmpl.replace(/\{@image\}/g,'/uploads/Shop_Item/113x100xf/'+items[grp_id][i].image);
                                tmpl=tmpl.replace(/\{@title\}/g,items[grp_id][i].title);
                                tmpl=tmpl.replace(/\{@link\}/g,items[grp_id][i].link);
                                tmpl=tmpl.replace(/\{@price\}/g,price);
                                caru.append($(tmpl).css('left',(170*j)+'px'));
                                j++;
                            }
                        }
                        $(function(){
                            items = topitems;
                            update_caru();
                            $('#caru').width($('.merry_go_round').width()-80)
                            $('.m_green').click(function(){
                                if($(this).hasClass('active')) return false;
                                $('.m_green.active').removeClass('active');
                                $(this).addClass('active');
                                var j=0;
                                if($(this).attr('href')=='#new'){
                                    items = newitems;
                                    $('.popular_right_green_link').html('')
                                    for(i in newgroups){
                                        if(j==0){
                                            $('.popular_right_green_link').append('<a href="#group'+newgroups[i].id+'" class="green_link active" style="margin-left:6px">'+newgroups[i].title+'</a>');
                                            grp_id = newgroups[i].id
                                        }else{                                            
                                            $('.popular_right_green_link').append($('<a />').attr({'href':'#group'+newgroups[i].id}).addClass('green_link').html(newgroups[i].title));
                                        }
                                        j++;
                                    }
                                }
                                if($(this).attr('href')=='#top'){
                                    items = topitems;
                                    $('.popular_right_green_link').html('')
                                    for(i in topgroups){
                                        if(j==0){
                                            $('.popular_right_green_link').append('<a href="#group'+topgroups[i].id+'" class="green_link active" style="margin-left:6px">'+topgroups[i].title+'</a>');
                                            grp_id = topgroups[i].id
                                        }else
                                            $('.popular_right_green_link').append($('<a />').attr({'href':'#group'+topgroups[i].id}).addClass('green_link').html(topgroups[i].title));
                                            
                                        j++;
                                    }
                                }
                                update_caru();
                                return false;
                            })
                            $('.green_link').live('click',function(){
                                if($(this).hasClass('active')) return false;
                                $('.green_link.active').removeClass('active');
                                $(this).addClass('active');
                                grp_id = $(this).attr('href').replace('#group','');
                                update_caru();
                            })
                            $('.l_strelka').click(function(){
                                if(anim) return;
                                anim = true;
                                if($('#caru').width() - parseInt($('#caru .mgr_product:last').css('left'))-177<0){
                                    $('#caru .mgr_product').animate({'left':'-=170px'},function(){anim=false});
                                }else
                                    anim = false;
                            });
                            $('.m_strelka').click(function(){
                                if(anim) return;
                                anim = true;
                                if(parseInt($('#caru .mgr_product:first').css('left'))<0){
                                    $('#caru .mgr_product').animate({'left':'+=170px'},function(){anim=false;});
                                }else
                                    anim = false;
                            })
                        })
                    </script>
		<div class="merry_go_round" style="overflow: hidden;">
			<div class="m_strelka">&nbsp;</div>
                        <div style="height:187px;margin-left:40px;width:687px;overflow:hidden;position:relative;" id="caru">
			</div>
			<div class="l_strelka">&nbsp;</div>
		</div>
                <?else:?>
	<?=  X3_Widget::run('@layouts:widgets:servicesMain.php',array('type'=>1))?>
                <?endif;?>
<?X3::profile()->end('carouselMain')?>
