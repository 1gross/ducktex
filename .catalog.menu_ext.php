<?php
global $APPLICATION;
$aMenuLinksExt = $APPLICATION->IncludeComponent(
    "b24tech:menu.sections",
    "",
    array(
        "ID" => $_REQUEST["ELEMENT_ID"],
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => IBLOCK_CATALOG_ID,
        "SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
        "CACHE_TIME" => "3600",
        "IS_SEF" => "N",
        "DEPTH_LEVEL" => "2",
        "CACHE_TYPE" => "A",
        "FILTER_NAME" => "",
    ),
    false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);