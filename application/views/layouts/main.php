<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=X3::app()->name?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="author" content="Kansha" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <?if(X3::app()->ogtitle!==null):?>
        <meta property="og:title" content="<?=X3::app()->ogtitle?>" />
        <?endif;?>
        <?if(X3::app()->ogdescription!==null):?>
        <meta property="og:description" content="<?=X3::app()->ogdescription?>" />
        <?endif;?>
        <meta property="og:type" content="company" />
        <?if(X3::app()->ogurl!==null):?>
        <meta property="og:url" content="<?=X3::app()->ogurl?>" />
        <?endif;?>
        <?if(X3::app()->image!==null):?>
        <meta property="og:image" content="<?=X3::app()->image?>" />
        <?endif;?>
        <meta property="og:site_name" content="<?=X3::app()->name?>" />
        <meta property="fb:app_id" content="" />
        <meta name="google-site-verification" content="p0YgTR35WVWJQLtUHwQ28crm6B0oZHMu5fNhDj6OlQE" />
        <meta name="google-site-verification" content="PxeOSvNkk1faqb_vNt7U-yVgeqk6qdx3eRC92OfbSFA" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/js/tipTip.css" />
        <script type="text/javascript" src="/js/jquery.js"></script>
        <script type="text/javascript" src="/js/jquery.tipTip.js"></script>
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?71"></script>
        <!--[if IE]> <link rel="stylesheet" type= "text/css" href ="/css/style.ie.css" /> <![endif]-->
        <!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
        <![endif]-->
        <script src="/application/modules/Admin/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>
        <? if(X3::app()->module->controller->id=='shop_Group' && X3::app()->module->controller->action == 'show'): ?>
        <link rel="stylesheet" href="/css/slider.css" type="text/css" />
        <script type="text/javascript">
            $(function(){
                $('#slider').slider({
                    range: true,
                    min:<?=$minPrice?>,
                    max:<?=$maxPrice?>,
                    values: [<?=$priceMin?>, <?=$priceMax?>],
                    create:function(event,ui){
                        $("#slider a").eq(1).addClass('right');
                    },
                    slide: function( event, ui ) {
                        $( "#Price_min" ).val(ui.values[ 0 ]);
                        $( "#Price_max" ).val(ui.values[ 1 ]);
                    }
                });                
            })
        </script>
        <?endif;?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("a").each(function() {
                    $(this).attr("hideFocus", "true").css("outline", "none");
                });
                $('.showperpage').change(function(){
                    $.post('/site/limit',{'module':$(this).attr('module'),'val':$(this).val()},function(m){
                        if(m=='OK'){
                            location.reload();
                        }
                    })
                })                
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
                <div style="position:absolute;left:100px;top:0px"><a style="background:none" href="/sitemap.html" class="link_two">Карта сайта</a></div>
                <div class="enter">
                    <a href="#" class="link_one" onclick="$(this).siblings('.enter_market').slideDown();return false;"><i>&nbsp;</i><span>Вход для магазинов</span></a>
                    <div class="enter_market" style="position:absolute;z-index:10;display:none">
                        <a href="#" class="link_green" onclick="$(this).parent().slideUp();return false;"><i>&nbsp;</i><span>Вход для магазинов</span></a>
                        <form name="company-login-form" method="post" action="/company/login.html">
                            <input type="text" name="fio" size="12" class="fio_name" placeholder="Имя пользователя" />
                            <input type="password" name="pass" class="secret" placeholder="Пароль" />
                            <a href="/company/restore.html" class="forget">Забыли пароль?</a><br />
                            <input type="checkbox" name="remember" value="yes" class="try" ><span class="try_remember">Запомнить</span>
                            <button type="submit">Войти</button>
                        </form>
                    </div>
                </div>	
                    <?php
                    $toplinks = X3::db()->query("SELECT name, short_title FROM data_page WHERE ontop ORDER BY weight DESC, title DESC");
                    if(is_resource($toplinks) && mysql_num_fields($toplinks)):
                        while($l = mysql_fetch_assoc($toplinks)):
                    ?>
                    <a href="/page/<?=$l['name']?>.html" class="link_two"><?=$l['short_title']?></a>
                    <?endwhile;?>
                    <?endif;?>
                    <a href="/page/help.html" class="link_two active">Помощь</a>
                </div>
            </div>
                                        
                                        <div id="head_content">
                                            <div class="main_header">
                                                <div class="main_logo">
                                                    <a href="http://www.kansha.kz/" title="Kansha.kz"><img src="/images/kansha.jpg" width="184" height="38" alt="kansha.kz" title="kansha.kz" /></a>
                                                    <noindex>
                                                    <p style="width:200px">В нашем каталоге <span class="number"><?=X3::user()->citemCount!==null?X3::user()->citemCount:(X3::user()->citemCount=Company_Stat::getStat('items'))?></span> 
                                                        <?=X3_String::create(" ")->numeral(X3::user()->itemCount,array("предложение","предложения","предложений"))?> от 
                                                        <span class="how_much"><?=X3::user()->shopCount!==null?X3::user()->shopCount:(X3::user()->shopCount=Company_Stat::getStat('companies'))?></span> 
                                                        <?=X3_String::create(" ")->numeral(X3::user()->shopCount,array("интернет-магазин","интернет-магазина","интернет-магазинов"))?>.</p>
                                                    </noindex>
                                                </div>
                                                
                                                <?=  X3_Widget::run('@layouts:widgets:BannerTop.php')?>
                                                
                                                <div class="find_it">
                                                    <noindex>
                                                    <div style="margin-bottom: 10px;text-align: center;">
                                                        <span>Хотите начать продавать свои товары через kansha.kz</span>
                                                        <a href="/page/start_selling.html" class="main_red_link">Добавить свои товары</a>
                                                    </div>
                                                    </noindex>
                                                    <div>
                                                        <div class="find_it_inside">
                                                            <form name="search-form" action="/search.html" method="get">
                                                            <table class="find_table" cellspacing="0" cellpadding="0"><tr><td class="left"><input type="text" name="search" size="12" placeholder="Что ищем?" />
                                                                    </td><td class="right">
                                                                        <button>Найти</button>
                                                                    </td></tr></table>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clear-both">&nbsp;</div>
                                                <div class="main_menu">
                                                    <nav>
                                                    <?= $this->renderPartial('@layouts:menu.php'); ?>
                                                    <?= $this->renderPartial('@layouts:bread.php', array('bread' => isset($bread) ? $bread : false)); ?>
                                                    </nav>
                                                </div>


                                            </div><!--main_header-->
                                            <div class="clear-both">&nbsp;</div>

<?if(!in_array($class,array('error','login'))):?>
                                            <div class="main_banner_block">
                                                <?=isset($tender)?$tender:''?>
                                                <?=$this->renderPartial('@layouts:widgets:BannerRight.php');?>
                                                <?//$this->renderPartial('@layouts:widgets:specialOffer.php');?>
                                                <?=X3_Widget::run('@layouts:widgets:topShops.php',array(),array('cache'=>true,'cacheConfig'=>array('expire'=>'+1 hour')));?>
                                                
                                                


                                            </div><!--Banners-->
<?endif;?>





                                            <div class="main_content_left<?=(isset($class)?" $class":'')?>">
                                                <?= $content ?>	
                                            </div><!--main_content_left-->
                                            <div class="clear" style="clear:both">&nbsp;</div>
                                            <? if (X3::app()->request->url == '' || X3::app()->request->url == 'site/index'): ?>
                                                <div class="main_lower_info">
                                                    <?//  X3_Widget::run('@layouts:widgets:nowFind.php',array('main'=>true))?>
                                                    <?=  X3_Widget::run('@layouts:widgets:vk_widget.php')?>
                                                    <?=  X3_Widget::run('@layouts:widgets:text.php',array('name'=>'about','type'=>1))?>
                                                    
                                                                            <div class="clear-both">&nbsp;</div>


                                                                            <div class="pustoi" style="height:42px">&nbsp;</div>
                                                                            </div><!--main_lower_info-->
                                                                        <? endif; ?>
                                                                        </div><!--head_content-->
                                                                        <div class="clear-both">&nbsp;</div>
<?=  X3_Widget::run('@layouts:footer.php',array(),array('cache'=>true))?>

                                                                        <div id="bg_lower">
                                                                            <div class="bg_lower_content">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="bg_lower_left">
                                                                                                <?=SysSettings::getValue('copyright', 'text', 'Копирайт', NULL, '<p>&copy;2010 Все права защищены.<a href="/">Kansha.kz</a><br />Республика Казахстан, г. Алматы, ул. Беимбетова, 106</p>')?>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <!--a href="/page/agreement.html" class="bg_lower_center">Условия использования<br />
                                                                                                и политика конфиденциальности
                                                                                            </a-->
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="bg_lower_right">
                                                                                                <a href="http://instinct.kz/" target="_blank"><img src="/images/logotipus.png" width="107" height="35"/></a>
                                                                                                <div class="last">Разработка сайта &mdash;</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
<img style="display:none" src="http://www.kansha.kz/logo.jpg" width="100" alt="www.kansha.kz" title="www.kansha.kz" />                                                                        
<?=SysSettings::getValue('counter01', 'html', 'Счетчик', NULL, '')?>
                                                                        </body>
                                                                        </html>