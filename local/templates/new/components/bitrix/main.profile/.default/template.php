<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTabs = array();
$db_sales = CSaleOrderUserProps::GetList(array('ID' => 'DESC'), array("USER_ID" => $USER->GetID(), "PERSON_TYPE_ID" => 1));
$db_sales_2 = CSaleOrderUserProps::GetList(array('ID' => 'DESC'), array("USER_ID" => $USER->GetID(), "PERSON_TYPE_ID" => 2));
$arTabs[1] = $db_sales->Fetch();
$arTabs[2] = $db_sales_2->Fetch();

foreach ($arTabs as $typeID => $arProfile) {
    $db_propVals = CSaleOrderUserPropsValue::GetList(array(), Array(
        "USER_PROPS_ID" => $arProfile['ID'],
    ));
    while ($ar_prop = $db_propVals->Fetch()) {
        $arTabs[$typeID]['ITEMS'][$ar_prop['PROP_CODE']] = $ar_prop;
    }
}
?>
<div class="personal-wrap">
    <div class="personal-info tab">
        <h2><?=$APPLICATION->GetTitle()?></h2>
        <div class="tabs">
            <div class="personal-info-item">
                <label class="radio-box js-init-tab" data-id="1">
                    <input type="radio" name="PERSON_TYPE_ID" value="1" checked="checked">
                    <span class="checkmark"></span>
                    Физическое лицо
                </label>
                <label class="radio-box js-init-tab" data-id="2">
                    <input type="radio" name="PERSON_TYPE_ID" value="2">
                    <span class="checkmark"></span>
                    Юридическое лицо
                </label>
            </div>
        </div>
        <?
            $arProps = $arTabs[1]['ITEMS'];
            ?>
            <div class="tabs_content active" data-type-id="1">
                <div class="personal-info-item">
                    <span class="title"><?=Loc::getMessage('PERSONAL_FULL_NAME_TITLE')?></span>
                    <div class="text"><?=isset($arProps['FIO']['VALUE']) && strlen($arProps['FIO']['VALUE']) > 0 ? $arProps['FIO']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title"><?=Loc::getMessage('PERSONAL_BIRTHDAY_TITLE')?></span>
                    <div class="text"><?=$arResult['arUser']['PERSONAL_BIRTHDAY'] ?: Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title"><?=Loc::getMessage('PERSONAL_PHONE_TITLE')?></span>
                    <div class="text"><?=isset($arResult['arUser']['PHONE_NUMBER']) && strlen($arResult['arUser']['PHONE_NUMBER']) > 0 ? $arResult['arUser']['PHONE_NUMBER'] : Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title"><?=Loc::getMessage('PERSONAL_INSTAGRAM_TITLE')?></span>
                    <div class="text"><?=$arResult['arUser']['UF_INSTAGRAM'] ?: Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title">Вконтакте</span>
                    <div class="text"><?=$arResult['arUser']['UF_VK'] ?: Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title">Facebook</span>
                    <div class="text"><?=$arResult['arUser']['UF_FB'] ?: Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title"><?=Loc::getMessage('PERSONAL_EMAIL_TITLE')?></span>
                    <div class="text"><?=isset($arProps['EMAIL']['VALUE']) && strlen($arProps['EMAIL']['VALUE']) > 0 ? $arProps['EMAIL']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
                </div>
                <div class="personal-info-item">
                    <span class="title"><?=Loc::getMessage('PERSONAL_ADDRESS_TITLE')?></span>
                    <div class="text"><?=isset($arProps['ADDRESS']['VALUE']) && strlen($arProps['ADDRESS']['VALUE']) > 0 ? $arProps['ADDRESS']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
                </div>
            </div>
        <?
        unset($arProps);
        $arProps = $arTabs[2]['ITEMS'];
        ?>
        <div class="tabs_content " data-type-id="2">
            <div class="personal-info-item">
                <span class="title">ИНН</span>
                <div class="text"><?=isset($arProps['INN']['VALUE']) && strlen($arProps['INN']['VALUE']) > 0 ? $arProps['INN']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
            </div>
            <div class="personal-info-item">
                <span class="title">КПП</span>
                <div class="text"><?=isset($arProps['KPP']['VALUE']) && strlen($arProps['KPP']['VALUE']) > 0 ? $arProps['KPP']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
            </div>
            <div class="personal-info-item">
                <span class="title">Контактное лицо</span>
                <div class="text"><?=isset($arProps['CONTACT_PERSON']['VALUE']) && strlen($arProps['CONTACT_PERSON']['VALUE']) > 0 ? $arProps['CONTACT_PERSON']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
            </div>
            <div class="personal-info-item">
                <span class="title"><?=Loc::getMessage('PERSONAL_EMAIL_TITLE')?></span>
                <div class="text"><?=isset($arProps['EMAIL']['VALUE']) && strlen($arProps['EMAIL']['VALUE']) > 0 ? $arProps['EMAIL']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
            </div>
            <div class="personal-info-item">
                <span class="title"><?=Loc::getMessage('PERSONAL_PHONE_TITLE')?></span>
                <div class="text"><?=isset($arResult['arUser']['PHONE_NUMBER']) && strlen($arResult['arUser']['PHONE_NUMBER']) > 0 ? $arResult['arUser']['PHONE_NUMBER'] : Loc::getMessage('NOT_VALUE')?></div>
            </div>
            <div class="personal-info-item">
                <span class="title">Адрес</span>
                <div class="text"><?=isset($arProps['ADDRESS']['VALUE']) && strlen($arProps['ADDRESS']['VALUE']) > 0 ? $arProps['ADDRESS']['VALUE'] : Loc::getMessage('NOT_VALUE')?></div>
            </div>
        </div>
        <div class="buttons-group">
            <button class="btn outline modal-link js-init-action" data-action="show_modal" data-modal="#edit-personal-info"><?=Loc::getMessage('EDIT_BUTTON_TEXT')?></button>
            <button class="btn simple modal-link js-init-action" style="display:none;" data-action="show_modal" data-modal="#delete-profile"><?=Loc::getMessage('DELETE_BUTTON_TEXT')?></button>
        </div>
    </div>
</div>
<div style="display: none;">
<div class="modal active" id="edit-personal-info">
    <div class="modal-block">
        <button class="arcticmodal-close close"></button>
        <div class="modal-title">Личная информация</div>
        <div class="modal-body tab">
            <div class="personal-info-item">
                <label class="radio-box js-init-tab" data-id="1">
                    <input type="radio" name="PERSON_TYPE" value="1" checked="checked">
                    <span class="checkmark"></span>
                    Физическое лицо
                </label>
                <label class="radio-box js-init-tab" data-id="2">
                    <input type="radio" name="PERSON_TYPE" value="2">
                    <span class="checkmark"></span>
                    Юридическое лицо
                </label>
            </div>
            <?
            $arProps = $arTabs[1]['ITEMS'];
            ?>
                <form action="" class="tabs_content active" data-type-id="1">
                    <input type="hidden" name="PERSON_TYPE_ID" value="1">
                    <input type="hidden" name="OLD_PHONE" value="<?=isset($arProps['PHONE']) ? $arProps['PHONE']['VALUE'] : ''?>">

                    <input type="text"
                           value="<?=isset($arProps['FIO']) ? $arProps['FIO']['VALUE'] : ''?>"
                           name="FIO"
                           placeholder="ФИО">

                    <input class="mask-date"
						   type="text"
                           value="<?=$arResult['arUser']['PERSONAL_BIRTHDAY'] ?: ''?>"
                           name="PERSONAL_BIRTHDAY"
                           placeholder="Дата рождения (дд.мм.г)">

                    <input type="tel"
                           value="<?=isset($arProps['PHONE']) ? $arProps['PHONE']['VALUE'] : ''?>"
                           name="PHONE"
                           placeholder="Телефон">

                    <input type="text"
                           value="<?=isset($arProps['EMAIL']) ? $arProps['EMAIL']['VALUE'] : ''?>"
                           name="EMAIL"
                           placeholder="Email">

                    <input type="text"
                           value="<?=$arResult['arUser']['UF_INSTAGRAM'] ?: ''?>"
                           name="UF_INSTAGRAM"
                           placeholder="Инстаграм">

                    <input type="text"
                           value="<?=$arResult['arUser']['UF_VK'] ?: ''?>"
                           name="UF_VK"
                           placeholder="Вконтакте">

                    <input type="text"
                           value="<?=$arResult['arUser']['UF_FB'] ?: ''?>"
                           name="UF_FB"
                           placeholder="Facebook">

                    <input type="text"
                           value="<?=isset($arProps['ADDRESS']) ? $arProps['ADDRESS']['VALUE'] : ''?>"
                           name="ADDRESS"
                           placeholder="Предпочтительный адрес доставки">

                    <input type="submit" class="btn blue js-init-action" data-action="send_form" data-id="profile_edit" value="сохранить">
                </form>
            <?
            unset($arProps);
            $arProps = $arTabs[2]['ITEMS'];
            ?>
            <form action="" class="tabs_content" data-type-id="2">
                    <input type="hidden" name="PERSON_TYPE_ID" value="2">
                    <input type="hidden" name="OLD_PHONE" value="<?=isset($arProps['PHONE']) ? $arProps['PHONE']['VALUE'] : ''?>">

                    <input type="text"
                           value="<?=isset($arProps['INN']) ? $arProps['INN']['VALUE'] : ''?>"
                           name="INN"
                           placeholder="ИНН">

                    <input type="text"
                           value="<?=isset($arProps['KPP']) ? $arProps['KPP']['VALUE'] : ''?>"
                           name="KPP"
                           placeholder="КПП">

                    <input type="tel"
                           value="<?=isset($arProps['PHONE']) ? $arProps['PHONE']['VALUE'] : ''?>"
                           name="PHONE"
                           placeholder="Телефон">

                    <input type="text"
                           value="<?=isset($arProps['EMAIL']) ? $arProps['EMAIL']['VALUE'] : ''?>"
                           name="EMAIL"
                           placeholder="Email">

                    <input type="text"
                           value="<?=isset($arProps['CONTACT_PERSON']) ? $arProps['CONTACT_PERSON']['VALUE'] : ''?>"
                           name="CONTACT_PERSON"
                           placeholder="Инстаграм">

                    <input type="text"
                           value="<?=isset($arProps['ADDRESS']) ? $arProps['ADDRESS']['VALUE'] : ''?>"
                           name="ADDRESS"
                           placeholder="Адрес">


                    <input type="submit" class="btn blue js-init-action" data-action="send_form" data-id="profile_edit" value="сохранить">
                </form>


        </div>
    </div>
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