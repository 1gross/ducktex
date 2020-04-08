<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$countItems = $APPLICATION->GetViewContent('items');
?>
<div class="personal-wrap personal-history">
    <h2><?=$APPLICATION->GetTitle()?></h2>
    <?if ($arResult["ITEMS"]) {?>
    <div class="history-count">
        <?=Loc::getMessage('HEAD_TITLE_ALL')?> <span><?=$countItems?></span> <?=NumPluralForm($countItems, array('операция','операции','операций'))?>
    </div>
    <table class="table-history">
        <thead>
        <tr>
            <td><?=Loc::getMessage('HEAD_TABLE_FIELD_SUM')?></td>
            <td><?=Loc::getMessage('HEAD_TABLE_FIELD_DATE')?></td>
            <td><?=Loc::getMessage('HEAD_TABLE_FIELD_OPERATION')?></td>
            <td><?=Loc::getMessage('HEAD_TABLE_FIELD_ORDER')?></td>
        </tr>
        </thead>

        <tbody>
        <?foreach($arResult["ITEMS"] as $arItem) {
            $paidBonus = $arItem['PROPS']['BALLANCE_AFTER'] < $arItem['PROPS']['BALLANCE_BEFORE'] ? true : false; ?>
        <tr>
            <td <?=$paidBonus == false ? '' : 'class="minus"'?>>
                <?=$paidBonus == false ? '+' : '-' ?><?=$arItem['PROPS']['OPERATION_SUM']['VALUE']?>
            </td>
            <td><?=strtolower(FormatDate("d F Y в H:i", MakeTimeStamp($arItem['PROPS']['DATE_CREATE'])))?></td>
            <td><?=$arItem['PROPS']['OPERATION_TYPE']['VALUE']?></td>
            <td><?=$arItem['PROPS']['ORDER_ID']['VALUE']?></td>
        </tr>
        <?}?>
        </tbody>
    </table>
    <?=$arResult['NAV_STRING']?>
    <?} else {?>
        <div class="info-message">
            <h4><?=Loc::getMessage('NO_OPERATIONS')?></h4>
        </div>
    <?}?>
</div>