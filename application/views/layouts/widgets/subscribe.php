    <div class="mailer">
        <span>Рассылка</span>
        <div class="mailer_inside">
            <form id="subscribe-form" name="subscribe-form" method="post" action="/subscribe/me.html">
                <input type="text" name="name" placeholder="Ваше имя" />
                <input type="text" name ="email" placeholder="E-mail" />
                <button type="submit">Подписаться</button>
            </form>
        </div>                                                                                    
        <div class="clear-both">&nbsp;</div>
    </div>
<script type="text/javascript">
    $('#subscribe-form').submit(function(){
        $.post($(this).attr('action'),$(this).serialize(),function(m){
            if(m.status=='ERROR'){
                //TODO: ERROR
            }else{
                if(m.status == 'OK')
                    $('.mailer').children('span').html('Вы успешно подписаны на рассылку').css('padding-left','48px');
                else
                    $('.mailer').children('span').html('Вы уже подписаны на рассылку').css('padding-left','55px');
                $('.mailer input').remove();
                $('.mailer button').html('Отписаться');
            }
        },'json').error(function(){})
        return false;
    })
</script>