<!doctype>
<html>
    <head>
        <title>Свойства</title>
        <script src="/js/jquery.js"></script>
        <script src="/js/jquery.treeview.js"></script>
        <link rel="stylesheet" type="text/css" href="/js/jquery.treeview.css" />
        <style>
            body {
                font-size: 72%;
            }
        </style>
    </head>
    <body>
        <ul id="xtree">
        <? foreach ($models as $model): if(!empty($model['values'])):?>
            <li>
                <?=$model['title']?>
                <ul>
                    <? foreach ($model['values'] as $value): ?>
                    <li><?=$value?></li>
                    <? endforeach; ?>
                </ul>
            </li>
        <? endif;endforeach; ?>
        </ul>
        <script>
            $(function(){
                $('#xtree').treeview({
                    persist: "location",
                    collapsed: true,
                    unique: true                     
                });
            })
        </script>
    </body>
</html>
