<?X3::profile()->start('nowFind.widget');?>

<?php
    $q = X3::db()->query("SELECT * FROM data_search WHERE 1 ORDER BY `count` DESC");
    if(mysql_num_rows($q)>0):
?>
    <div <?=isset($main)?'style="margin-top:31px"':''?> class="now_find">
            <table class="find_find">
                <tbody><tr>
                        <td style="width:118px">
                            <h2>Сейчас ищут</h2>
                        </td>
                        <td>
                            <div class="now_find_stripe">
                                <div class="find_left">&nbsp;</div>
                                <div class="find_right">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </tbody></table>


            <ul>
                <?while($a = mysql_fetch_assoc($q)):?>
                <li><a href="/search.html?search=<?=$a['keyword']?>"><?=$a['keyword']?></a></li>
                <?endwhile;?>
            </ul>
        </div>
<?endif;?>
<?X3::profile()->end('nowFind.widget');?>
