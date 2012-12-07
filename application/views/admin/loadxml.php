<?php
?>
<form action="/admin/loadcsv.html" target="xframe" method="post" enctype="multipart/form-data">
    <input type="file" name="xml" />
    <button type="submit" class="button">Загрузить</button>
</form>
<iframe name="xframe" id="xframe" style="margin-top: 10px;width:60%"></iframe>
<script type="text/javascript">
    $("#xframe").ready(function(){
        /*var m = eval('('+$(this).html()+')');
        if(m!=null)
            alert(m.message);*/
    })
</script>