<?php
$count = mysql_num_rows($ms);
$paginator = new Paginator('Company', $count);
$q .= " LIMIT $paginator->offset,$paginator->limit";
$models = X3::db()->query($q);
?>
<div class="main_popular">
    <table width="50%" style="float:right" class="with_navi">
        <tbody><tr>
                <td style="float:right">
                    <div class="selector_shops service">
<?=X3_Widget::run('@views:company:_sort.php');?>
                    </div>
                </td>
                <td width="140">
                    <div style="margin-top:1px" class="sale_selector expand">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
                    </div>
                </td>
            </tr>
        </tbody></table>


    <table class="big_table">
        <tbody><tr class="shops_table_header">
                <td style="width:112px">
                    <span>Название</span>
                </td>
                <td>
                    <span>Контакты</span>
                </td>
                <td colspan="2">
                    <span>Адрес</span>
                </td>

            </tr>
<? while($m = mysql_fetch_assoc($models)): 
    $model = new Company;
    $model->getTable()->acquire($m);
    $model->getTable()->setIsNewRecord(false);
    $image = false;
    $address = $model->getAddress();
    $phones = $address->phones;
    $name = X3_String::create($model->title)->translit();
    $name = preg_replace("/['\"\.\/\-;:\+\)\(\*\&\^%\$#@!`]/", '', $name);    
    $region = '';
    if($address && $address->getRegion() == null)
        $region = $address->getRegion()->title . ' ';
    if(is_file('uploads/Company/'.$model->image)){
        $image = "/uploads/Company/88x31xh/$model->image";
    }    
    ?>
            <tr>
                <td>
                    <div class="go_site">
                        <?if($image):?>
                        <a href="/<?=$name?>-company<?=$model->id?>.html"><img src="<?=$image?>" title="<?=$model->title?>" style="text-decoration: none" /></a><br>
                        <?endif;?>
                        <?if(!empty($m['site'])):?>
                        <noindex>
                            <div class="go_site_link"><a target="_blank" href="/company/go/to/<?=base64_encode($m['id'])?>">Перейти на сайт продавца</a></div>
                        </noindex>
                        <?endif;?>
                    </div>
                </td>
                <td style="width:182px">
                    <div class="shops_contact">
                        <ul class="number">
                            <? if(!empty($phones)) foreach ($phones as $z => $phone): ?>
                            <li><?=$phone?></li>
                            <? endforeach; ?>
                        </ul>
                        <ul class="e-mail">
                            <?if($address->email!=''):?>
                                <li>e-mail: <a href="mailto:<?=$address->email?>"><?=$address->email?></a></li>
                            <?endif;?>
                            <?if($address->skype!=''):?>
                                <li>skype: <a href="skype:<?=$address->skype?>"><?=$address->skype?></a></li>
                            <?endif;?>
                            <?if($address->icq!=''):?>
                                <li>icq: <a href="#"><?=$address->icq?></a></li>
                            <?endif;?>
                        </ul>
                    </div>
                </td>
                <td>
                    <div class="shops_adress">
                        <p><?=$m['title']?><br/>
                            <?=$region . $address->address?></p>
                    </div>
                    <a href="/<?=$name?>-company<?=$model->id?>.html" style="color: #2068A5;font-size: 11px;">Посмотреть профиль продавца</a>
                </td>
                <td style="width:185px">
                    <div class="shops_sort_by">
     <?=  X3_Widget::run('@layouts:widgets:stars.php',array('class'=>'Company','query'=>array('company_id'=>$model->id),'url'=>"/$name-company$model->id/feedback.html"))?>
                    </div>
                </td>
            </tr>
<? endwhile; ?>            
        </tbody></table>

    <div style="float:right;margin-top:21px" class="sale_selector">
<?=X3_Widget::run('@layouts:widgets:pageselect.php',array('paginator'=>$paginator));?>
    </div>

    <div style="float:right;top:20px" class="selector_shops service">
<?=X3_Widget::run('@views:company:_sort.php');?>
    </div>

    <div style="float:none" class="navi">
<?=$paginator?>
    </div>


    <div style="height:10px" class="pustoi">&nbsp;</div>
    <?=X3_Widget::run('@layouts:widgets:BannerBottom.php')?>
</div>
<script type="text/javascript">
    $('.nameSort').change(function(){
        $.get('/company/sort/',{name:$(this).val()},function(m){
            if(m=='OK')
                location.reload();
        })
    });
</script>