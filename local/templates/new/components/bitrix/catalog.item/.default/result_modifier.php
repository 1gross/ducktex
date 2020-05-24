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
    $arResult['PICTURE'] = $arResult['DETAIL_PICTURE']['SRC'] ?: $arResult['PREVIEW_PICTURE']['SRC'];
} else {
    $arResult['PICTURE'] = '';
}
if (is_array($arParams['PROPERTY_CODE']) && count($arParams['PROPERTY_CODE']) > 0) {
    $arResult['SHOW_PROPERTIES'] = $arParams['PROPERTY_CODE'];
} else {
    $arResult['SHOW_PROPERTIES'] = array(
        "CML2_ARTICLE",
        "COLOR_REF2",
        "WIDTH",
        "DENSITY",
        "COMPOSITION",
    );
}
