<?X3::profile()->start('text.widget');?>

<?php
$page = Page::get(array('name'=>$name,'status'),1);
?>
<div class="project" 
    <?if(isset($type) && $type==2):?>style="margin:21px 0 0 0 "<?endif;?>
    <?if(isset($type) && $type==1):?>style="margin:21px 262px 0 0"<?endif;?>
    >
<?if($page!==null):?>
    <table class="project_stripe">
        <tr>
            <td>
                <h2><?=$page->short_title?></h2>
            </td>
            <td width="100%">
                <div class="long_project_stripe">
                    <div class="project_left">&nbsp;</div>
                    <div class="project_right">&nbsp;</div>
                </div>
            </td>
        </tr>
    </table>


    <p><?=  X3_String::create(strip_tags($page->text))->carefullCut($type===2 || $type===1?600:255)?></p>
    <a href="/page/<?=$page->name?>.html" class="more_info">Подробнее</a>
<?endif;?>
    <?// X3_Widget::run('@layouts:widgets:subscribe.php')?>
</div>
<?X3::profile()->end('text.widget');?>
