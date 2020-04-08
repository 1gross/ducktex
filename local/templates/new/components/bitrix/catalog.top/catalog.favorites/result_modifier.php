<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
foreach ($arResult['ITEMS'] as $k => $arItem) {
    $img = '';
    if ($arItem['PREVIEW_PICTURE'] || $arItem['DETAIL_PICTURE']) {
        $img = $arItem['PREVIEW_PICTURE'] ?: $arItem['DETAIL_PICTURE'];

    } else {
        $img = array(
            'SRC' => '/local/front/files/img/product-bg.jpg',
            'ALT' => 'Изображение отсутствует'
        );
    }
    $arResult['ITEMS'][$k]['PICTURE'] = $img;
}