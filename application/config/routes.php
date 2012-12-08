<?php
$manus = X3::app()->db->fetchAll("SELECT name FROM manufacturer WHERE status ORDER BY title");
$ms = array();
foreach($manus as $man){
    $ms[] = str_replace(array("'","\\","/","-"),array("\\'","\\\\","\\/","\\-"),strtolower($man['name']));
}
unset($manus);
$ms = implode('|', $ms);
return
array(
    //Common
    '/\/search\/(.+)$/'=>array('/search/index/$1'),
    '/^\/moduler(.*)$/'=>array('/Admin_Moduler$1'),
    '/^\/sitemap\.xml$/' => array('/site/map/type/xml', true), // first element - module/action, second - if either way then redirect
    '/^\/sitemap.html$/' => array('/site/map/type/html'), 
    '/^\/sitemap\/(.*)$/' => array('/site/map/type/html$1'), 
    '/^\/login\/(.*)$/' => array('/admin/index/name/$1', true),
    '/^\/logout(.*)$/' => array('/user/logout$1', true),
    '/^\/feedback\.html$/' => array('/site/feedback.html', true),
    '/^\/contacts\.html$/' => array('/site/contacts.html', true),
    //Manufactirers
    '/^\/manufacturers\.html$/'=>array('/manufacturer/index.html',false),
    '/^\/manufacturer\/(.+?)\-group([0-9]+?)\-p([0-9]+?)\.html$/' => array('/manufacturer/index/gid/$2/pid/$3.html', true),
    '/^\/manufacturer\/(.+?)\-group([0-9]+?)\.html$/' => array('/manufacturer/index/gid/$2.html', true),
    "~^/($ms)\/(.+?)\-group([0-9]+?)\-p([0-9]+?)\.html$~"=>array('/manufacturer/show/name/$1/gid/$2/pid/$3.html',false),
    "~^/($ms)\/(.+?)\-group([0-9]+?)\.html$~"=>array('/manufacturer/show/name/$1/gid/$2.html',false),
    "~^/($ms)\.html$~"=>array('/manufacturer/show/name/$1.html',false),
    //Shop_Category
    '/^\/(.+?)\-c([0-9]+?)\.html$/' => array('/Shop_Category/show/id/$2.html', true),
    //Shop_Group
    '/^\/vse-razdely\.html$/' => array('/Shop_Group/index.html', true),
    '/^\/-group([0-9]+?)[^\/]*\/reset\.html$/' => array('/Shop_Group/reset/id/$1.html', true),
    '/^\/(.+?)\-group([0-9]+?)\.html$/' => array('/Shop_Group/show/id/$2.html', true),
    "~^\/(.+?)\-group([0-9]+?)\/($ms)(.*)$~" => array('/Shop_Group/show/id/$2/manuf/$3$4.html', true),
    '/^\/(.+?)\-group([0-9]+?)\/(.+?)\.html$/' => array('/Shop_Group/show/id/$2/$3.html', true),
    '/^\/(.+?)\-group([0-9]+?)\-p([0-9]+?)\.html$/' => array('/Shop_Group/show/id/$2/propid/$3.html', true),
    "~^\/(.+?)\-group([0-9]+?)\-p([0-9]+?)\/($ms)(.*)$~" => array('/Shop_Group/show/id/$2/propid/$3/manuf/$4$5.html', true),
    '/^\/(.+?)\-group([0-9]+?)\-p([0-9]+?)\/(.+?)\.html$/' => array('/Shop_Group/show/id/$2/propid/$3/$4.html', true),
    //Company
    '/^\/shops(.*)$/'=>array('/Company/index$1'),
    '/^\/([\S]+)\-shops(.*)$/'=>array('/Company/index/city/$1$2'),
    '/^\/.+?\-company([0-9]+)\/address(.*)$/'=>array('/Company/show/id/$1/type/address$2'),
    '/^\/.+?\-company([0-9]+)\/about(.*)$/'=>array('/Company/show/id/$1/type/about$2'),
    '/^\/.+?\-company([0-9]+)\/sale(.*)$/'=>array('/Company/show/id/$1/type/sale$2'),
    '/^\/.+?\-company([0-9]+)\/services(.*)$/'=>array('/Company/show/id/$1/type/services$2'),
    '/^\/.+?\-company([0-9]+)\/feedback(.*)$/'=>array('/Company/show/id/$1/type/feedback$2'),
    '/^\/.+?\-company([0-9]+)(.*)$/'=>array('/Company/show/id/$1$2'),
    '/^\/buy\/([0-9]+)\.html$/'=>array('/Company/buy/item/$1'),
    //Shop_Item
    '/^\/(\d+)($|[\/]$|\.html$)/'=>array('/Shop_Item/show/id/$1'),
    '/^\/(.+)?\-(\d+?)\/prices\.html$/'=>array('/Shop_Item/prices/id/$2'),
    '/^\/(.+)?\-(\d+?)\/services(.*)$/'=>array('/Shop_Item/services/id/$2$3'),
    '/^\/(.+)?\-(\d+?)\/feedback(.*)$/'=>array('/Shop_Item/feedback/id/$2$3'),
    '/^\/(.+)?\-(\d+?)\.html$/'=>array('/Shop_Item/show/id/$2$3'),
    //Shop_Properties
    '/^\/props\/(.*?)$/'=>array('/Shop_Properties/$1'),
    //Service
    '/^\/services\.html$/'=>array('/service/index.html'),
    '/^\/service\/category([0-9]+?)(.*)$/'=>array('/service/show/id/$1$2'),
    '/^\/service\/city\/(.*)$/'=>array('/service/show/city/$1'),
    '/^\/service\/(.+)$/'=>array('/service/show/name/$1'),
    //Page
    '/^\/page\/(.+)?$/' => array('/page/show/name/$1', true),
    //Articles/News
    '/^\/sale\/([0-9]+)?(.*)$/' => array('/sale/show/id/$1$2', true),
    '/^\/news\/([0-9]+)?(.*)$/' => array('/news/show/id/$1$2', true),
    '/^\/article\/([0-9]+)?(.*)$/' => array('/article/show/id/$1$2', true),
    '/^\/articles(.*)$/' => array('/article/index/article/1$1', true),
    
        )
?>
