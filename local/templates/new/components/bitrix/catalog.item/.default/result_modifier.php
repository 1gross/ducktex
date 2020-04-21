<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$arResult = $arParams['ITEM'];

if (!isset($arResult['DISCOUNT'])) {
    $arDiscounts = CCatalogDiscount::GetDiscountByProduct($arResult['ID'], $USER->GetUserGroupArray(), "N", 1, SITE_ID);
    $arResult['DISCOUNT'] = $arDiscounts ?  current($arDiscounts) : false;
}
if (isset($arResult['ITEM_PRICES'][0])) {
    $arResult['PRICE_ITEM'] = $arResult['ITEM_PRICES'][0];
}

if ($arResult['PREVIEW_PICTURE'] || $arResult['DETAIL_PICTURE']) {
    $arResult['PICTURE'] = $arResult['PREVIEW_PICTURE']['SRC'] ?: $arResult['DETAIL_PICTURE']['SRC'];
} else {
    $arResult['PICTURE'] = '';
}

