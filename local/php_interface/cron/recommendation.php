#!/usr/bin/php
<?
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
define("SITE_ID", "s1"); //site_id
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

header("Access-Control-Allow-Origin: *");
CModule::IncludeModule('iblock');

$pathFile = $_SERVER['DOCUMENT_ROOT'] . '/recomendations.csv';

if (file_exists($pathFile)) {
    $arProductXML = [];
    $arListCodeXML = [];

    $arRows = explode(PHP_EOL, file_get_contents($pathFile));

    foreach ($arRows as $row) {
        $arRow = explode(';', $row);
        if (strlen($arRow[1]) > 0) {
            $arProductXML[$arRow[0]]['ITEMS'][$arRow[1]] = $arRow[1];

            $arListCodeXML[$arRow[0]] = '';
            $arListCodeXML[$arRow[1]] = '';
        }
    }

    if (!empty($arListCodeXML)) {
        $el = new CIBlockElement();

        $rsProducts = CIBlockElement::GetList([],
            ['IBLOCK_ID' => 13, 'XML_ID' => array_keys($arListCodeXML)],
            false, false,
            ['ID', 'NAME', 'XML_ID']
        );
        while ($arProduct = $rsProducts->Fetch()) {
            $arProducts[$arProduct['XML_ID']] = $arProduct;
        }

        foreach ($arProductXML as $xmlCode => $arItem) {
            if (isset($arProducts[$xmlCode])) {
                CIBlockElement::SetPropertyValuesEx($arProducts[$xmlCode]['ID'], 13, ['ASSOCIATED' => []]);

                $el->Update($arProducts[$xmlCode]['ID'], [
                    'PROPERTY_VALUES' => [
                        '177' => []
                    ]
                ]);
                $PROP = [];
                foreach ($arItem['ITEMS'] as $code => $item) {
                    $PROP[$arProducts[$code]['ID']] = $arProducts[$code]['ID'];
                }

                CIBlockElement::SetPropertyValuesEx($arProducts[$xmlCode]['ID'], 13, ['ASSOCIATED' => array_keys($PROP)]);

                unset($PROP);
            }
        }

    }
}