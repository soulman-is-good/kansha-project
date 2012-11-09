<style>
    .login {
        margin-right:0 !important;
    }
</style>
<div class="product_expand">
        <table class="production">
        <tr>
            <td style="padding-right:10px;white-space:nowrap;height:47px">
                <h2>Восстановление пароля</h2>
            </td>
            <td style="width:100%">
                <div class="black_stripe">
                    <div class="left_stripe_black">&nbsp;</div>
                    <div class="right_stripe_black">&nbsp;</div>
                </div>
            </td>
        </tr>
    </table>
    <div class="product_right list_text" style="margin-top:-24px;margin-left:25px">
        <div class="product_comment">
            <table width="100%">
                <tbody><tr>
                        <td width="300px">
                            <form action="/company/login.html" method="post" name="company-form-general">
                                <?if(!empty($errors)):?>
                                <? foreach ($errors as $err): ?>
                                <div class="gray">
                                    <i><?=$err?></i>
                                </div>
                                <? endforeach; ?>
                                <?endif?>
                                <div class="gray">
                                    <i>*</i><span class="gray_inside"><b>E-mail</b> (первый указаный в ваших контактах)</span>
                                </div>
                                <input type="text" name="email" />

                                <div class="send"><input style="float:none" type="submit" value="Восстановить" class="photo"></div>
                            </form>
                        </td>
                    </tr>
                </tbody></table>
                    
            <div style="height:10px" class="pustoi">&nbsp;</div>
        </div>
    </div>

</div>