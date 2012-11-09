<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?=X3::app()->name;?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/gradients.css" />
    <link rel="stylesheet" type="text/css" href="/css/style1.css" />
    <script src="/js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/checkboxes.js"></script>
    <script type="text/javascript">
        function add_favorite(a) { if(document.all) window.external.AddFavorite(document.location.href, document.title); else if (typeof(opera)=="object") { a.rel="sidebar"; a.title=document.title; a.url=document.location.href; return true; } else window.sidebar.addPanel(document.title,document.location.href,""); return false; }
        function makestart(a) {if(document.all) {a.style.behavior='url(#default#homepage)'; a.setHomePage(document.location.href);}else{alert('Чтобы добавить в избранное, нажмите Ctrl+D')}return false;}
    </script>
</head>
<body>
<div class="body-gradient1"><!-- --></div>
<div class="content22">
    <div class="lozung22">&laquo;Мы делаем все для удобства выбора,
        <br />размещения, оплаты и доставки запчастей&raquo;
	</div>
		<div id="privet">Добро пожаловать!</div>
		<div class="picturebig" style="position:relative">
                <div style="position: absolute; font: 12px Arial; color: #FF0000; top: 54px; width: 100%; left: 0px; text-align: center; background: #FFFFFF;"><?=$error?></div>
		<div class="vstavka">
		<form name="login" method="post">
			<span>Логин:</span>
			<div class="dlina"><input name="User[uid]" type="text" value="<?=$user['uid']?>" size="24"/></div>
			<span class="password">Пароль:</span>
			<div class="dlina1"><input name="User[password]" type="password" value="<?=$user['password']?>" size="24"/></div>
			<br />
			<div class="new"><input type="checkbox" name="rememberme" value="yes">
			<div>Запомнить меня</div>
			<div class="bigbutton"><button type="submit">Войти</button></div>
			</div>
			</form>

		</div>
		</div>
			<div class="ssilkaP">
                                <?if(IS_IE):?>
				<div id="start"><a href="#" onclick="makestart(this)">Сделать стартовой</a></div>
                                <?endif?>
				<div id="dobavit" <?=(!IS_IE)?'style="margin-right:65px"':''?>><a href="#" onclick="add_favorite(this)">Добавить в избранное</a></div>
                                <div style="clear: both"><!-- --></div>
			</div>
</div>
</body>
</html>