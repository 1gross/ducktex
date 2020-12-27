<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

if ($arResult['ORDERS']) {
    foreach ($arResult['ORDERS'] as $key => $arOrder) {
        foreach ($arOrder['BASKET_ITEMS'] as $k => $arItem) {
            $ar = CIBlockElement::GetByID($arItem['PRODUCT_ID'])->Fetch();
            if ($ar['PREVIEW_PICTURE'] || $ar['DETAIL_PICTURE']) {
                $imgID = $ar['PREVIEW_PICTURE'] ?: $ar['DETAIL_PICTURE'];
                $img = CFile::GetPath($imgID);
            } else {
                $img = '/local/front/files/img/product-bg.jpg';
            }
            $arResult['ORDERS'][$key]['BASKET_ITEMS'][$k]['PICTURE'] = $img;
        }
    }
}