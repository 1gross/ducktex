<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Loader,
    Bitrix\Sale;

Loader::includeModule('sale');
Loader::includeModule('iblock');

 foreach ($arResult['ORDERS'] as $k => $arOrder) {
     foreach ($arOrder['BASKET_ITEMS'] as $key => $item) {
         $arProduct = CIBlockElement::GetByID($item['PRODUCT_ID'])->Fetch();
         $img = '';
         if (isset($arProduct['PREVIEW_PICTURE']) && !empty($arProduct['PREVIEW_PICTURE'])) {
             $img = CFile::GetPath($arProduct['PREVIEW_PICTURE']);
         } elseif (isset($arProduct['DETAIL_PICTURE']) && !empty($arProduct['DETAIL_PICTURE'])) {
             $img = CFile::GetPath($arProduct['PREVIEW_PICTURE']);
         } else {
             $img = '/local/front/files/img/simple.png';
         }
         $arResult['ORDERS'][$k]['BASKET_ITEMS'][$key]['PICTURE'] = $img;
     }

     $order = Sale\Order::loadByAccountNumber($arOrder['ORDER']['ID']);
     $propertyCollection = $order->getPropertyCollection();
     $arResult['ORDERS'][$k]['PROPS'] = $propertyCollection->getArray();

 }