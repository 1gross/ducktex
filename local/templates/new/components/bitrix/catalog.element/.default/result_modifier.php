<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

if(CModule::IncludeModule("vbcherepanov.bonus")){
    ob_start();
    $APPLICATION->IncludeComponent("vbcherepanov:vbcherepanov.bonuselement",".default",
        Array(
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "N",
            "ELEMENT" => $arResult, //передаем весь результирующий массив в компонент
            "OFFERS_AR" => "OFFERS", //ключ массива $arResult в котором находятся торговые предложения
            "OFFERS_ID" => "OFFER_ID_SELECTED", //ключ массива $arResult с ID выбранного торгового предложения
            "ONLY_NUM" => "N", //возвратит бонус в виде числа без валюты
        )
    );
    $arResult['BONUSEL'] = ob_get_clean(); // сохраняем вывод бонусов в переменную массива
}

$arPhoto = array();
if ($arResult['DETAIL_PICTURE']['SRC']) {
    $arPhoto[] = array(
        'SRC' => $arResult['DETAIL_PICTURE']['SRC'],
        'ALT' => $arResult['DETAIL_PICTURE']['ALT']
    );
}
if (is_array($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
    foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $id) {
        $imgPath = CFile::GetPath($id);
        $img = CFile::GetByID($id)->Fetch();
        $arPhoto[] = array(
            'SRC' => $imgPath,
            'ALT' => $img['DESCRIPTION'] ?: ''
        );
    }
}
$arResult['MORE_PHOTO'] = $arPhoto;