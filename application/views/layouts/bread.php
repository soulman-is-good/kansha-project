<? if ($bread):
    ?>
    <div class="upper_ssilki">
        <div style="position:relative;float:left;"><a class="house other" href="/">&nbsp;</a></div>
        <div style="position:relative;float:left;"><span class="other_noun inside">→</span></div>
        <? if(is_string($bread)):?>
        <?=$bread?>
        <?else:
        $c = count($bread);foreach ($bread as $x => $elem): 
            if(is_array($elem) && is_numeric(key($elem))):
            $b = array_shift($elem);
        $url = key($b);?>
        <div style="position:relative;float:left;"><a class="service" href="<?=$url?>"><?=$b[$url]?></a><a href="#menu" class="expand">&nbsp;</a><img width="8" height="1" src="/images/_zero.gif">
            <div style="display:none;z-index: 100" class="popup">
                <ul>
                    <li><a onclick="$('.popup').slideUp();return false;" class="why_why" href="<?=$url?>"><?=$b[$url]?></a></li>
                    <? foreach ($elem as $link): $k = key($link);?>
                    <li><a href="<?=$k?>"><?=$link[$k]?></a></li>
                    <? endforeach; ?>
                </ul>

            </div>				
        </div>
        <?else:
            $url = key($elem);?>
        <div style="position:relative;float:left;"><a class="service" href="<?=$url?>"><?=$elem[$url]?></a></div>
        <?if($x<$c-1):?>
        <div style="position:relative;float:left;margin-left: 5px;"><span class="other_noun inside">→</span></div>
        <?endif;?>
        <? endif;endforeach;endif;?>
        <div class="clearfix"><!----></div>
    </div>
<script type="text/javascript">
    $('.upper_ssilki a.expand').click(function(){
        var pp = $(this).siblings('.popup');
        var width = pp.width();
        pp.children('ul').children('li').each(function(){
            var t = 0 ;
            if(width<(t=8*$(this).text().length))
                width = t;
        })
        pp.width(width)
        pp.slideDown();
        return false;
    })
</script>
<? endif; ?>