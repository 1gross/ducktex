<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
if ($arResult['ITEMS']) {
    foreach ($arResult['ITEMS'] as $id => $arItem) {
        if ($arItem['PREVIEW_PICTURE'] || $arItem['DETAIL_PICTURE']) {
            $arItem['PICTURE'] = $arItem['PREVIEW_PICTURE']['SRC'] ?: $arItem['DETAIL_PICTURE']['SRC'];
        } else {
            $arItem['PICTURE'] = '';
        }
        $arResult['ITEMS'][$id]['PICTURE'] = $arItem['PICTURE'];
    }
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