<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<div class="personal-wrap">
    <div class="personal-info">
        <h2><?=$APPLICATION->GetTitle()?></h2>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_FULL_NAME_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['FULL_NAME'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_BIRTHDAY_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['PERSONAL_BIRTHDAY'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_PHONE_TITLE')?></span>
            <div class="text"><?=checkPhone($arResult['arUser']['PHONE_NUMBER']) ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_INSTAGRAM_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['UF_INSTAGRAM'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_EMAIL_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['EMAIL'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_ADDRESS_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['FUU'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="buttons-group">
            <button class="btn outline modal-link js-init-action" data-action="show_modal" data-modal="#edit-personal-info"><?=Loc::getMessage('EDIT_BUTTON_TEXT')?></button>
            <button class="btn simple modal-link js-init-action" data-action="show_modal" data-modal="#delete-profile"><?=Loc::getMessage('DELETE_BUTTON_TEXT')?></button>
        </div>
    </div>
</div>
<div style="display: none;">
<div class="modal active" id="edit-personal-info">
    <form action="" method="post" class="modal-block">
        <button class="arcticmodal-close close"></button>
        <div class="modal-title">Личная информация</div>
        <div class="modal-body">
            <form action="">
                <input type="text" value="<?=$arResult['arUser']['FULL_NAME']?>" name="FIO" placeholder="ФИО">
                <input type="text" value="<?=$arResult['arUser']['PERSONAL_BIRTHDAY']?>" name="PERSONAL_BIRTHDAY" placeholder="Дата рождения (дд/мм/г)">
                <input type="tel"  value="<?=checkPhone($arResult['arUser']['PHONE_NUMBER'])?>" name="PHONE_NUMBER" placeholder="Телефон">
                <input type="text" value="<?=$arResult['arUser']['PERSONAL_BIRTHDAY']?>" name="EMAIL" placeholder="Email">

                <input type="text" value="<?=$arResult['arUser']['UF_INSTAGRAM']?>" name="UF_INSTAGRAM" placeholder="Инстаграм">

                <input type="text" value="<?=$arResult['arUser']['PERSONAL_CITY']?>" name="PERSONAL_CITY" placeholder="Город">
                <input type="text" value="<?=$arResult['arUser']['PERSONAL_STREET']?>" name="PERSONAL_STREET" placeholder="Адрес (Улица, дом)">
                <input type="submit" class="btn blue js-init-action" data-action="send_form" data-id="profile_edit" value="сохранить">
            </form>
        </div>
    </form>
</div>

<div class="modal active" id="delete-profile">
    <div class="modal-block">
        <button class="arcticmodal-close close"></button>
        <div class="modal-title">Удаление профиля</div>
        <div class="modal-desc">Пожалуйста, расскажите, почему Вы решили удалить профиль?</div>
        <div class="modal-body">
            <form action="">
                <textarea id="" name="COMMENT" cols="30" rows="10" required=""></textarea>
                <div class="buttons-group"><input type="submit" class="btn outline js-init-action"  data-action="send_form" data-id="profile_delete" value="Продолжить"> <button class="btn blue">отмена</button></div>
            </form>
        </div>
    </div>
</div>
</div>