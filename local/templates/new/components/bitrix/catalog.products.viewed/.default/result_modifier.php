<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
if ($arResult['ITEMS']) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        $minPrice = '';
        $lastPrice = '';
        if ($arItem['ITEM_PRICES']) {
            foreach ($arItem['ITEM_PRICES'] as $i => $arPrice) {
                if ($minPrice = '' || $minPrice > $arPrice['PRICE']) {
                    $minPrice = $arPrice['PRICE'];
                }
            }
            $arItem['MIN_PRICE'] = $minPrice;
        }
    }
}