
<div style="display:none;">
    <div class="modal active" id="<?=$arParams['MODAL_ID'] ?: 'sign'?>">
        <form action="/local/tools/ajax.php" class="modal-block">
            <?if ($arParams['CLOSE_BUTTON'] !== false) {?>
                <button class="arcticmodal-close close"></button>
            <?}?>
            <div class="modal-title">Вход или регистрация</div>
            <div class="modal-desc"><?=$arParams['DESCRIPTION'] ? htmlspecialchars_decode($arParams['DESCRIPTION']) : 'Введите Ваш номер телефона'?></div>
            <div class="modal-body">
                <form action="/">
                    <input type="tel" name="PHONE_NUMBER" placeholder="+_ (___) ___-__-__" required="">
                    <div class="modal-flag__wrap">
                        <div class="modal-flag"></div>
                    </div>
                    <?if ($arParams['MODAL_ID'] == 'sign_basket') {?>
                        <input type="hidden" name="REDIRECT_URL" value="/basket/">
                    <?}?>
                    <button type="submit" disabled="disabled" class="btn blue js-init-action" data-action="send_form" data-modal-type="<?=$arParams['MODAL_ID'] ?: 'sign'?>" data-id="auth">Получить код</button>
                    <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c политикой конфиденциальности</div>
                </form>
                <div class="modal-footer">
                    <a class="btn btn-link btn-link__strong js-init-action" data-id="auth_pass" data-action="show_modal" data-modal="#auth_pass">Войти с помощью пароля</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div style="display: none;">
    <div class="modal active" id="auth_pass">
        <form action="/local/tools/ajax.php" class="modal-block">
            <button class="arcticmodal-close close"></button>
            <div class="modal-title">Войдите с помощью пароля</div>
            <div class="modal-desc">Только для зарегистрированных пользователей</div>
            <div class="modal-error error"></div>
            <div class="modal-body">
                <form action="/">
                    <input type="tel" name="PHONE_NUMBER" class="" placeholder="+_(___)___-____" required="">
                    <div class="modal-flag__wrap">
                        <div class="modal-flag"></div>
                    </div>
                    <input type="password" name="PASS" placeholder="Пароль" required="">
                    <button type="submit" class="btn blue js-init-action" data-action="send_form" data-modal-type="<?=$arParams['MODAL_ID'] ?: 'sign'?>" data-id="auth_pass">Войти</button>
                    <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c политикой конфиденциальности</div>
                </form>
                <div class="modal-footer">
                    <a class="btn btn-link btn-link__strong arcticmodal-close">Вернуться на главный экран</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            //$('[name="dob"]').mask("00/00/0000");
        });
    </script>
</div>
<div style="display: none">
    <div class="modal active" id="code">
        <form action="/local/tools/ajax.php" class="modal-block">
            <input type="hidden" name="CODE" value="">
            <button class="arcticmodal-close close"></button>
            <div class="modal-title">Введите код</div>
            <div class="modal-desc bold">Мы отправили код на номер <span class="modal-desc__phone"></span></div>
            <button class="arcticmodal-close back">Изменить</button>
            <?if ($APPLICATION->GetCurPage() == '/basket/' && !$USER->IsAuthorized()) {?>
                <div class="modal-desc">
                    Это нужно для того чтобы мы корректно отобразили ваши предыдущие заказы и учли баллы на бонусный счет
                </div>
            <?}?>
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
                <div class="modal-message timer">Запросить новый код можно через <span>1:59</span></div>
                <div class="modal-message"><b>В редких случаях SMS может идти до 3х минут, пожалуйста подождите</b></div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-link btn-link__strong js-init-action" data-id="auth_pass" data-action="show_modal" data-modal="#auth_pass">Войти с помощью пароля</a>
            </div>
        </form>

    </div>
</div>
