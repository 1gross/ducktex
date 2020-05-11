<?CJSCore::Init('jquery')?>
<div style="display:none;">
    <div class="modal active" id="sign">
        <form action="/local/tools/ajax.php" class="modal-block">
            <button class="arcticmodal-close close"></button>
            <div class="modal-title">Вход или регистрация</div>
            <div class="modal-desc">Введите Ваш номер телефона</div>
            <div class="modal-body">
                <form action="/">
                    <input type="tel" name="PHONE_NUMBER" placeholder="+7 (___) ___-__-__" required="">
                    <button type="submit" disabled="disabled" class="btn blue js-init-action" data-action="send_form" data-id="auth">Получить код</button>
                    <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c политикой конфиденциальности</div>
                </form>
            </div>
        </form>
    </div>
</div>
<div style="display: none">
    <div class="modal active" id="code">
        <form action="/local/tools/ajax.php" class="modal-block">
            <input type="hidden" name="CODE" value="">
            <button class="arcticmodal-close close"></button>
            <div class="modal-title">Введите код</div>
            <div class="modal-desc bold">Мы отправили код на номер <span class="modal-desc__phone"></span></div>
            <button class="arcticmodal-close back">Изменить</button>
            <div class="modal-body">
                <div class="sms-code">
                    <input type="text" data-id="1" class="digit" maxlength="1" />
                    <input type="text" data-id="2" class="digit" maxlength="1" />
                    <input type="text" data-id="3" class="digit" maxlength="1" />
                    <input type="text" data-id="4" class="digit" maxlength="1" />
                    <input type="text" data-id="5" class="digit" maxlength="1" />
                    <input type="text" data-id="6" class="digit" maxlength="1" />
                </div>

            </div>
            <div class="modal-body__message">
                <div class="modal-message error-info"></div>
                <div class="modal-message modal-body__btn">
                    <button type="submit" class="btn blue btn-accept js-init-action" disabled="disabled" data-action="send_form" data-id="auth_check_code">Подтвердить</button>
                </div>
                <div class="modal-message timer">Запросить новый код можно через <span>0:59</span></div>
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