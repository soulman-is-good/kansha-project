<?php
return array(
    'basePath'=>dirname(dirname(dirname(__FILE__))),
    'baseUrl'=>'http://www.kansha.kz',
    'name'=>'Kansha.kz',
    //'grabber'=>'http://localhost:8111',
    'grabber'=>'http://localhost:3435',
    //'errorController'=>'site/error',
    'uri'=>array(
        'suffix'=>'html'
    ),
    'locale'=>'ru',
    //'languages'=>array('en','de','fr'),
    'components'=>array(
        'profile'=>array(
            'class'=>'Profile',
            'time'=>microtime(true),
            'enable'=>false
        ),
        'db'=>array(
            'host'=>'localhost',
            'user'=>'root',
            'password'=>'ghj,bhrf2011',
            'database'=>'kansha_tmp'
        ),
        'mongo'=>array(
            'class'=>'X3_MongoConnection',
            'host'=>'localhost',
            'user'=>null,
            'password'=>null,
            'database'=>'kansha',
            'lazyConnect'=>true,
        ),
        'admin'=>array(
            'class'=>'AdminSide'
        ),
        'seo'=>array(
            'class'=>'SeoHelper'
        ),
//        'optitmizer'=>array(
//            'class'=>'Optimizer',
//            'predefine'=>include('meta.php')
//        ),
        'router'=>array(
            'routes'=>  'application/config/routes.php'//include('routes.php')
        ),
        'log'=>array(
            'dblog'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'mysql-{d-m-Y}.log',
                'category'=>'db'
            ),
            'applog'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'application-{d-m-Y}.log',
                'category'=>'application'
            ),
            'kansha_error'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'ks.error-{d-m-Y}.log',
                'category'=>'kansha_error'
            ),
            'mailer'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'mailer-{d-m-Y}.log',
                'category'=>'mailer'
            ),            
        )
    )
);
?>

