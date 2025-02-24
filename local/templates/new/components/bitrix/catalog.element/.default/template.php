<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($_REQUEST['ajax_mode'] == 'Y') {
    $APPLICATION->RestartBuffer();
}
?>
<section id="card">
    <div class="wrapper">
        <div class="card-block">
            <div class="card-header">
                <h1><?=$arResult['NAME']?></h1>
                <div class="card-buttons">
                   <!-- <button data-action="add_compare" data-id="<?=$arResult['ID']?>" class="compare js-init-action"></button>-->
                    <?if ($USER->IsAuthorized()) {?>
                        <button data-action="add_favorites"
                                data-id="<?=$arResult['ID']?>"
                                class="favorites like js-init-action <?=$arResult['IS_FAVORITES'] ? 'active' : ''?>"></button>
                    <?}?>
                </div>
            </div>
            <div class="card-body">
                <div class="slider-box">
                    <?if (is_array($arResult['MORE_PHOTO']) && count($arResult['MORE_PHOTO']) > 0) {?>
                        <div class="slider-big">
                            <?foreach ($arResult['MORE_PHOTO'] as $arItem) {?>
                                <a data-fancybox="gallery" class="js-init-image_open" href="<?=$arItem['SRC']?>">
                                    <img src="<?=$arItem['SRC']?>"
                                         alt="<?=$arItem['ALT']?>">
                                </a>
                            <?}?>
                        </div>
                        <div class="slider-thumb">
                            <?foreach ($arResult['MORE_PHOTO'] as $arItem) {?>
                                <div>
                                    <img src="<?=$arItem['SRC']?>" alt="<?=$arItem['ALT']?>">
                                </div>
                            <?}?>
                        </div>
                    <?}?>
                    <div class="address">
                    <?
                    global $arrFilter;
                    $arrFilter = array('PROPERTY_DEFAULT_VALUE' => 'Да');
                    $APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "product_contact",
                        array(
                            "COMPONENT_TEMPLATE" => "product_contact",
                            "IBLOCK_TYPE" => "aspro_mshop_content",
                            "IBLOCK_ID" => "4",
                            "NEWS_COUNT" => "1",
                            "SORT_BY1" => "ACTIVE_FROM",
                            "SORT_ORDER1" => "DESC",
                            "SORT_BY2" => "SORT",
                            "SORT_ORDER2" => "ASC",
                            "FILTER_NAME" => "arrFilter",
                            "FIELD_CODE" => array(
                                0 => "",
                                1 => "",
                            ),
                            "PROPERTY_CODE" => array(
                                0 => "EMAIL",
                                1 => "ADDRESS",
                                2 => "DEFAULT",
                                3 => "SCHEDULE",
                                4 => "PHONE",
                                5 => "",
                            ),
                            "CHECK_DATES" => "Y",
                            "DETAIL_URL" => "",
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "N",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "Y",
                            "PREVIEW_TRUNCATE_LEN" => "",
                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "SET_TITLE" => "N",
                            "SET_BROWSER_TITLE" => "N",
                            "SET_META_KEYWORDS" => "N",
                            "SET_META_DESCRIPTION" => "N",
                            "SET_LAST_MODIFIED" => "N",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "PARENT_SECTION" => "",
                            "PARENT_SECTION_CODE" => "",
                            "INCLUDE_SUBSECTIONS" => "N",
                            "STRICT_SECTION_CHECK" => "N",
                            "PAGER_TEMPLATE" => ".default",
                            "DISPLAY_TOP_PAGER" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "N",
                            "PAGER_TITLE" => "Новости",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_DESC_NUMBERING" => "N",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_SHOW_ALL" => "N",
                            "PAGER_BASE_LINK_ENABLE" => "N",
                            "SET_STATUS_404" => "N",
                            "SHOW_404" => "N",
                            "MESSAGE_404" => ""
                        ),
                        $component
                    );
                    ?>
                    </div>
                </div>
                <div class="data">
                    <?if (strlen($arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']) > 0) {?>
                        <div class="product-number">
                            <?=Loc::getMessage('ARTNUMBER_NAME')?>: <span><?=$arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']?></span>
                        </div>
                    <?}?>
                    <h1 class="title-mob"><?=$arResult['NAME']?></h1>
                    <div class="price-block">
                        <div class="price">
							<div class="price-item">
                                <?=$arResult['PRICE_MATRIX']['MATRIX'][5]['ZERO-INF']['PRICE'] || $arResult['PRICE_MATRIX']['MATRIX'][4]['ZERO-INF']['PRICE'] ? 'до 4.9м - ' : ''?>
                                <?=$arResult['PRICE_MATRIX']['MATRIX'][1]['ZERO-INF']['PRICE'];?> руб / м
                            </div>
                            <?if ($arResult['PRICE_MATRIX']['MATRIX'][5]['ZERO-INF']['PRICE']) {?>
                                <div class="price-item">
                                   от 5м - <?=$arResult['PRICE_MATRIX']['MATRIX'][5]['ZERO-INF']['PRICE'];?> руб / м
                                </div>
                            <?}?>
                            <?if ($arResult['PRICE_MATRIX']['MATRIX'][4]['ZERO-INF']['PRICE']) {?>
                                <div class="price-item">
                                    от 10м - <?=$arResult['PRICE_MATRIX']['MATRIX'][4]['ZERO-INF']['PRICE'];?> руб / м
                                </div>
                            <?}?>
							<?/*if (count($arResult['ITEM_QUANTITY_RANGES']) > 1) {?>
                                <?foreach ($arResult['ITEM_PRICES'] as $CODE => $arPrice) {?>
                                    <div class="price-item"
                                         data-price-value="<?=$arPrice['PRICE']?>"
                                         data-quantity-to="<?=$arPrice['QUANTITY_TO'] ?: 'false'?>"
                                         data-quantity-from="<?=$arPrice['QUANTITY_FROM'] ?: 'false'?>">
                                        <?=$arPrice['QUANTITY_FROM'] == null ? 'до ' : 'от '?>
                                        <?=$arPrice['QUANTITY_FROM'] ?: $arPrice['QUANTITY_TO']?>
                                        <?=$arResult['ITEM_MEASURE']['TITLE'] ? $arResult['ITEM_MEASURE']['TITLE'] . '.' : ''?>
                                        <?=' - ' . $arPrice['PRINT_PRICE']?>
                                        <?=$arResult['ITEM_MEASURE']['TITLE'] ? ' / ' . $arResult['ITEM_MEASURE']['TITLE'] : ''?>
                                    </div>
                                <?}?>
                            <?} else {?>
                                <?$arPrice = array_shift($arResult['ITEM_PRICES'])?>
                                <div class="price-item"
                                     data-price-value="<?=$arPrice['PRICE']?>">
                                    <?=$arPrice['PRINT_PRICE']?>
                                    <?if ($arPrice['PRINT_PRICE']) {?>
                                        <?=$arResult['ITEM_MEASURE']['TITLE'] ? ' / ' . $arResult['ITEM_MEASURE']['TITLE'] : ''?>
                                        <?if ($arResult['PRICES']['BASE']['DISCOUNT_DIFF'] > 0) {?>
                                            <span class="old-price"><?=$arResult['PRICES']['BASE']['PRINT_VALUE_VAT']?> / <?=$arResult['ITEM_MEASURE']['TITLE']?></span>
                                        <?}?>
                                    <?} else {?>
                                        Цена отсутствует
                                    <?}?>
                                </div>
							<?}*/?>
                        </div>
                        <div class="quantity-block" data-page="product">
                            <?$measureID = $arResult['ITEM_MEASURE_RATIO_SELECTED']?>
                            <button class="quant-btn quantity-arrow-minus js-init-action" data-action="update_basket" data-type="de" data-id="<?=$arResult['ID']?>"> - </button>
                            <input class="quantity-num"
                                   id="quantity-c"
                                   data-value="<?=$arResult['ITEM_MEASURE_RATIOS'][$measureID]['RATIO']?>"
                                   data-min="<?=$arResult['ITEM_MEASURE_RATIOS'][$measureID]['RATIO']?>"
                                   data-max="<?=$arResult['PRODUCT']['QUANTITY']?>"
                                   data-step="<?=$arResult['ITEM_MEASURE_RATIOS'][$measureID]['RATIO']?>"
                                   data-unit="<?=$arResult['ITEM_MEASURE']['TITLE']?>"
                                   type="text" value="<?=$arResult['ITEM_MEASURE_RATIOS'][$measureID]['RATIO']?> <?=$arResult['ITEM_MEASURE']['TITLE']?>" />
                            <button class="quant-btn quantity-arrow-plus js-init-action" data-action="update_basket" data-type="in" data-id="<?=$arResult['ID']?>"> + </button>
                        </div>
                    </div>
                    <? $APPLICATION->IncludeComponent(
                        "logictim:bonus.catalog",
                        ".default",
                        Array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "AJAX" => "Y",
                            "ITEMS" => array("ITEMS"=>$arResult)
                        )
                    );?>

                    <div class="bonus" id="lb_ajax_<?=$arResult["ID"]?>"></div>
                    <button class="btn blue add-cart js-init-action" data-action="add_basket" data-id="<?=$arResult['ID']?>"><?=$arResult['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('ADD_TO_CARD')?></button>
                    <?if ($arParams['PROPERTY_CODE']) {?>
                        <div class="props">
                            <?foreach ($arParams['PROPERTY_CODE'] as $CODE) {

                                $arProperty = $arResult['PROPERTIES'][$CODE];

                                if (!empty($arProperty['VALUE'])) {
                                    switch ($arProperty['PROPERTY_TYPE']) {
                                        case 'E':
                                            $arEl = CIBlockElement::GetByID($arProperty['VALUE'])->Fetch();
                                            if ($arEl['NAME']) {
                                                $arProperty['VALUE'] = $arEl['NAME'];
                                            }
                                            break;
                                        case 'S':
                                            if ($arProperty['USER_TYPE']) {
                                                $vl = GetPropertyForHlBlock($arProperty['USER_TYPE_SETTINGS']['TABLE_NAME'], $arProperty['VALUE']);
                                                if ($vl['UF_NAME']) {
                                                    $arProperty['VALUE'] = $vl['UF_NAME'];
                                                } else {
                                                    $arProperty['VALUE'] = '';
                                                }
                                            } else {
                                                if (is_array($arProperty['VALUE'])) {
                                                    $arProperty['VALUE'] = implode(', ', $arProperty['VALUE']);
                                                }
                                            }
                                            break;
                                        case 'L':
                                            switch ($arProperty['VALUE']) {
                                                case 'Y':
                                                    $arProperty['VALUE'] = 'Есть';
                                                    break;
                                                case 'N':
                                                    $arProperty['VALUE'] = 'Нет';
                                                    break;
                                            }
                                            break;
                                    }
                                    if ($arProperty['VALUE']) {?>
                                        <div class="props-item">
                                            <div class="name"><?=$arProperty['NAME']?>:</div>
                                            <div class="value"><?=is_array($arProperty['VALUE']) ? implode(', ', $arProperty['VALUE']) : $arProperty['VALUE']?></div>
                                        </div>
                                    <?} ?>
                                <?}?>
                            <?}?>
                        </div>
                    <?}?>

                    <!-- цветопередача -->
                    <div class="alert_color_view">
                        <div class="alert_text">Обращаем Ваше внимание! Цветопередача на разных устройствах может отличаться и не совпадать с реальным оттенком.</div>
                    </div>
                    <!-- цветопередача -->


                    <div class="pluses desktop">
                        <div class="pluses-block">
                            <div class="item">
                                <img src="/local/front/files/img/quality-icon.svg" alt="">
                                <div class="text">
                                    Ткани <br>высокого качества
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/delivery-icon.svg" alt="">
                                <div class="text">
                                    Оперативная отправка <br>в течение 1 - 3 дней
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/pay-icon.svg" alt="">
                                <div class="text">
                                    Оплата на <br>сайте
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/showroom-icon.svg" alt="">
                                <div class="text">
                                    Шоурум в <br>Москве
                                </div>
                            </div>
                        </div>
                        <div class="help-block">
                            <div class="info">
                                <?$APPLICATION->IncludeFile(
                                    SITE_TEMPLATE_PATH . "/include/product_help_block.php",
                                    array(),
                                    array(
                                        "SHOW_BORDER" => true,
                                        "MODE" => "html"
                                    )
                                );?>
                            </div>
                            <a class="tel" href="tel:<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/include/phone.php", [], ["SHOW_BORDER" => false]);?>">
                                <?$APPLICATION->IncludeFile(
                                    SITE_TEMPLATE_PATH . "/include/phone.php",
                                    array(),
                                    array(
                                        "SHOW_BORDER" => true,
                                        "MODE" => "text"
                                    )
                                );?>
                            </a>
                        </div>
                    </div>
                    <div class="address mob">
                        <?
                        global $arrFilter;
                        $arrFilter = array('PROPERTY_DEFAULT_VALUE' => 'Да');
                        $APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "product_contact",
                            array(
                                "COMPONENT_TEMPLATE" => "product_contact",
                                "IBLOCK_TYPE" => "aspro_mshop_content",
                                "IBLOCK_ID" => "4",
                                "NEWS_COUNT" => "1",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_ORDER1" => "DESC",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "arrFilter",
                                "FIELD_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "EMAIL",
                                    1 => "ADDRESS",
                                    2 => "DEFAULT",
                                    3 => "SCHEDULE",
                                    4 => "PHONE",
                                    5 => "",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "N",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "Y",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "SET_TITLE" => "N",
                                "SET_BROWSER_TITLE" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "N",
                                "STRICT_SECTION_CHECK" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "Новости",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SET_STATUS_404" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => ""
                            ),
                            $component
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tabs-block">
            <ul class="tabs">
                <?if ($arResult['DETAIL_TEXT']) {?>
                    <li class="tab-link current" data-tab="tab-1"><?=Loc::getMessage('DETAIL_TEXT_TAB')?></li>
                <?}?>
                <li class="tab-link <?=empty($arResult['DETAIL_TEXT']) ? 'current' : ''?>" data-tab="tab-2"><?=Loc::getMessage('DELIVERY_TAB')?></li>
                <li class="tab-link" data-tab="tab-3"><?=Loc::getMessage('REFUND_TAB')?></li>
                <li class="tab-link" data-tab="tab-4"><?=Loc::getMessage('SHOWROOM_TAB')?></li>
            </ul>
            <?if ($arResult['DETAIL_TEXT']) {?>
                <button class="tab-link current" data-tab="tab-1"><?=Loc::getMessage('DETAIL_TEXT_TAB')?></button>
                <div id="tab-1" class="tab-content current">
                    <?=$arResult['DETAIL_TEXT']?>
                </div>
            <?}?>
            <button class="tab-link <?=empty($arResult['DETAIL_TEXT']) ? 'current' : ''?>" data-tab="tab-2"><?=Loc::getMessage('DELIVERY_TAB')?></button>
            <div id="tab-2" class="tab-content <?=empty($arResult['DETAIL_TEXT']) ? 'current' : ''?>">
                <?$APPLICATION->IncludeFile(
                    SITE_TEMPLATE_PATH . "/include/product_tab_delivery.php",
                    array(),
                    array(
                        "SHOW_BORDER" => true,
                        "MODE" => "html"
                    )
                );?>
            </div>
            <button class="tab-link" data-tab="tab-3"><?=Loc::getMessage('REFUND_TAB')?></button>
            <div id="tab-3" class="tab-content">
                <?$APPLICATION->IncludeFile(
                    SITE_TEMPLATE_PATH . "/include/product_tab_refund.php",
                    array(),
                    array(
                        "SHOW_BORDER" => true,
                        "MODE" => "html"
                    )
                );?>
            </div>
            <button class="tab-link" data-tab="tab-4"><?=Loc::getMessage('SHOWROOM_TAB')?></button>
            <div id="tab-4" class="tab-content">
                <?
                global $arrFilter;
                $arrFilter = array('PROPERTY_DEFAULT_VALUE' => 'Да');
                $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "product_tab_contact",
                    array(
                        "COMPONENT_TEMPLATE" => "product_tab_contact",
                        "IBLOCK_TYPE" => "aspro_mshop_content",
                        "IBLOCK_ID" => "4",
                        "NEWS_COUNT" => "1",
                        "SORT_BY1" => "ACTIVE_FROM",
                        "SORT_ORDER1" => "DESC",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "arrFilter",
                        "FIELD_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "EMAIL",
                            1 => "ADDRESS",
                            2 => "DEFAULT",
                            3 => "SCHEDULE",
                            4 => "PHONE",
                            5 => "",
                        ),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "N",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "SET_TITLE" => "N",
                        "SET_BROWSER_TITLE" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "N",
                        "STRICT_SECTION_CHECK" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "Новости",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "SET_STATUS_404" => "N",
                        "SHOW_404" => "N",
                        "MESSAGE_404" => ""
                    ),
                    $component
                );
                ?>
            </div>
        </div>
        <div class="card-block  desktop_show">
            <div class="card-body">
                <div class="data">
                    <div class="pluses mobile">
                        <div class="pluses-block">
                            <div class="item">
                                <img src="/local/front/files/img/quality-icon.svg" alt="">
                                <div class="text">
                                    Ткани <br>высокого качества
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/delivery-icon.svg" alt="">
                                <div class="text">
                                    Оперативная отправка <br>в течение 1 - 3 дней
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/pay-icon.svg" alt="">
                                <div class="text">
                                    Оплата на <br>сайте
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/showroom-icon.svg" alt="">
                                <div class="text">
                                    Шоурум в <br>Москве
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
