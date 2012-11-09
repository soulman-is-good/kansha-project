<?X3::profile()->start('servicesFor.widget');?>

<?if($model->group_id==0):?>
<table style="position:relative;width:100%" class="company_service">
            <tbody><tr>
                    <td style="white-space:nowrap;padding-right:10px;height:19px">
                        <h2>Услуги для</h2>
                    </td>
                    <td width="100%">
                        <div style="width:auto" class="orange_company">
                            <div class="orange_left">&nbsp;</div>
                            <div class="orange_right">&nbsp;</div>
                        </div>

                    </td>
                </tr>
            </tbody></table>
<table width="100%" class="des_table_list">
            <tbody><tr>
                    <td>
                        <ul>
                            <li><a href="#">Адаптация</a><sup>6</sup></li>
                            <li><a href="#">Восстановление</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><a href="#">Консультации</a><sup>6</sup></li>
                            <li><a href="#">Модернизация</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><a href="#">Ремонт</a><sup>6</sup></li>
                            <li><a href="#">Диагностика</a><sup>5</sup></li>
                        </ul>
                    </td>
                    <td width="170px">
                        <ul>
                            <li><a href="#">Гарантийное обслуживание</a><sup>6</sup></li>
                            <li><a href="#">Прошивка</a><sup>5</sup></li>
                        </ul>
                    </td>
                </tr>
            </tbody></table>
<?endif;?>
<?X3::profile()->end('servicesFor.widget');?>
