<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--[if lte IE 6]>     <html class="ie ie6"> <![endif]--> 
 <!--[if IE 7]>     <html class="ie ie7"> <![endif]--> 
 <!--[if IE 8]>     <html class="ie ie8"> <![endif]--> 
 <!--[if IE 9]>     <html class="ie ie9"> <![endif]--> 
 <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Главная!!!</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
        <meta name="author" content="Kansha" />
        <meta property="og:title" content="Kansha" />
        <meta property="og:type" content="company" />
        <meta property="og:url" content="http://www.kansha.kz/" />
        <meta property="og:image" content="http://www.kansha.kz/images/logo.png" />
        <meta property="og:site_name" content="Kansha" />
        <meta property="fb:app_id" content="" />        
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<script type="text/javascript" src="/js/jquery.js"></script>
	
	<!--[if IE]> <link rel="stylesheet" type= "text/css" href ="/css/style.ie.css" /> <![endif]-->
	<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
	<![endif]-->
	<script type="text/javascript">
		$(document).ready(function(){
			$("a").each(function() {
				$(this).attr("hideFocus", "true").css("outline", "none");
			});
		});
		function show_menu(id){
			$('.sub_menu').hide();
			$(id).toggle();
		}
		
	</script>
	
</head>


<body>
<div id="bg_upper">
	<div class="bg_upper_header">
	<div class="enter">
		<a href="#" class="link_one" onclick="$(this).siblings('.enter_market').slideDown();return false;"><i>&nbsp;</i><span>Вход для магазинов</span></a>
		<div class="enter_market" style="position:absolute;z-index:10;display:none">
			<a href="#" class="link_green" onclick="$(this).parent().slideUp();return false;"><i>&nbsp;</i><span>Вход для магазинов</span></a>
			<form name="primer1" method="post" action="obrabotchik.php">
				
				<input type="text" name="fio" size="#" class="fio_name" placeholder="Имя пользователя" >
				<input type="password" name="pass" class="secret" placeholder="Пароль">
				<a href="#" class="forget">Забыли пароль?</a><br />
				<input type="checkbox" name="remember" value="yes" class="try" ><span class="try_remember">Запомнить</span>
				<button>Войти</button>
			</form>
		</div>
	</div>	
		
		<a href="#" class="link_two">Реклама на сайте</a>
		<a href="#" class="link_two">Начать продавать</a>
		<a href="#" class="link_two">О проекте</a>
		<a href="#" class="link_two active">Помощь</a>
	</div>
</div>
<div id="main_content">
	<div class="main_header">
		<div class="main_logo">
			<a href="#"><img src="/images/kansha.jpg" width="184" height="38"></a>
			<p>В нашем каталоге <span class="number">25 000</span> товаров от <span class="how_much">120</span> интернет-магазинов.</p>
		</div>
		<div class="header_banner">
			<a href="#">
				<img src="/uploads/h_banner.jpg" width="240" height="100">
			</a>
		</div>
		<div class="find_it">
			<div style="margin-bottom: 10px;text-align: center;">
			<span>Хотите начать продовать свои товары через kansha.kz</span>
			<a href="#" class="main_red_link">Добавить свои товары</a>
			</div>
			<div>
				<div class="find_it_inside">
				<table class="find_table" cellspacing="0" cellpadding="0"><tr><td class="left"><input type="text" name="#" size="#" placeholder="Что ищем?">
					</td><td class="right">
					<button>Найти</button>
					</td></tr></table>
				</div>
			</div>
		</div>
		<div class="clear-both">&nbsp;</div>
		<div class="main_menu">
				<div class="sub_menu" style="display:none" id="product">
					<div class="m_product">
						<a href="#" onclick="$('#product').toggle();return false;"><h1>Товары</h1><span>&nbsp;</span></a>
					</div>
					<div class="clear-both">&nbsp;</div>
					<div class="sub_menu_inside">
						<div class="sub_menu_stripe">&nbsp;</div>
							<table>
								<tr>
									<td>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc.png" width="40" height="26" style="position:relative;left:-5px"/><h3>Компьютеры<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Ноутбуки</a>
												<a href="#">Нетбуки</a>
												<a href="#">Tablet</a>
												<a href="#">UMPC</a>
												<a href="#">Неттопы</a>
												<a href="#">Моноблоки</a>
												<a href="#">Настольные ПК</a>
												<a href="#">Электронные книги</a>
												<a href="#">Игровые приставки</a>
												<a href="#"> USB flash drive</a>
												<a href="#">Карты памяти</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc1.png);height:28px;"><h3>Комплектующие<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Процессоры AMD</a>
												<a href="#">Процессоры Intel</a>
												<a href="#">Материнские платы</a>
												<a href="#">Видеокарты</a>
												<a href="#">Оперативная память</a>
												<a href="#">Жесткие диски HDD| SDD</a>
												<a href="#">Оптические приводы</a>
												<a href="#">Накопители</a>
												<a href="#">Корпуса</a>
												<a href="#">Блоки питания</a>
												<a href="#">Кулеры|Системы охлождения</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc3.png" width="39" height="34" style="position:relative;left:-5px"/><h3>Сети и интернет<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Модемы</a>
												<a href="#">Коммутаторы</a>
												<a href="#">Маршрутизаторы</a>
												<a href="#">Сетевые карты</a>
												<a href="#">Bluetooth адаптеры</a>
												<a href="#">Wi-Fi адаптеры </a>
												<a href="#">Wi-Fi точки доступа</a>
												<a href="#">Wi-Fi ADSL точки доступа</a>
												<a href="#">Wifi антенны</a>
												<a href="#">Патч корды</a>
												<a href="#">Коннекторы</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc4.png);height:33px;"><h3>Видеотехника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Видеокамеры</a>
												<a href="#">ЖК телевизоры</a>
												<a href="#">Плазменные телевизоры</a>
												<a href="#">  Элт телевизоры  </a>
												<a href="#"> DVD и Blu-ray плееры</a>
												<a href="#">Домашние кинотеатры</a>
												<a href="#">Мультимедиа</a>
												<a href="#">Проекторы</a>
												<a href="#">Стационарные медиаплееры</a>
												<a href="#">Патч корды</a>
												<a href="#">Коннекторы</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc6.png" width="41" height="32" style="float:left;position:relative;left:-5px"/><h3>Аудиотехника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Акустические системы </a>
												<a href="#">Усилители и ресиверы</a>
												<a href="#">Музыкальные центры</a>
												<a href="#">Магнитолы</a>
												<a href="#">MP3-плееры</a>
												<a href="#">Наушники</a>
												<a href="#">Диктофоны </a>
												<a href="#">Портативная акустика</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc7.png);height:28px;"><h3>Мультимедиа<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Веб-камеры</a>
												<a href="#">Звуковые карты</a>
												<a href="#">Компьютерная акустика</a>
												<a href="#">ТВ-тюнеры</a>
												<a href="#">Компьютерные гарнитуры</a>
												
											</div>
										</div>
									</td>
									<td>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc5.png" width="35" height="28" style="position:relative;left:-5px"/><h3>Фототехника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Фотоаппараты</a>
												<a href="#">Фотообъективы</a>
												<a href="#">Фотовспышки</a>
												<a href="#">Цифровые рамки</a>
												<a href="#">Штативы</a>
												<a href="#">Светофильтры</a>
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc8.png);height:34px;"><h3>Техника для дома<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Варочные поверхности</a>
												<a href="#">Вытяжки</a>
												<a href="#">Водонагреватели</a>
												<a href="#">Духовые шкафы</a>
												<a href="#">Кухонные плиты</a>
												<a href="#">Микроволновые печи</a>
												<a href="#">Посудомоечные машины</a>
												<a href="#">Пылесосы</a>
												<a href="#">Роботы-пылесосы</a>
												<a href="#"> Стиральные машины</a>
												<a href="#"> Утюги</a>
												<a href="#"> Холодильники</a>
												<a href="#"> Швейные машины</a>
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc9.png);height:32px;"><h3>Красота и здоровье<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Машинки для стрижки</a>
												<a href="#">Фены</a>
												<a href="#">Эпилляторы</a>
												<a href="#">Духовые шкафы</a>
												<a href="#">Электробритвы мужские</a>
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc10.png" width="19" height="36" style="float:left;margin-left:10px"/><h3>Расходники<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Аккумуляторы</a>
												<a href="#">Барабаны</a>
												<a href="#">Батарейки</a>
												<a href="#"> Картриджи </a>
												<a href="#">Печатающие головки </a>
												<a href="#">Селеновый барабан</a>
												<a href="#"> СНПЧ</a>
												<a href="#"> Термопленки</a>
												<a href="#"> Тонеры  </a>
												<a href="#">  Тонер-картриджи </a>
												<a href="#"> Фотобарабаны </a>
												<a href="#">   Фотобумага</a>
												<a href="#"> Чернила  </a>
												<a href="#">  Чипы для картриджей</a>
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc11.png);height:31px;"><h3>Периферия<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Мониторы</a>
												<a href="#">Принтеры</a>
												<a href="#">МФУ</a>
												<a href="#">Сканеры</a>
												<a href="#">Источники бесперебойного питания</a>
												<a href="#">Графические планшеты</a>
												<a href="#">Клавиатуры</a>
												<a href="#">Мышки</a>
												<a href="#">Коврики</a>
												<a href="#">Рули</a>
												<a href="#">Джойстики</a>
												<a href="#">Геймпады</a>
												
												
											</div>
										</div>
									</td>
									<td>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc12.png" width="19" height="31" style="position:relative;left:5px"/><h3>Средства связи<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Сотовые телефоны</a>
												<a href="#">Смартфоны</a>
												<a href="#">Телефоны</a>
												<a href="#">Факсы</a>
												<a href="#"> Bluetooth-гарнитуры</a>
												<a href="#">Радиотелефоны</a>
												<a href="#"> GPS-навигаторы</a>
												<a href="#">Антирадары</a>
												
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc13.png" width="37" height="33" style="position:relative;left:-5px"/><h3>Программное обеспечение<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Антивирусы  </a>
												<a href="#">Операционные системы</a>
												<a href="#">Офисные предложения</a>
												<a href="#">Словари и переводчики</a>
												<a href="#">Графика и дизайн</a>
												<a href="#">Бухучет и финансы</a>
												
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc14.png);height:28px;"><h3>Техника для кухни<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#"> Аэрогрили </a>
												<a href="#">Блендеры</a>
												<a href="#">Кофеварки</a>
												<a href="#">Миксеры</a>
												<a href="#">Пароварки</a>
												<a href="#">Соковыжималки</a>
												<a href="#">Тостеры</a>
												<a href="#">Фритюрницы</a>
												<a href="#">Хлебопечки</a>
												<a href="#">Электорчайники</a>
												
												
												
											</div>
										</div>
									
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc15.png);height:24px;"><h3>Климатическая техника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#"> Ионизаторы</a>
												<a href="#">Вентиляторы</a>
												<a href="#">Кондиционеры</a>
												<a href="#">Обогреватели</a>
												<a href="#">Очистители и увлажнители воздуха</a>
												
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc16.png" width="37" height="24" style="position:relative;left:-5px"/><h3>Аксессуары<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">3D-очки</a>
												<a href="#">Автомобильные держатели</a>
												<a href="#">Автомобильные зарядные устройства</a>
												<a href="#">Зарядные устройства </a>
												<a href="#">Защитные пленки на экран</a>
												<a href="#">Кабеля </a>
												<a href="#">Сумки</a>
												<a href="#">Чехлы</a>
												<a href="#">Переходники</a>
												<a href="#">Подставки для ноутбуков </a>
												<a href="#">Card Reader</a>
												<a href="#">Dock-станции</a>
												<a href="#">USB HUB</a>
												<a href="#"> Экраны</a>
												
												
												
											</div>
										</div>
									</td>
								</tr>
							</table>
						
						
					</div>
				</div><!--active_position-->
				<div class="sub_menu" style="display:none;" id="service">
					<div class="m_product orange" style="margin-left:118px;">
						<a href="#" onclick="$('#service').toggle();return false;"><h1>Услуги</h1><span>&nbsp;</span></a>
					</div>
					<div class="clear-both">&nbsp;</div>
					<div class="sub_menu_inside">
						<div class="sub_menu_stripe orange">&nbsp;</div>
							<table>
								<tr>
									<td>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc.png" width="40" height="26" style="position:relative;left:-5px"/><h3>Компьютеры<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Ноутбуки</a>
												<a href="#">Нетбуки</a>
												<a href="#">Tablet</a>
												<a href="#">UMPC</a>
												<a href="#">Неттопы</a>
												<a href="#">Моноблоки</a>
												<a href="#">Настольные ПК</a>
												<a href="#">Электронные книги</a>
												<a href="#">Игровые приставки</a>
												<a href="#"> USB flash drive</a>
												<a href="#">Карты памяти</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc1.png);height:28px;"><h3>Комплектующие<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Процессоры AMD</a>
												<a href="#">Процессоры Intel</a>
												<a href="#">Материнские платы</a>
												<a href="#">Видеокарты</a>
												<a href="#">Оперативная память</a>
												<a href="#">Жесткие диски HDD| SDD</a>
												<a href="#">Оптические приводы</a>
												<a href="#">Накопители</a>
												<a href="#">Корпуса</a>
												<a href="#">Блоки питания</a>
												<a href="#">Кулеры|Системы охлождения</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc3.png" width="39" height="34" style="position:relative;left:-5px"/><h3>Сети и интернет<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Модемы</a>
												<a href="#">Коммутаторы</a>
												<a href="#">Маршрутизаторы</a>
												<a href="#">Сетевые карты</a>
												<a href="#">Bluetooth адаптеры</a>
												<a href="#">Wi-Fi адаптеры </a>
												<a href="#">Wi-Fi точки доступа</a>
												<a href="#">Wi-Fi ADSL точки доступа</a>
												<a href="#">Wifi антенны</a>
												<a href="#">Патч корды</a>
												<a href="#">Коннекторы</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc4.png);height:33px;"><h3>Видеотехника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Видеокамеры</a>
												<a href="#">ЖК телевизоры</a>
												<a href="#">Плазменные телевизоры</a>
												<a href="#">  Элт телевизоры  </a>
												<a href="#"> DVD и Blu-ray плееры</a>
												<a href="#">Домашние кинотеатры</a>
												<a href="#">Мультимедиа</a>
												<a href="#">Проекторы</a>
												<a href="#">Стационарные медиаплееры</a>
												<a href="#">Патч корды</a>
												<a href="#">Коннекторы</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc6.png" width="41" height="32" style="float:left;position:relative;left:-5px"/><h3>Аудиотехника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Акустические системы </a>
												<a href="#">Усилители и ресиверы</a>
												<a href="#">Музыкальные центры</a>
												<a href="#">Магнитолы</a>
												<a href="#">MP3-плееры</a>
												<a href="#">Наушники</a>
												<a href="#">Диктофоны </a>
												<a href="#">Портативная акустика</a>
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc7.png);height:28px;"><h3>Мультимедиа<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Веб-камеры</a>
												<a href="#">Звуковые карты</a>
												<a href="#">Компьютерная акустика</a>
												<a href="#">ТВ-тюнеры</a>
												<a href="#">Компьютерные гарнитуры</a>
												
											</div>
										</div>
									</td>
									<td>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc5.png" width="35" height="28" style="position:relative;left:-5px"/><h3>Фототехника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Фотоаппараты</a>
												<a href="#">Фотообъективы</a>
												<a href="#">Фотовспышки</a>
												<a href="#">Цифровые рамки</a>
												<a href="#">Штативы</a>
												<a href="#">Светофильтры</a>
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc8.png);height:34px;"><h3>Техника для дома<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Варочные поверхности</a>
												<a href="#">Вытяжки</a>
												<a href="#">Водонагреватели</a>
												<a href="#">Духовые шкафы</a>
												<a href="#">Кухонные плиты</a>
												<a href="#">Микроволновые печи</a>
												<a href="#">Посудомоечные машины</a>
												<a href="#">Пылесосы</a>
												<a href="#">Роботы-пылесосы</a>
												<a href="#"> Стиральные машины</a>
												<a href="#"> Утюги</a>
												<a href="#"> Холодильники</a>
												<a href="#"> Швейные машины</a>
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc9.png);height:32px;"><h3>Красота и здоровье<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Машинки для стрижки</a>
												<a href="#">Фены</a>
												<a href="#">Эпилляторы</a>
												<a href="#">Духовые шкафы</a>
												<a href="#">Электробритвы мужские</a>
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc10.png" width="19" height="36" style="float:left;margin-left:10px"/><h3>Расходники<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Аккумуляторы</a>
												<a href="#">Барабаны</a>
												<a href="#">Батарейки</a>
												<a href="#"> Картриджи </a>
												<a href="#">Печатающие головки </a>
												<a href="#">Селеновый барабан</a>
												<a href="#"> СНПЧ</a>
												<a href="#"> Термопленки</a>
												<a href="#"> Тонеры  </a>
												<a href="#">  Тонер-картриджи </a>
												<a href="#"> Фотобарабаны </a>
												<a href="#">   Фотобумага</a>
												<a href="#"> Чернила  </a>
												<a href="#">  Чипы для картриджей</a>
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc11.png);height:31px;"><h3>Периферия<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Мониторы</a>
												<a href="#">Принтеры</a>
												<a href="#">МФУ</a>
												<a href="#">Сканеры</a>
												<a href="#">Источники бесперебойного питания</a>
												<a href="#">Графические планшеты</a>
												<a href="#">Клавиатуры</a>
												<a href="#">Мышки</a>
												<a href="#">Коврики</a>
												<a href="#">Рули</a>
												<a href="#">Джойстики</a>
												<a href="#">Геймпады</a>
												
												
											</div>
										</div>
									</td>
									<td>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc12.png" width="19" height="31" style="position:relative;left:5px"/><h3>Средства связи<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Сотовые телефоны</a>
												<a href="#">Смартфоны</a>
												<a href="#">Телефоны</a>
												<a href="#">Факсы</a>
												<a href="#"> Bluetooth-гарнитуры</a>
												<a href="#">Радиотелефоны</a>
												<a href="#"> GPS-навигаторы</a>
												<a href="#">Антирадары</a>
												
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc13.png" width="37" height="33" style="position:relative;left:-5px"/><h3>Программное обеспечение<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">Антивирусы  </a>
												<a href="#">Операционные системы</a>
												<a href="#">Офисные предложения</a>
												<a href="#">Словари и переводчики</a>
												<a href="#">Графика и дизайн</a>
												<a href="#">Бухучет и финансы</a>
												
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc14.png);height:28px;"><h3>Техника для кухни<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#"> Аэрогрили </a>
												<a href="#">Блендеры</a>
												<a href="#">Кофеварки</a>
												<a href="#">Миксеры</a>
												<a href="#">Пароварки</a>
												<a href="#">Соковыжималки</a>
												<a href="#">Тостеры</a>
												<a href="#">Фритюрницы</a>
												<a href="#">Хлебопечки</a>
												<a href="#">Электорчайники</a>
												
												
												
											</div>
										</div>
										
										<div class="sub_menu_list">
											<div class="name" style="background-image:url(/images/pc15.png);height:24px;"><h3>Климатическая техника<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#"> Ионизаторы</a>
												<a href="#">Вентиляторы</a>
												<a href="#">Кондиционеры</a>
												<a href="#">Обогреватели</a>
												<a href="#">Очистители и увлажнители воздуха</a>
												
												
												
											</div>
										</div>
										<div class="sub_menu_list">
											<div class="name"><img src="/images/pc16.png" width="37" height="24" style="position:relative;left:-5px"/><h3>Аксессуары<sup>21 414</sup></h3></div>
											<div class="link_list">
												<a href="#">3D-очки</a>
												<a href="#">Автомобильные держатели</a>
												<a href="#">Автомобильные зарядные устройства</a>
												<a href="#">Зарядные устройства </a>
												<a href="#">Защитные пленки на экран</a>
												<a href="#">Кабеля </a>
												<a href="#">Сумки</a>
												<a href="#">Чехлы</a>
												<a href="#">Переходники</a>
												<a href="#">Подставки для ноутбуков </a>
												<a href="#">Card Reader</a>
												<a href="#">Dock-станции</a>
												<a href="#">USB HUB</a>
												<a href="#"> Экраны</a>
												
												
												
											</div>
										</div>
									</td>
								</tr>
							</table>
						
						
					</div>
				</div><!--active_position-->
				
			<div class="m_product"><a href="#" onclick="show_menu('#product');return false;"><h1>Товары</h1><span>&nbsp;</span></a>
			<div class="m_product_stripe">&nbsp;</div>
			</div>
			<div class="m_product"><a href="#" onclick="show_menu('#service');return false;"><h1>Услуги</h1><span>&nbsp;</span></a>
			<div class="m_service_stripe">&nbsp;</div>
			</div>
			<div class="m_product market"><a href="#"><h1>Магазины</h1></a>
			<div class="m_market_stripe">&nbsp;</div>
			</div>
			<div class="m_product without"><a href="#"><h1>Распродажа</h1></a>
			<div class="m_sale_stripe">&nbsp;</div>
			</div>
			
			<div class="m_gray_stripe">
				<div>&nbsp;</div>
			</div>
				
			
		</div>
		
		
	</div><!--main_header-->
	<div class="clear-both">&nbsp;</div>
	
	
<div class="main_banner_block">
<div class="right_banner">
	<a href="#">
		<img src="/uploads/r_banner.jpg" width="240" height="399">
	</a>
</div>

<div class="banner_links">
	<a href="#" class="active"><img src="/images/m_a.png" width="7" height="7"></a>
	<a href="#"><img src="/images/mini.png" width="7" height="7"></a>
	<a href="#"><img src="/images/mini.png" width="7" height="7"></a>
	
</div>
<div class="tender">
	<table class="tender_stripe">
		<tr>
			<td style="width:183px">
				<h2>Спец. предложения</h2>
			</td>
			<td>
				<div class="tender_long_stripe">
					<div class="tender_left">&nbsp;</div>
					<div class="tender_right">&nbsp;</div>
				</div>
			</td>
		</tr>
	</table>
	
	<div class="clear-both">&nbsp;</div>
	<div class="tender_box">
		<div class="tender_box_picture" style="background-image:url(/uploads/t1.jpg)"></div>
		<div class="tender_text">
			<span class="tender_letter">Фотоаппараты</span>
			<a href="#">Canon EOS 600D</a>
			<span class="tender_red">158 360 <i>&nbsp;</i></span>
			<span class="tender_letter"><a href="#">в Fotoland.kz</a></span>
		</div>
		<div class="clear-both">&nbsp;</div>
	</div>
	<div class="tender_box">
		<div class="tender_box_picture" style="background-image:url(/uploads/t2.jpg)"></div>
		<div class="tender_text">
			<span class="tender_letter">Телевизоры</span>
			<a href="#">Panasonic TX-P50VT30</a>
			<span class="tender_red">303 400<i>&nbsp;</i></span>
			<span class="tender_letter"><a href="#">в Телемир</a></span>
		</div>
		<div class="clear-both">&nbsp;</div>
	</div>
	<div class="tender_box">
		<div class="tender_box_picture" style="background-image:url(/uploads/t3.jpg)"></div>
		<div class="tender_text">
			<span class="tender_letter">Фотоаппараты</span>
			<a href="#">Fujifilm FinePix Real 3D</a>
			<span class="tender_red">48 100<i>&nbsp;</i></span>
			<span class="tender_letter"><a href="#">в Fotoland.kz</a></span>
		</div>
		<div class="clear-both">&nbsp;</div>
	</div>
		<div class="tender_box last">
		<div class="tender_box_picture" style="background-image:url(/uploads/t1.jpg)">&nbsp;</div>
		<div class="tender_text">
			<span class="tender_letter">Фотоаппараты</span>
			<a href="#">Canon EOS 600D</a>
			<span class="tender_red">158 360 <i>&nbsp;</i></span>
			<span class="tender_letter"><a href="#">в Fotoland.kz</a></span>
		</div>
		<div class="clear-both">&nbsp;</div>
	</div>
</div>
<div class="top_markets">
	<table class="market_stripe">
		<tr>
			<td>
				<h2>Топ-магазины</h2>
			</td>
			<td>
				<div class="blue_stripe">
					<div class="radius_left">&nbsp;</div>
					<div class="radius_right">&nbsp;</div>
				</div>
			</td>
	</tr>
	</table>
	<div class="clear-both">&nbsp;</div>
	<a href="#" class="red_link_offer">Хотите добавить свой интернет-магазин?</a>
	<div class="market_box">
		<a href="#"><div class="market_box_picture" style="background-image:url(/uploads/mk1.jpg)">&nbsp;</div></a>
		<p class="market_text">Кол-во просмотров товаров компании:
		Март <span>22 525</span>
		Февраль <span>1 135</span> </p>
	<div class="clear-both">&nbsp;</div>
	</div>
	<div class="market_box">
		<a href="#"><div class="market_box_picture" style="background-image:url(/uploads/mk2.jpg)">&nbsp;</div></a>
		<p class="market_text">Кол-во просмотров товаров компании:
		Март <span>22 525</span>
		Февраль <span>1 135</span> </p>
	<div class="clear-both">&nbsp;</div>
	</div>
	<div class="market_box last">
		<a href="#"><div class="market_box_picture" style="background-image:url(/uploads/mk3.jpg)">&nbsp;</div></a>
		<p class="market_text">Кол-во просмотров товаров компании:
		Март <span>22 525</span>
		Февраль <span>1 135</span> </p>
	<div class="clear-both">&nbsp;</div>
	</div>
	<a href="#">Все магазины</a>
</div>

	
</div><!--Banners-->





	
<div class="main_content_left">
	<div class="main_popular">
		<div class="main_popular_left">
			<table class="popular">
				<tr>
					<td>
						<h2>Популярные</h2>
					</td>
					<td style="width:100%">
						<div class="popular_blue_stripe">
						<div class="popular_left_s">&nbsp;</div>
						<div class="popular_right_s">&nbsp;</div>
						</div>
						
					</td>
				</tr>
			</table>
			
			<div class="clear-both">&nbsp;</div>
			<ul>
				<li><a href="#">Мобильные телефоны</a></li>
				<li><a href="#">Ноутбуки</a></li>
				<li><a href="#">Фотоаппараты</a></li>
				<li><a href="#">Телевизоры</a></li>
				<li><a href="#">MP3 плееры</a></li>
				<li><a href="#">Стиральные машины</a></li>
				<li><a href="#">Холодильники</a></li>
				<li><a href="#">Планшеты</a></li>
				<li><a href="#">Компьютеры</a></li>
				<li><a href="#" class="all_last">Все разделы</a></li>
				
				
			</ul>
		</div>
		<div class="main_popular_right">
			<table class="product_new">
				<tr>
					<td class="first">
						<a href="#" class="m_green active">Топ-товары<span>&nbsp;</span></a>
						<a href="#" class="m_green">Новое<span>&nbsp;</span></a>
					</td>
					<td>
						<div class="popular_green">
							<div class="green_left">&nbsp;</div>
							<div class="green_right">&nbsp;</div>
						</div>
					</td>
				</tr>
			</table>
		
		<div class="popular_right_green_link">
			<a href="#" class="green_link" style="margin-left:6px">Мобильные телефоны</a>
			<a href="#" class="green_link active">Ноутбуки</a>
			<a href="#" class="green_link">Фотоаппараты</a>
			<a href="#" class="green_link"> Телевизоры</a>
			<a href="#" class="green_link">Холодильники</a>
		</div>
		<div class="merry_go_round">
			<div class="m_strelka">&nbsp;</div>
			<div class="mgr_product">
				<a href="#" class="pic"><div class="mgr_box_picture" style="background-image:url(/uploads/pic1.jpg)">&nbsp;</div></a>
				<div class="main_product_info">
				<a href="#" class="product_info">HP Pavilion dv6-6000</a>
					<span>от <b>116 180<i>&nbsp;</i></b></span>
				<a href="#" class="product_info_price">Сравнить цены</a>
				</div>
			</div>
			<div class="mgr_product box2">
				<a href="#" class="pic"><div class="mgr_box_picture" style="background-image:url(/uploads/pic2.jpg)">&nbsp;</div></a>
				<div class="main_product_info">
				<a href="#" class="product_info">HP Pavilion dv6-6000</a>
					<span>от <b>116 180<i>&nbsp;</i></b></span>
				<a href="#" class="product_info_price">Сравнить цены</a>
				</div>
			</div>
			<div class="mgr_product box3">
				<a href="#" class="pic"><div class="mgr_box_picture" style="background-image:url(/uploads/pic3.jpg)">&nbsp;</div></a>
				<div class="main_product_info">
				<a href="#" class="product_info">HP Pavilion dv6-6000</a>
					<span>от <b>116 180<i>&nbsp;</i></b></span>
				<a href="#" class="product_info_price">Сравнить цены</a>
				</div>
			</div>
			<div class="l_strelka">&nbsp;</div>
			<div class="clear-both">&nbsp;</div>
		</div>
			
		</div>
		
		<div class="pustoi" style="height:10px">&nbsp;</div>
	</div>
	<div class="clear" style="clear:left">&nbsp;</div>
	
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
					<a href="#" class="add">Добавить услуги</a>
				</td>
			</tr>
		</table>
		
		
		
		<div class="clear" style="clear:left;height:0px">&nbsp;</div>
		<table><tr>
		<td style="width:193px;">
		<ul class="list">
			<li><a href="#">Адаптация</a></li>
			<li><a href="#">Аренда</a></li>
			<li><a href="#">Аэрография</a></li>
			<li><a href="#">Восстановление</a></li>
			<li><a href="#">Диагностика</a></li>
			<li><a href="#">Замена картриджей</a></li>
			<li><a href="#">Заправка</a></li>
			<li><a href="#">Консультации</a></li>
			<li><a href="#">Модернизация</a></li>
			<li><a href="#">Монтаж</a></li>
			<li><a href="#">Прокат</a></li>
			<li><a href="#">Прошивка</a></li>
			<li><a href="#">Разблокировка</a></li>
			<li><a href="#">Ремонт</a></li>
			<li><a href="#">Русификация</a></li>
			<li><a href="#">Сервисное обслуживание</a></li>
			<li><a href="#">Установка</a></li>
			<li><a href="#">Установка ПО</a></li>
			<li><a href="#" class="last">Все услуги</a></li>
		</ul>
		</td>
		<td>
	<div class="ask">
			<span class="under">Справочник услуг по ремонту и сервису товаров в Казахстане</span>
	</div>
		<div class="main_services_left">
		
			<div class="services_inside_right">
			<div class="star_links">
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars"><img src="/images/gstar.png" width="16" height="16"></a>
				<a href="#" class="stars"><img src="/images/gstar.png" width="16" height="16"></a>
				<a href="#" class="comment">2 Отзыва</a>
			</div>
			
				<div class="phone_number">
				<ul>
				<li>8 (777) 621 45 19</li>
				<li>8 (727) 220 07 71</li>
				<li>8 (727) 225 59 23</li>
				<li>8 (727) 221 31 88</li>
				</ul>
				<div class="clear-both">&nbsp;</div>
				</div>
				<div class="clear-both">&nbsp;</div>
				<div class="m_adress">
					<span>e-mail: <a href="#">alisa-invest@mail.ru</a></span>
					<span>skype: <a href="#">alisa_invest</a></span>
					<span>icq: <a href="#">281359576</a></span>
				</div>
				<div class="clear-both">&nbsp;</div>
			</div>
			
			
			<div class="main_services_inside_left one">
			<a href="#" class="name"><h3>АльсаИнвест ООО</h3></a>
			<p>ООО АльсаИнвест производит ремонт сотовых телефонов, ноутбуков, цифровых фотоаппаратов, видеокамер, мониторов, факсов, автомагнитол, эхолотов, МР3 плееров, видеорегистраторов, проекторов, диктофонов, ТВ-тюнеров.
			Предлагаем ремонт любой сложности. <a href="#">Подробнее</a></p>
			<div class="main_services_inside_left_link">
			<a href="#" class="main_orange_link active">Адаптация</a>
			<a href="#" class="main_orange_link">Восстановление</a>
			<a href="#" class="main_orange_link">Диагностика</a>
			<a href="#" class="main_orange_link">Прошивка</a>
			<a href="#" class="main_orange_link active">Русификация </a>
			<a href="#" class="main_orange_link">Разблокировка </a>
			<a href="#" class="main_orange_link"> Установка ПО</a>
			<a href="#" class="main_orange_link">Сервисное обслуживание</a>
			<a href="#" class="main_orange_link">Монтаж</a>
			<a href="#" class="main_orange_link">Консультация</a>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right;">&nbsp;</div>
		</div><!--main_services_left-->
		
		<div class="main_services_left">
			<div class="services_inside_right">
			<div class="star_links">
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="comment">2 Отзыва</a>
			</div>
			
				<div class="phone_number">
				<ul>
				<li>8 (777) 621 45 19</li>
				<li>8 (727) 220 07 71</li>
				<li>8 (727) 225 59 23</li>
				<li>8 (727) 221 31 88</li>
				</ul>
				<div class="clear-both">&nbsp;</div>
				</div>
				<div class="clear-both">&nbsp;</div>
				<div class="m_adress">
					<span>e-mail: <a href="#">alisa-invest@mail.ru</a></span>
					<span>skype: <a href="#">alisa_invest</a></span>
					<span>icq: <a href="#">281359576</a></span>
				</div>
				<div class="clear-both">&nbsp;</div>
			</div>
			
			
			<div class="main_services_inside_left">
			<a href="#" class="name"><h3>NM Group</h3></a>
			<p>Ремонт цифровых фотоаппаратов Canon, Samsung, Nikon, Sony, Panasonic, Casio, Olympus, Kodak, Pentax, Rekam и других фирм. Ремонт мобильных телефонов любых моделей. Ремонт GPS-навигаторов, установка програмного обеспечения. Нам доверяют более 8 лет!
			 <a href="#">Подробнее</a></p>
			<div class="main_services_inside_left_link">
			<a href="#" class="main_orange_link active">Адаптация</a>
			<a href="#" class="main_orange_link">Восстановление</a>
			<a href="#" class="main_orange_link">Диагностика</a>
			<a href="#" class="main_orange_link">Прошивка</a>
			<a href="#" class="main_orange_link active">Русификация </a>
			<a href="#" class="main_orange_link">Разблокировка </a>
			<a href="#" class="main_orange_link"> Установка ПО</a>
			<a href="#" class="main_orange_link">Сервисное обслуживание</a>
			<a href="#" class="main_orange_link">Монтаж</a>
			<a href="#" class="main_orange_link">Консультация</a>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right">&nbsp;</div>
		</div><!--main_services_left-->
		
		
		
		
		<div class="main_services_left last">
			<div class="services_inside_right">
			<div class="star_links">
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars orange"><img src="/images/ostar.png" width="16" height="16"></a>
				<a href="#" class="stars"><img src="/images/gstar.png" width="16" height="16"></a>
				<a href="#" class="stars"><img src="/images/gstar.png" width="16" height="16"></a>
				<a href="#" class="comment">2 Отзыва</a>
			</div>
			
				<div class="phone_number">
				<ul>
				<li>8 (777) 621 45 19</li>
				<li>8 (727) 220 07 71</li>
				<li>8 (727) 225 59 23</li>
				<li>8 (727) 221 31 88</li>
				</ul>
				<div class="clear-both">&nbsp;</div>
				</div>
				<div class="clear-both">&nbsp;</div>
				<div class="m_adress">
					<span>e-mail: <a href="#">alisa-invest@mail.ru</a></span>
					<span>skype: <a href="#">alisa_invest</a></span>
					<span>icq: <a href="#">281359576</a></span>
				</div>
				<div class="clear-both">&nbsp;</div>
			</div>
			
			
			<div class="main_services_inside_left">
			<a href="#" class="name"><h3>Techno ZONE</h3></a>
			<p>Сертифицированный сервисный центр по ремонту мобильных телефонов, факсов. Диагностика бесплатно! Ремонт любой сложности, восстановление после ударов, попадания влаги, замена программного обеспечения, замена ЖКИ, динамиков, микрофонов, корпусов.
			<a href="#">Подробнее</a></p>
			<div class="main_services_inside_left_link">
			<a href="#" class="main_orange_link active">Адаптация</a>
			<a href="#" class="main_orange_link">Восстановление</a>
			<a href="#" class="main_orange_link">Диагностика</a>
			<a href="#" class="main_orange_link">Прошивка</a>
			<a href="#" class="main_orange_link active">Русификация </a>
			<a href="#" class="main_orange_link">Разблокировка </a>
			<a href="#" class="main_orange_link"> Установка ПО</a>
			<a href="#" class="main_orange_link">Сервисное обслуживание</a>
			<a href="#" class="main_orange_link">Монтаж</a>
			<a href="#" class="main_orange_link">Консультация</a>
			</div>
			</div>
			<div class="pustoi" style="height:15px;clear:right">&nbsp;</div>
		</div><!--main_services_left-->
	</td></tr></table>	
	
	</div><!--main_services-->
	<div class="lower_banner">
		<a href="#">
			<img src="/uploads/l_banner.jpg">
		</a>
	</div>
	
	<table class="main">
		<tr>
			<td class="wnews">
				<div class="mn_news">
					<table class="blue_stripes" style="width:100%">
						<tr>
							<td style="width:75px">
								<h2>Новости</h2>
							</td>
							<td>
								<div class="one_blue_stripe">
								<div class="blue_left">&nbsp;</div>
								<div class="blue_right">&nbsp;</div>
								</div>
							</td>
						</tr>
					</table>
					<span class="first">13 марта 2012</span>
					<h3>Windows Phone Tango стало Windows Phone 7.5 Refresh</h3>
					<div class="mn_news_pic"><img src="/uploads/im1.jpg" width="139" height="83">
					</div>
					<p>По сведениям главы итальянского представительства Windows Phone Дуико Стефаниа (Duico Stefania), кодовое имя «Tango» для очередной версии
мобильной операционной системы вскоре заменят на «7.5 Refresh».</p>
					<div class="clear-both">&nbsp;</div>
					<div class="pustoi" style="height:8px">&nbsp;</div>
				</div>
				<div class="mn_news">
					<span>13 марта 2012</span>
					<h3>Lenovo IdeaTab S2109: почти iPad на на Android 4.0 Ice Cream Sandwich</h3>
				</div>
				<div class="mn_news last">
					<span>13 марта 2012</span>
					<h3>Retina-дисплей нарушает сроки выхода «нового iPad»</h3>
				</div>
				<a href="#" class="all_news">Все новости</a>
			</td>
			<td class="woverlook">
				<div class="overlook">
					<table class="one_black_stripe">
						<tr>
							<td style="width:72px">
								<h2>Обзоры</h2>
							</td>
							<td>
								<div class="black_stripe_long">
								<div class="black_left">&nbsp;</div>
								<div class="black_right">&nbsp;</div>
								</div>
							</td>
						</tr>
					</table>
					
					<div class="clear-both">&nbsp;</div>
					<span class="first">12 марта 2012</span>
					<h3>Обзор электронной книги PocketBook Pro 912: лучшие 10 дюймов</h3>
				</div>
				<div class="overlook">
					<div class="clear-both">&nbsp;</div>
					<span>12 марта 2012</span>
					<h3>Обзор ноутбука Asus N55SF: игровой, мобильный, мультимедийный</h3>
					<div class="mn_news_pic"><img src="/uploads/im2.jpg" width="139" height="83"></div>
					<p>Ноутбуки, скажу вам по секрету, мой любимый вид компьютеров. Они компактны, портативны, и обладают достаточной для большинства задач 
производительностью. Для обзора был выбран Asus N55SF.</p>
					<div class="clear-both">&nbsp;</div>
					
				</div>
				<div class="overlook last">
					<div class="clear-both">&nbsp;</div>
					<span>12 марта 2012</span>
					<h3>Обзор электронной книги PocketBook Pro 912: лучшие 10 дюймов</h3>
				</div>
				<a href="#" class="all_news">Все обзоры</a>
			</td>
		</tr>
	</table>
	
	
</div><!--main_content_left-->
<div class="clear" style="clear:left">&nbsp;</div>
<div class="main_lower_info">
<div class="now_find">
		<table class="find_find">
			<tr>
				<td style="width:118px">
					<h2>Сейчас ищут</h2>
				</td>
				<td>
					<div class="now_find_stripe">
						<div class="find_left">&nbsp;</div>
						<div class="find_right">&nbsp;</div>
					</div>
				</td>
			</tr>
		</table>
		
		<ul>
		<li><a href="#">Samsung Galaxy</a></li>
		<li><a href="#">iPhone</a></li>
		<li><a href="#">iPade</a></li>
		<li><a href="#">Мониторы Samsung</a></li>
		<li><a href="#">Ноутбуки HP</a></li>
		<li><a href="#">HTC Flyer</a></li>
		<li><a href="#">Canon EOS 550D</a></li>
		<li><a href="#">Lenovo IdeaTab</a></li>
		<li><a href="#">Nokia 9100</a></li>
		<li><a href="#">Пылесос Karcher</a></li>
		</ul>
</div>
	<div class="vk">
	<a href="#">
		<img src="/uploads/vk.jpg" width="240" height="287">
	</a>
	</div>
	<div class="project">
	<table class="project_stripe">
		<tr>
			<td style="width:105px">
			<h2>О проекте</h2>
			</td>
			<td>
				<div class="long_project_stripe">
					<div class="project_left">&nbsp;</div>
					<div class="project_right">&nbsp;</div>
				</div>
			</td>
		</tr>
	</table>
		
		
		<p>Добро пожаловать на торговую площадку Kansha.kz. У нас вы можете ознакомиться с ценами магазинов на цифровую, бытовую, компьютерную технику, детские товары, электро и бензоинструмент, а также на товары автомобильной тематики. Основой проекта является каталог товаров с техническими характеристиками и изображениями, а также аналитические статьи, обзоры и видео об устройствах. Помимо этого в каталоге услуг Kansha.kz пользователь сможет найти компании по сервисному обслуживанию или ремонту товаров.
		</p><p>Цель нашего проекта – максимально упростить процесс покупки товара. Посетитель сайта может выбрать и, сравнив цены в разных магазинах, купить понравившийся товар по лучшей цене.</p>
		<a href="#" class="more_info">Подробнее</a>
		<div class="mailer">
			<span>Рассылка</span>
			<div class="mailer_inside">
			<form name="primer1" method="post" action="">
				<input type="text" name="#" placeholder="Ваше имя">
				<input type="password" name ="#" placeholder="E-mail">
				<button>Подписаться</button>
			</form>
			</div>
			
			<div class="clear-both">&nbsp;</div>
		</div>
	</div>
	<div class="clear-both">&nbsp;</div>
	
	
	<div class="pustoi" style="height:42px">&nbsp;</div>
</div><!--main_lower_info-->
</div><!--main_content-->
<div class="clear-both">&nbsp;</div>
<div id="long_gray_stripe">
	<div class="long_gray_content">
	<table>
	<tr>
		<td>
		<div class="variants header">
			<h4>Товары</h4>
			<ul>
			<li><a href="#">Компьютеры</a></li>
			<li><a href="#">Комплектующие</a></li>
			<li><a href="#">Сети и интернет</a></li>
			<li><a href="#">Видеотехника</a></li>
			<li><a href="#">Аудиотехника</a></li>
			<li><a href="#">Мультимедиа</a></li>
			</ul>
		</div>
		</td>
		<td>
		<div class="variants">
			<ul>
			<li><a href="#">Фототехника</a></li>
			<li><a href="#">Техника для дома</a></li>
			<li><a href="#">Красота и здоровье</a></li>
			<li><a href="#">Расходники</a></li>
			<li><a href="#">Периферия</a></li>
			</ul>
		</div>
		</td>
		<td>
		<div class="variants">
			<ul>
			<li><a href="#">Средства связи </a></li>
			<li><a href="#">Програмное обеспечение</a></li>
			<li><a href="#">Техника для кухни </a></li>
			<li><a href="#">Климатическая техника</a></li>
			<li><a href="#">Аксессуары</a></li>
			</ul>
		</div>
		</td>
		
		<td>
		<div class="variants_turn">
		<div class="dbl_background">&nbsp;</div>
			<h4>Услуги</h4>
			<ul>
			<li><a href="#">Ремонт</a></li>
			<li><a href="#">Сервисное обслуживание</a></li>
			<li><a href="#">Диагностика</a></li>
			<li><a href="#">Восстановление</a></li>
			<li><a href="#">Установка ПО</a></li>
			<li><a href="#">Прошивка</a></li>
			</ul>
		</div>
		</td>
		<td>
		<div class="variants_kansha">
		<div class="dbl_background">&nbsp;</div>
			<h4>Kansha.kz</h4>
			<ul>
			<li><a href="#">Ремонт</a></li>
			<li><a href="#">Сервисное обслуживание</a></li>
			<li><a href="#">Диагностика</a></li>
			<li><a href="#">Восстановление</a></li>
			<li><a href="#">Установка ПО</a></li>
			<li><a href="#">Прошивка</a></li>
			</ul>
		</div>
		</td>
	</tr>
	</table>
	</div>
</div>

<div id="bg_lower">
	<div class="bg_lower_content">
	<table>
	<tr>
		<td>
		<div class="bg_lower_left">
			<p>&copy;2010 Все права защищены.<a href="#">Kansha.kz</a><br />
				Республика Казахстан, г. Алматы, ул. Беимбетова, 106
			</p>
		</div>
		</td>
		<td>
		<a href="#" class="bg_lower_center">Условия использования<br />
			и политика конфиденциальности
		</a>
		</td>
		<td>
		<div class="bg_lower_right">
			<a href="#"><img src="/images/logotipus.png" width="107" height="35"/></a>
			<div class="last">Разработка сайта &mdash;</div>
		</div>
		</td>
	</tr>
	</table>
	</div>
</div>

</body>
</html>