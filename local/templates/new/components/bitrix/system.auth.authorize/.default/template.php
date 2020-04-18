<?CJSCore::Init('jquery')?>
<div class="block-message">
</div>
<div style="display:none;">
    <div class="modal active" id="sign">
        <form action="/local/tools/ajax.php" class="modal-block">
            <div class="modal-title">Вход или регистрация</div>
            <div class="modal-desc">Введите Ваш номер телефона</div>
            <div class="modal-body">
                <form action="">
                    <input type="tel" name="PHONE_NUMBER" placeholder="+7 (___) ___-__-__" required="">
                    <input type="submit" class="btn blue js-init-action" data-action="send_form" data-id="auth" value="Получить код">
                    <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c политикой конфиденциальности</div>
                </form>
            </div>
        </form>
    </div>
</div>
<div style="display: none">
    <div class="modal active" id="code">
        <form action="/local/tools/ajax.php" class="modal-block">
            <button class="arcticmodal-close close"></button>
            <div class="modal-title">Введите код</div>
            <div class="modal-desc bold">Мы отправили код на номер <span class="modal-desc__phone"></span></div>
            <button class="arcticmodal-close back">Изменить</button>
            <div class="modal-body">
                <div class="sms-code">
                    <input type="text" name="CODE[]" class="digit" maxlength="1" />
                    <input type="text" name="CODE[]" class="digit" maxlength="1" />
                    <input type="text" name="CODE[]" class="digit" maxlength="1" />
                    <input type="text" name="CODE[]" class="digit" maxlength="1" />
                    <input type="text" name="CODE[]" class="digit" maxlength="1" />
                    <input type="text" name="CODE[]" class="digit" maxlength="1" />
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#sign').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false
        });
    });

</script>