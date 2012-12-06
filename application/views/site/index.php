<?X3::profile()->start('INDEX')?>
<?$items = array(1);?>
<div class="main_popular">
		<div class="main_popular_left">
			<?=X3_Widget::run('@layouts:widgets:popular_sections.php',array(),array('cache'=>true));?>			
		</div>
		<div class="main_popular_right">
			<?=X3_Widget::run('@layouts:widgets:carouselMain.php',array(),array('cache'=>true));?>
		</div>
		
		<div class="pustoi" style="height:10px">&nbsp;</div>
	</div>
	<div class="clear" style="clear:left">&nbsp;</div>
        <?if(!empty($items)):?>  
	<?=  X3_Widget::run('@layouts:widgets:servicesMain.php',array('type'=>2))?>
        <?endif;?>
	<?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('attrs'=>array()))?>
	
	<?=X3_Widget::run('@layouts:widgets:newsMain.php',array(),array('cache'=>false));?>

<?X3::profile()->end('INDEX')?>