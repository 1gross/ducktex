<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$arSections = array();
if ($arResult['SECTIONS']) {
    foreach ($arResult['SECTIONS'] as $arItem) {
        if ($arItem['DEPTH_LEVEL'] <= 2) {
            if ($arItem['IBLOCK_SECTION_ID'] > 0) {
                $arSections[$arItem['IBLOCK_SECTION_ID']]['SUBSECTIONS'][$arItem['ID']] = $arItem;
            } else {
                $arSections[$arItem['ID']] = $arItem;
            }
        }
    }
    $arResult['SECTIONS'] = $arSections;
}