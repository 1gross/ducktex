<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
if ($arResult['VARIABLES']['SECTION_ID']) {
    $arSection = CIBlockSection::GetByID($arResult['VARIABLES']['SECTION_ID'])->Fetch();
    $APPLICATION->SetTitle($arSection['NAME']);
}