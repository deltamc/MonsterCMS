<?
$key_tmpl = rand(100, 10000);

$signature   = md5($conf['widgets']['feedback']['key'] . "&" . $email);
$send_script =     $conf['widgets']['feedback']['send_script'];


?>


<form target="feedback_<?=$id?>" id="feedback_form_<?=$id?>" name="feedback_form_<?=$id?>" class="form2" method="post">
    <div>
<label>Введите Ваше имя:</label>
            <input type="text" name="bvz" required='' />
    </div>
    <div>
        <label>Введите Ваш телефон:</label>
        <input type="text" required=""  name="ntktajyt"/>
    </div>

    <div>
        <label>Введите Ваш E-mail:</label>
        <input type="text" required=""  name="dfigjxnf"/>
    </div>

    <div>
        <label>Введите Ваше сообщение:</label>
            <textarea  required="" placeholder="" type="text" name="cjjotybt"></textarea>
    </div>
    <div class='submit' >
        <input type="button" value="Отправить" onclick="$('#feedback_form_<?=$id?>').attr('action','<?=$send_script?>').submit();" />
        <input type="hidden" name="gjxnf" value="<?=$email?>" />
        <input type="hidden" name="signature" value="<?=$signature?>" />
        <input type="hidden" name="form_id" value="<?=$id?>" />

    </div>
</form>

<iframe name="feedback_<?=$id?>" style="border:none;width:0px;height:0px;" frameborder="0" ></iframe>
