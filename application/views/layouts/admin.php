<script type="text/javascript" src="/js/admin.js"></script>
<script type="text/javascript">
    //PHP routine
    panel = <?=json_encode(X3::app()->user->panel)?>;
</script>
<div class="admin-scape">
    <div class="inner">
        <div class="admin-scape-upper"><span>Административная часть</span><a class="admin-system-close" href="#close" onclick="panel.scape.css('display','none')">Закрыть</a></div>
        <div class="admin-main-content">&nbsp;</div>
        <div class="button-toolbar">&nbsp;</div>
    </div>
</div>
<div class="admin-panel">
    <div class="admin-panel-upper">
        <div class="buttCont"><a class="showbutton" href="#hide_panel" onclick="admin_panel();return false;"><img src="/images/_zero.gif" width="129" height="11" /></a></div>
        <div style="float:right"><img src="/images/_zero.gif" width="20" height="1" /></div>
        <a class="admin-system-close" href="/user/logout.html">Выход</a>
        <a class="admin-system-minimize" href="#minimize" onclick="admin_panel();return false;">Свернуть</a>
        <div style="float:right;font-size: 11px;color:#848282"><?=round(memory_get_peak_usage(true)/1024/1024,2)?> из <?=ini_get('memory_limit')."B"?></div>
    </div>
    <div class="admin-modules">
        <div class="admin-module"><a onclick="call_list('menu');return false;" href="#menu"><img src="/images/admin/modules/menu.png" alt="MENU" title="Меню" /></a><br/><a onclick="call_list('menu');return false;" href="#menu">Меню</a></div>
        <div class="admin-module-sep">&nbsp;</div>
        <div class="admin-module"><a href="#page" onclick="call_list('page');return false;"><img src="/images/admin/modules/page.png" alt="PAGE" title="Текстовые страницы" /></a><br/><a href="#page" onclick="call_list('page');return false;">Текстовые</a></div>
        <div class="admin-module-sep">&nbsp;</div>
        <div class="admin-module"><a href="#clients" onclick="call_list('clients');return false;"><img src="/images/admin/modules/user.png" alt="CLIENTS" title="Клиенты" /></a><br/><a href="#clients" onclick="call_list('clients');return false;">Клиенты</a></div>
        <div class="admin-module-sep">&nbsp;</div>
        <div class="admin-module"><a href="#faq" onclick="call_list('faq');return false;"><img src="/images/admin/modules/review.png" alt="REVIEW" title="Вопрос-ответ" /></a><br/><a href="#faq" onclick="call_list('faq');return false;">Вопрос-ответ</a></div>
        <div class="admin-module-sep">&nbsp;</div>
        <div class="admin-module"><a href="#section" onclick="call_list('section');return false;"><img src="/images/admin/modules/publics.png" alt="CATALOG" title="Каталог" /></a><br/><a href="#section" onclick="call_list('section');return false;">Каталог</a></div>
        <div class="admin-module-sep">&nbsp;</div>
        <div class="admin-module"><a href="#jobs" onclick="call_list('jobs');return false;"><img src="/images/admin/modules/works.png" alt="JOBS" title="Вакансии" /></a><br/><a href="#jobs" onclick="call_list('jobs');return false;">Вакансии</a></div>
        <div class="admin-module-sep">&nbsp;</div>
        <div class="admin-module"><a href="#syssettings" onclick="call_list('syssettings?order=group');return false;"><img src="/images/admin/modules/settings.png" alt="SETTINGS" title="Настройки" /></a><br/><a onclick="call_list('syssettings');return false;" href="#syssettings">Настройки</a></div>
    </div>
</div>
<div class="admin-panel-hidden">
    <div class="admin-panel-show" style="margin-right:155px;"><a class="hidebutton" href="#show_panel" onclick="admin_panel();return false;"><img src="/images/_zero.gif" width="129" height="25" /></a></div>
    <div style="float: right; padding-top: 12px;"><img width="84" height="13" src="/images/admin/logo.gif" style="display:block"></div>
</div>