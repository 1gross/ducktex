<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$APPLICATION->RestartBuffer();

$arResponse = [];

foreach ($arResult['ITEMS'] as $k => $arItem) {
    $img = '';
    if ($arItem['PREVIEW_PICTURE']['ID']) {
        $img = CFile::GetPath($arItem['PREVIEW_PICTURE']['ID']);
    } elseif($arItem['DETAIL_PICTURE']['ID']) {
        $img = CFile::GetPath($arItem['DETAIL_PICTURE']['ID']);
    } else {
        $img = '/local/front/files/img/no_image_small.png';
    }

        $arResponse[$k] = [
        'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL'],
        'NAME' => $arItem['NAME'],
        'PRICES' => $arItem['ITEM_PRICES'][0],
        'PICTURE' => $img
    ];
}




echo json_encode($arResponse);
die();