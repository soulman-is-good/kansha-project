<?php

return array(
        'admin' => array(
            'Menu'=>array(
                'general'=>array('add'),
                'common'=>array('add_sub','edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Меню')
            ),
            'Region'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Регион')
            ),
            'Sale'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Распродажа')
            ),
            'Meta'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+')
            ),
            'Notify'=>array(
                'general'=>array('add','sendclients'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+','sendclients'=>'Оповещение')
            ),
            'User_Storage'=>array(
                'general'=>array(),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array()
            ),
            'News'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Новость')
            ),
            'News_Grabber'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Граббер')
            ),
            'Article'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Обзор')
            ),
            'Admin_Menu'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Меню')
            ),
            'Admin_Tools'=>array(
                'general'=>array(),
                'common'=>array(),
                'direct'=>array('process','cleancache','redirects','restore','tasks'),
                'labels'=>array()
            ),
            'Grabber'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Граббер')
            ), 
            'UI'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ ЮИ')
            ),
            'Shop_Item'=>array(
                'general'=>array('add'),
                'common'=>array('edit','clone','delete'),
                'direct'=>array('save','filter-title'),
                'labels'=>array('add'=>'+')
            ),
            'Shop_Group'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete','genmodels','meta'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Группа','genmodels'=>'Генерировать модели')
            ),
            'Shop_Category'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Категория')
            ),
            'Manufacturer'=>array(
                'general'=>array('add','listadd'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Производитель','listadd'=>'Добавить списком'),
                'misc'=>array('textTab')
            ),
            'Company'=>array(
                'general'=>array('add'),
                'common'=>array('edit','excel','upload','content','autoupdate','payment'),
                'direct'=>array('save','delete'),
                'labels'=>array('add'=>'+ Компания','toexcel'=>'в Excel'),
                'misc'=>array('tabSeo','tabAddress','tabService','tabPayments')
            ),
            'Service'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Услуга')
            ),
            'SysSettings'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Группа')
            ),
            'Page'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Страницу')
            ),
            'Banner'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Баннер')
            ),
            'Site_Feedback'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Отзыв')
            ),
            'Shop_Feedback'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Отзыв')
            ),
            'Company_Feedback'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Отзыв')
            ),
            'User'=>array(
                'general'=>array('add'),
                'common'=>array('edit','delete'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Пользователя')
            ),
        ),
        'moderator'=>array(
            'Shop_Item'=>array(
                'general'=>array('add'),
                'common'=>array('edit','clone','delete'),
                'direct'=>array('save','filter-title'),
                'labels'=>array('add'=>'+')
            ),
            'Company'=>array(
                'general'=>array('add'),
                'common'=>array('edit','excel','upload','autoupdate'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Компания'),
                'misc'=>array('tabAddress','tabService')
            ),
            'Manufacturer'=>array(
                'general'=>array('add'),
                'common'=>array('edit'),
                'direct'=>array('save'),
                'labels'=>array('add'=>'+ Производитель')
            ),
        )
    );
