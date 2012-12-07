<?php
$err = Page::get(array('name'=>'error404'),1);
?>
<div class="mistake">
		<div class="mistake_number">
			404
		</div>
		<div class="mistake_text">
<?=$err->text?>
		</div>
		<div class="clear-both">&nbsp;</div>
	</div>
<div class="sub_menu_sitemap">
		<h4>Каталог товаров</h4>				
<?=  X3_Widget::run('@layouts:widgets:menuItems.php',array(),array('cache'=>true));?>
						
	</div>
<?=$this->renderPartial('@layouts:widgets:BannerBottom.php');?>
