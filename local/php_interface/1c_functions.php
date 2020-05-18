<?
use Bitrix\Main\Loader,
    Bitrix\Catalog\StoreTable,
    Sepro\ElementTable;

Loader::includeModule('sepro.helper');

function isFurniture($sectionId)
{
    $sectionId = intval($sectionId);
    $rootSection = 0;
    if ($sectionId > 0){
        $nav = CIBlockSection::GetNavChain(false, $sectionId);
        if($arSectionPath = $nav->GetNext()){
            $rootSection = $arSectionPath['ID'];
        }
    }
    if ($rootSection == 401){
        return true;
    }
    return false;
}

AddEventHandler('ipol.sdek', 'onBeforeDimensionsCount', 'handleGoods');

function handleGoods(&$arOrderGoods){

    if(!CModule::includeModule('iblock')) return;

    foreach($arOrderGoods as $key => $arGood)
    {
        $elt = CIBlockElement::GetList(array(),array('ID'=>$arGood['PRODUCT_ID']),false,false,array('ID','IBLOCK_SECTION_ID'))->Fetch();
        if (isFurniture($elt['IBLOCK_SECTION_ID'])){
            unset($arOrderGoods[$key]);
        }
    }
}

if (!function_exists('d'))
{
    function d($o, $all = false, $die = false )
    {
        global $USER;

        if ( !$USER->IsAdmin() and !$all)
            return;

        $bt         = debug_backtrace();
        $bt         = $bt[0];
        $dRoot      = $_SERVER["DOCUMENT_ROOT"];
        $dRoot      = str_replace("/","\\",$dRoot);
        $bt["file"] = str_replace($dRoot,"",$bt["file"]);
        $dRoot      = str_replace("\\","/",$dRoot);
        $bt["file"] = str_replace($dRoot,"",$bt["file"]);
        ?>
        <div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;text-align: left!important;'>
            <div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?> [<?=$bt["line"]?>]</div>
            <pre style='padding:5px;'><?print_r($o)?></pre>
        </div>
        <?
    }
}


AddEventHandler("catalog", "OnSuccessCatalogImport1C", "OnSuccessCatalogImport1CHandler");
function OnSuccessCatalogImport1CHandler()
{
    CModule::IncludeModule("iblock");
    CModule::IncludeModule("catalog");
    $arSelect = Array('ID', 'IBLOCK_ID', 'PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA');
    $arFilter = Array('IBLOCK_ID' => 13);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        if ($arFields['PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA_VALUE'] != "")
        {
            $newProductRatio = (float)str_replace(',', '.', $arFields['PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA_VALUE']);
            if ($newProductRatio > 0)
            {
                $dbRatio = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => $arFields["ID"]), false, false, array());
                if($arRatio = $dbRatio->Fetch())
                {
                    if ($newProductRatio != $arRatio['RATIO'])
                    {
                        CCatalogMeasureRatio::update(
                            $arRatio["ID"],
                            array(
                                "PRODUCT_ID" => $arFields["ID"],
                                "RATIO" => $newProductRatio
                            )
                        );
                    }
                }
                else
                {
                    CCatalogMeasureRatio::add(
                        array(
                            "PRODUCT_ID" => $arFields["ID"],
                            "RATIO" => $newProductRatio
                        )
                    );
                }
            }
        }
    }
}

AddEventHandler("catalog", "OnGetOptimalPrice", "MyGetOptimalPrice");
function MyGetOptimalPrice($intProductID, $quantity, $arUserGroups = array(), $renewal = "N", $arPrices = array(), $siteID = false, $arDiscountCoupons = false) {
    //if ($GLOBALS['USER']->GetID() <= 0) return false;
    CModule::IncludeModule('sale');
    CModule::IncludeModule('catalog');
    $arOptPrices = CCatalogProduct::GetByIDEx($intProductID);
    $price = $arOptPrices['PRICES'][1]['PRICE'];
    //if (!$price) return false;
    $price_type_id = 1;
    if ($quantity >= 5 && $quantity < 10) {
        if (isset($arOptPrices['PRICES'][5])) {
            $price = $arOptPrices['PRICES'][5]['PRICE'];
            $price_type_id = 5;
        }
    } elseif ($quantity >= 10) {
        if (isset($arOptPrices['PRICES'][4])) {
            $price = $arOptPrices['PRICES'][4]['PRICE'];
            $price_type_id = 4;
        }
    }
    $arPrices = array(
        "PRODUCT_ID" => $intProductID,
        "RESULT_PRICE" => array(
            "PRICE_TYPE_ID" => $price_type_id,
            "BASE_PRICE" => $price,
            "DISCOUNT_PRICE" => $price,
            "CURRENCY" => "RUB",
            "DISCOUNT" => 0,
            "PERCENT" => 0,
            "VAT_RATE" => 0,
            "VAT_INCLUDED" => "Y"
        ),
        "DISCOUNT_PRICE" => $price,
        "DISCOUNT_LIST" => array()
    );
    return $arPrices;
}


if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php")) {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php");
}
?>