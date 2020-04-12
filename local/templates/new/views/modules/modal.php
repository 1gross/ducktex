<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs('/local/front/files/js/jquery.mask.js')
?>

<div class="modal" id="edit-personal-info">
    <div class="modal-block">
        <button class="close"></button>
        <div class="modal-title">Личная информация</div>
        <div class="modal-body">
            <form action="">
                <input type="text" placeholder="ФИО">
                <input type="text" name="dob" placeholder="Дата рождения (дд/мм/г)">
                <input type="tel" placeholder="Телефон">
                <input type="text" placeholder="Instagram">
                <input type="text" placeholder="Email">
                <input type="submit" class="btn blue" value="сохранить">
            </form>
        </div>
    </div>
</div>

<div class="modal" id="delete-profile">
    <div class="modal-block">
        <button class="close"></button>
        <div class="modal-title">Удаление профиля</div>
        <div class="modal-desc">Пожалуйста, расскажите, почему Вы решили удалить профиль?</div>
        <div class="modal-body">
            <form action="">
                <textarea id="" cols="30" rows="10" required=""></textarea>
                <div class="buttons-group"><input type="submit" class="btn outline" value="Продолжить"> <button class="btn blue">отмена</button></div>
            </form>
        </div>
    </div>
</div>
