<div class="modal" id="sign">
    <form action="/local/tools/ajax.php" class="modal-block">
        <button class="close"></button>
        <div class="modal-title">Вход или регистрация</div>
        <div class="modal-desc">Введите Ваш номер телефона</div>
        <div class="modal-body">
            <form action="">
                <input type="tel" name="data[PHONE]" placeholder="+7 (___) ___-__-__" required="">
                <input type="submit" class="btn blue" value="Получить код">
                <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c политикой конфиденциальности</div>
            </form>
        </div>
    </form>
</div>
<div class="modal" id="code">
    <form action="/local/tools/ajax.php" class="modal-block">
        <input type="hidden" name="data[SIGN_DATA]" value="">
        <button class="close"></button>
        <div class="modal-title">Введите код</div>
        <div class="modal-desc bold">Мы отправили код на номер +7 (000) 000-00-00</div>
        <button class="back">Изменить</button>
        <div class="modal-body">
            <div class="sms-code">
                <input type="text" name="data[CODE][]" class="digit" maxlength="1" />
                <input type="text" name="data[CODE][]" class="digit" maxlength="1" />
                <input type="text" name="data[CODE][]" class="digit" maxlength="1" />
                <input type="text" name="data[CODE][]" class="digit" maxlength="1" />
                <input type="text" name="data[CODE][]" class="digit" maxlength="1" />
                <input type="text" name="data[CODE][]" class="digit" maxlength="1" />
            </div>
        </div>
    </form>
</div>