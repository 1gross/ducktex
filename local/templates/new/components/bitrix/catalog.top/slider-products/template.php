<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
//dump($arResult['ITEMS']);
?>
<section class="block sales slider-block">
    <div class="wrapper">
        <div class="sales-block">
            <div class="header">
                <h2><?=$arParams['BLOCK_TITLE']?></h2>
                <div class="сontrol">
                    <button class="arrow left"></button>
                    <div class="count"></div>
                    <button class="arrow right"></button>
                </div>
            </div>
            <div class="slider">
                <?foreach ($arResult['ITEMS'] as $arItem) {
                   /* foreach ($arItem['PROPERTIES'] as $arProp) {
                        echo '<div>';
                        dump($arProp['NAME']);
                        dump($arProp['PROPERTY_TYPE']);
                        dump($arProp['VALUE']);
                        echo '</div>';
                    }*/
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

                    $arDiscounts = CCatalogDiscount::GetDiscountByProduct($arItem['ID'], $USER->GetUserGroupArray(), "N", 1, SITE_ID);
                    if ($arDiscounts) {
                        $arDiscount = current($arDiscounts);
                    }
                    ?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="product-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <div class="image" style="background-image: url('<?=$arItem['PREVIEW_PICTURE']['SRC']?>');"></div>
                        <?if ($arDiscount['VALUE']) {?>
                            <div class="badge">
                                -<?=$arDiscount['VALUE']?>%
                            </div>
                        <?} else {?>
                            <div class="badge">
                                NEW
                            </div>
                        <?}?>
                        <div class="price" data-ratio="<?=$arItem['CATALOG_MEASURE_RATIO']?>">
                            <div class="new">от <?=$arItem['MIN_PRICE']['PRINT_VALUE_NOVAT']?> / <?=$arItem['CATALOG_MEASURE_NAME']?></div>
                            <?if ($arItem['MIN_PRICE']['PRINT_VALUE_NOVAT'] > $arItem['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_NOVAT']) {?>
                                <div class="last"><?=$arItem['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_NOVAT']?></div>
                            <?}?>
                        </div>
                        <div class="hover">
                            <div class="buttons-block">
                                <button data-action="add_favorites" data-id="<?=$arItem['ID']?>" class="favorites"></button>
                                <button data-action="add_compare" data-id="<?=$arItem['ID']?>" class="compare"></button>
                            </div>
                            <?if ($arParams['PROPERTY_CODE']) {?>
                                <?
                                $i=0;
                                foreach ($arParams['PROPERTY_CODE'] as $CODE) {?>
                                    <?if (isset($arItem['PROPERTIES'][$CODE]) && strlen($arItem['PROPERTIES'][$CODE]['VALUE'] > 0) && $i < 3) {?>
                                        <?$arProp = $arItem['PROPERTIES'][$CODE];?>
                                        <div class="hover-item">
                                            <span class="hover-item-name"><?=$arProp['NAME']?>:</span>
                                            <?switch ($arProp['PROPERTY_TYPE']) {
                                                case 'S':
                                                    ?>
                                                    <span class="hover-item-value"><?=is_array($arProp['VALUE']) ? implode(', ', $arProp['VALUE']) : $arProp['VALUE']?></span>
                                                    <?
                                                    break;
                                                case 'E':
                                                    $arProp['VALUE'] = CIBlockElement::GetByID($arProp['VALUE'])->Fetch()['NAME'];
                                                    ?>
                                                    <span class="hover-item-value"><?=is_array($arProp['VALUE']) ? implode(', ', $arProp['VALUE']) : $arProp['VALUE']?></span>
                                                    <?
                                                    break;
                                            } ?>
                                        </div>
                                        <?$i++;?>
                                    <?}?>
                                <?}?>
                            <?}?>
                            <button class="btn outline">подробнее</button>
                        </div>
                    </a>
                <?}?>
            </div>
            <div class="center" style="display: none">
                <a href="<>/" class="btn blue">
                    Смотреть все предложения (42)
                </a>
            </div>
        </div>
    </div>
</section>
