				<div class="sub_menu" style="display:none" id="product">
					<div class="m_product">
						<a href="#" onclick="$('#product').toggle();return false;"><h1>Товары</h1><span>&nbsp;</span></a>
					</div>
					<div class="clear-both">&nbsp;</div>
					<div class="sub_menu_inside">
						<div class="sub_menu_stripe">&nbsp;</div>
<?=  X3_Widget::run('@layouts:widgets:menuItems.php',array(),array('cache'=>true));?>
						
						
					</div>
				</div><!--active_position-->
				<div class="sub_menu" style="display:none;" id="service">
					<div class="m_product orange" style="margin-left:118px;">
						<a href="#" onclick="$('#service').toggle();return false;"><h1>Услуги</h1><span>&nbsp;</span></a>
					</div>
					<div class="clear-both">&nbsp;</div>
					<div class="sub_menu_inside" style="display: none;">
						<div class="sub_menu_stripe orange">&nbsp;</div>
<?// X3_Widget::run('@layouts:widgets:menuService.php',array(),array('cache'=>false));?>
						
						
					</div>
				</div><!--active_position-->
				
			<div class="m_product"<?=X3::app()->browser[0]=="opera"?' style="margin-left:-200px"':''?>><a href="#" onclick="show_menu('#product');return false;"><h1>Товары</h1><span>&nbsp;</span></a>
			<div class="m_product_stripe">&nbsp;</div>
			</div>
			<div class="m_product"<?=X3::app()->browser[0]=="opera"?' style="margin-left:-82px"':''?>><a href="/services.html" ><h1>Услуги</h1></a>
			<div class="m_service_stripe">&nbsp;</div>
			</div>
			<div class="m_product market"><a href="/shops.html"><h1>Магазины</h1></a>
			<div class="m_market_stripe">&nbsp;</div>
			</div>
                        <?/*
			<div class="m_product without"><a href="/sale.html"><h1>Распродажа</h1></a>
			<div class="m_sale_stripe">&nbsp;</div>
			</div>*/?>
			
			<div class="m_gray_stripe">
				<div>&nbsp;</div>
			</div>
				
			