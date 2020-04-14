<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="products-list">
    <?foreach ($arResult['ITEMS'] as $arItem) {?>
        <div class="product-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="product-card-front">
                <div class="image" style="background-image: url('<?=$arItem['PREVIEW_PICTURE']['SRC'] ?: '/local/front/files/img/product-bg.jpg'?>');">

                </div>
                <?if ($arDiscount['VALUE']) {?>
                    <div class="badge">
                        -<?=$arDiscount['VALUE']?>%
                    </div>
                <?} else {?>
                    <div class="badge">
                        NEW
                    </div>
                <?}?>
                <div class="title">
                    <?=$arItem['NAME']?>
                </div>
                <div class="price" data-ratio="<?=$arItem['CATALOG_MEASURE_RATIO']?>">
                    <div class="new">от <?=$arItem['MIN_PRICE']['PRINT_VALUE_NOVAT']?> / <?=$arItem['CATALOG_MEASURE_NAME']?></div>
                    <?if ($arItem['MIN_PRICE']['PRINT_VALUE_NOVAT'] > $arItem['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_NOVAT']) {?>
                        <div class="last"><?=$arItem['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_NOVAT']?></div>
                    <?}?>
                </div>
            </a>
            <div class="hover">
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
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn outline">подробнее</a>
            </div>
            <div class="buttons-block">
                <button data-id="<?=$arItem['ID']?>" data-action="add_favorites" class="favorites"></button>
                <button data-id="<?=$arItem['ID']?>" data-action="add_compare" class="compare"></button>
            </div>
        </div>
    <?}?>
</div>
