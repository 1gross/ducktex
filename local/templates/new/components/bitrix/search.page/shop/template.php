<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?//dump($arResult)?>
<div class="page search-page">
    <div class="wrapper">
        <h1><?=$APPLICATION->GetTitle(false)?></h1>
        <form action="<?=$APPLICATION->GetCurPage()?>" class="search-input">
            <input type="text" name="q" value="<?=$_REQUEST['q'] ?: ''?>" placeholder="Поиск по сайту">
            <input type="submit" class="btn blue small" value="Найти">
        </form>
        <div class="search-block">
            <?//if ($arResult['SEARCH']) {
                $arProducts = array();
                foreach ($arResult['SEARCH'] as $arItem) {
                    $arProducts[$arItem['ITEM_ID']] = $arItem['ITEM_ID'];
                }
                ?>

                <?
                $APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"search", 
	array(
		"FILTER_NAME" => "arrSearchFilter",
		"IBLOCK_ID" => "13",
		"COMPONENT_TEMPLATE" => "search",
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		//"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"ELEMENT_COUNT" => "20",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"USE_FILTER" => 'Y',
		"SEF_MODE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/personal/basket.php",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
		),
		"DISPLAY_COMPARE" => "N",
		"COMPATIBLE_MODE" => "Y",
        "PRICE_CODE" => array(
            0 => "BASE",
            1 => "10M",
            2 => "5M",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRICE_VAT_SHOW_VALUE" => "N",
	),
	false
);?>
        </div>
    </div>
</div>
