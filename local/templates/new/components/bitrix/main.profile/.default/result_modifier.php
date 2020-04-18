<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

$arName = array();

if ($arResult['arUser']['LAST_NAME']) {
    $arName[] = $arResult['arUser']['LAST_NAME'];
}
if ($arResult['arUser']['NAME']) {
    $arName[] = $arResult['arUser']['NAME'];
}
if ($arResult['arUser']['SECOND_NAME']) {
    $arName[] = $arResult['arUser']['SECOND_NAME'];
}
$arResult['arUser']['FULL_NAME'] = trim(implode(' ', $arName));