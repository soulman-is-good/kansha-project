<?php
$q = "SELECT r.id id, r.name name, r.title title FROM data_region r INNER JOIN data_address a ON a.city=r.id INNER JOIN data_company c ON c.id=a.company_id WHERE 1 GROUP BY r.id ORDER BY r.title";
$a = X3::db()->query($q);
if(is_resource($a)):
    $city = array('title'=>'Казахстан');
    if(X3::user()->city>0)
        $city = X3::db()->fetch("SELECT * FROM data_region WHERE id=".X3::user()->city);
    $i = 0;
    $html = '<li><a onclick="$(\'.inda_city_inside\').slideUp();return false;" href="#"><span>'.$city['title'].'</span><i>&nbsp;</i></a></li>';
    while($c = mysql_fetch_assoc($a)){
        if(X3::user()->city!=$c['id'])
        $html .= '<li><a href="/'.$c['name'].'-shops.html"><span>'.$c['title'].'</span></a></li>';
        $i++;
    }
    if(X3::user()->city>0)
        $html .= '<li><a href="/all-shops.html"><span>Казахстан</span></a></li>';
?>
<div class="inda_city upper">
    <a onclick="$(this).siblings('.inda_city_inside').slideDown();return false;" href="#"><span><?=$city['title']?></span><i>&nbsp;</i></a>
    <div style="display:none" class="inda_city_inside">
        <ul>
            <?=$html?>
        </ul>
    </div>

</div>
<?endif;?>