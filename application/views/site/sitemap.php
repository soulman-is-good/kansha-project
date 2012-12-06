<style>
    .one li span, .one li span a {
        font:11px Tahoma,sans-serif;
        color:#dadada;
        text-decoration: none;
    }
</style>
<table width="100%" class="sitemap">
		<tbody><tr>
			<td>
			<ul class="list_color">
				<li><a class="orange" href="/services.html">Услуги</a></li>
				<li><a class="blue" href="/shops.html">Магазины</a></li>
				<li><a class="red" href="/vse-razdely.html">Товары</a></li>
				<!--li><a class="red" href="/sales.html">Распродажа</a></li-->
			</ul>
			</td>
		
			<td>
			<ul class="black_list">
				<li><a href="/" hidefocus="true" style="outline: medium none;">Главная</a></li>
				<li><a href="/page/about.html">О проекте</a></li>
				<li><a href="/news.html">Новости</a></li>
			</ul>
			</td>
			<td>
                            <ul class="black_list">
                                    <li><a href="/articles.html">Обзоры</a></li>
                                    <li><a href="/page/contacts.html">Контакты</a></li>
                                    <li><a href="/manufacturers.html">Производители</a></li>
                            </ul>
			</td>
			<td>
			<ul class="black_mini">
                            <?foreach($pages as $page):?>
                            <li><a href="/page/<?=$page->name?>.html"><?=$page->title?></a></li>
                            <?endforeach?>
			</ul>
			</td>
		</tr>
	</tbody></table>

<div class="sub_menu_sitemap">
    <?if($inside):?>
    <h4><?=$groupTitle?></h4>

    <ul class="one">
        <?while($item = mysql_fetch_assoc($items)):
            $I = new Shop_Item();
            $I->getTable()->acquire($item);
            $I->getTable()->setIsNewRecord(false);
        ?>
        <li><a href="<?=$I->getLink()?>.html"><?=$item['title']?></a>
        <?endwhile;?>
    </ul>
        <?else: ?>
    <h4>Каталог товаров</h4>
    <ul class="one">
        <?foreach($groups as $group):?>
        <li><a href="<?=$group['url']?>.html"><?=$group['title']?></a> <span> - <a href="/sitemap/<?=$group['sm']?>.html">sitemap</a></span>
            <ul class="two">
            <? if(!empty($group['mans'])) foreach ($group['mans'] as $man): ?>
            <li><a href="<?=$group['url']?>/<?=$man['name']?>.html"><?=$group['title']?> <?=$man['title']?></a></li>
            <? endforeach; ?>
            </ul>
        </li>
        <?endforeach;?>
        <?endif;?>
    </ul>
</div>