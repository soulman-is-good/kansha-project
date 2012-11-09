<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title><?= X3::app()->name; ?></title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <script src="/js/jquery.js"></script>
	<link rel ="stylesheet" type= "text/css" href ="/application/modules/Admin/css/style.css" />        
        <style>
            #rem{
                text-decoration: none;color:#2A094A;font-size:14px;padding:4px 6px;border:1px solid #2A094A;background: #000;                
            }
            #rem.active {
                color:#FFF;
                background:#2A094A;
            }
        </style>
        <script type="text/javascript">
                $(document).ready(function(){
                    $('.login input').focus();
                        $("#splash,#overlay").height($(this).height()).width($(this).width());
                        $("#overlay").css('opacity','1');
                        setTimeout(function(){$("#overlay").animate({'opacity':'toggle'},3000,function(){
//                                $(this).css({'opacity':1,'background':'url(/application/modules/Admin/images/admin/sp_load.gif) no-repeat 50% 65%','display':'block'});
//                                setTimeout(function(){$("#overlay,#splash").fadeOut('slow');},5000);
                        });},1000);
                    $('#rem').click(function(){
                        $(this).toggleClass('active');
                        var name = $(this).parent().children('button').attr('name');
                        if(name == '' || typeof name == 'undefined' || name == false || name == null){
                            $(this).parent().children('button').attr('name','rememberme');
                        }else
                            $(this).parent().children('button').attr('name','');
                        return false;
                    })
                });
        </script>
    </head>
    <body>
<div id="overlay"><!----></div>
<div id="splash">
        <div class="login">           
            <form name="instinct.CMS" id="instinct.CMS" method="POST">
                <input style="text-align:center" value="" name="password" type="password" /><br/>
                <button type="submit">Войти</button><a id="rem" href="#rememberme" title="Запомнить меня">&bull;</a>
            </form>
        </div>    
</div>        
    </body>
</html>
    