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
            <div class="text"><?=$arResult['arUser']['FULL_NAME']?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_BIRTHDAY_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['PERSONAL_BIRTHDAY'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_PHONE_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['PERSONAL_PHONE'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_INSTAGRAM_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['UF_INSTAGRAM'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_EMAIL_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['EMAIL']?></div>
        </div>
        <div class="personal-info-item">
            <span class="title"><?=Loc::getMessage('PERSONAL_ADDRESS_TITLE')?></span>
            <div class="text"><?=$arResult['arUser']['UF_ADDRESS'] ?: Loc::getMessage('NOT_VALUE')?></div>
        </div>
        <div class="buttons-group">
            <button class="btn outline modal-link" data-modal="#edit-personal-info"><?=Loc::getMessage('EDIT_BUTTON_TEXT')?></button>
            <button class="btn simple modal-link" data-modal="#delete-profile"><?=Loc::getMessage('DELETE_BUTTON_TEXT')?></button>
        </div>
    </div>
</div>
