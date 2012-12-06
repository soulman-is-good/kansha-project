<?php
$manus = X3::db()->query($sql); 
$count = mysql_num_rows($manus);
$row = ceil($count/3);
$i=0;
$j=0;
?>
[%<?=$count?>%]
        <table width="100%" class="brands">
            <tbody><tr>
                    <td width="33%">
                        <ul>
                            <? 
                            while ($man = mysql_fetch_object($manus)): 
                                $cnt = X3::db()->fetch("SELECT COUNT(0) cnt FROM `shop_item` `si` 
                                    INNER JOIN `manufacturer` m ON m.id=si.manufacturer_id 
                                    INNER JOIN `company_item` `ci` ON `ci`.`item_id`=si.id {$ccond['join']}
                                    WHERE `m`.`id`=$man->id AND ci.price>0 {$ccond['where']}");
                                if($cnt['cnt']==0) continue;
                                ?>
                            <li><a href="/<?=$man->name.$link?>.html"><?=$man->title?></a><sup><?=$cnt['cnt']?></sup></li>
                                <?$i++;
                                if($i%$row==0) echo '</ul></td><td width="33%" align="'.($j++==0?'center':'right').'"><ul>';
                            endwhile; ?>
                       </ul>
                    </td>
                </tr>
            </tbody></table>
