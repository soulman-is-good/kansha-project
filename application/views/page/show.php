<?php
    //if($model->parent_id>0)
        //$parents = X3::db()->query("SELECT id, short_title, name FROM data_page WHERE status");// AND parent_id=$model->parent_id");
    //else
        $parents = X3::db()->query("SELECT id, short_title, name FROM data_page WHERE status");// AND parent_id=$model->id");
?>
<div class="product_expand">
    <table class="production">
        <tr>
            <td style="padding-right:10px;white-space:nowrap;height:47px">
                <h2><?=$model->title?></h2>
            </td>
            <td style="width:100%">
                <div class="black_stripe">
                    <div class="left_stripe_black">&nbsp;</div>
                    <div class="right_stripe_black">&nbsp;</div>
                </div>
            </td>
        </tr>
    </table>
    <?if(mysql_num_rows($parents)>0):?>
    <div class="product_left_side" style="margin-top:-12px">
        <ul class="text_list">
            <?while($p = mysql_fetch_assoc($parents)):?>
                <?if($p['id'] == $model->id):?>
                <li><span style="line-height: 2"><?=$p['short_title']?></span></li>
                <?else:?>
                <li><a href="/page/<?=$p['name']?>.html"><?=$p['short_title']?></a></li>
                <?endif;?>
            <?endwhile;?>
        </ul>
    </div><!--product_left_side-->
    <div class="product_right list_text">
    <?else:echo'<div class="product_right list_text" style="margin-top:-24px;margin-left:25px">'; endif;?>
<?=$model->text?>
    </div><!--product_right-->
<?=  X3_Widget::run('@layouts:widgets:BannerBottom.php',array('style'=>'margin-top:43px;margin-bottom:20px'));?>
</div>
