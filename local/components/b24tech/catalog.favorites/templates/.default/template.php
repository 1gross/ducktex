<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
    <div class="personal-wrap">
        <div class="personal-favorites">
            <h2><?=$APPLICATION->GetTitle()?></h2>
            <?if ($arResult['ITEMS']) {?>
                <?
                $GLOBALS['arrFilter']=Array("ID" => $arResult['ITEMS']);
                ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.top",
                    "catalog.favorites",
                        array(
                            "COMPONENT_TEMPLATE" => "slider-products",
                            "IBLOCK_TYPE" => "aspro_mshop_catalog",
                            "IBLOCK_ID" => "13",
                            "FILTER_NAME" => "arrFilter",
                            //"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
                            "HIDE_NOT_AVAILABLE" => "N",
                            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                            "ELEMENT_SORT_FIELD" => "sort",
                            "ELEMENT_SORT_ORDER" => "asc",
                            "ELEMENT_SORT_FIELD2" => "id",
                            "ELEMENT_SORT_ORDER2" => "desc",
                            "OFFERS_SORT_FIELD" => "sort",
                            "OFFERS_SORT_ORDER" => "asc",
                            "OFFERS_SORT_FIELD2" => "id",
                            "OFFERS_SORT_ORDER2" => "desc",
                            "ELEMENT_COUNT" => "9",
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
                            "SEF_MODE" => "Y",
                            "SEF_RULE" => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_ID#/",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_FILTER" => "N",
                            "ACTION_VARIABLE" => "action",
                            "PRODUCT_ID_VARIABLE" => "id",
                            "PRICE_CODE" => array(
                                0 => "BASE",
                            ),
                            "USE_PRICE_COUNT" => "N",
                            "SHOW_PRICE_COUNT" => "1",
                            "PRICE_VAT_INCLUDE" => "Y",
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
                            "COMPATIBLE_MODE" => "Y"
                        ),
                    $component
                );?>
                <div class="center clear-all">
                    <button class="btn blue js-init-action" data-action="clear_favorites" data-refresh="true"><?=Loc::getMessage('CLEAR_FAVORITES_BUTTON_TEXT')?></button>
                </div>
            <?} else {?>
                <div class="info-message">
                    <h4><?=Loc::getMessage('NO_FAVORITES')?></h4>
                    <a href="<?=SITE_DIR?>catalog/"><?=Loc::getMessage('LINK_TO_CATALOG')?></a>
                </div>
            <?}?>
        </div>
    </div>