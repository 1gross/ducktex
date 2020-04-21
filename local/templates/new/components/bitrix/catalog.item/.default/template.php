<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>

    <a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="product-card-front">
        <div class="image <?=empty($arResult['PICTURE']) ? 'no-image' : ''?>" style="background-image: url('<?=$arResult['PICTURE']?>');">

        </div>
        <?if ($arResult['DISCOUNT']) {?>
            <div class="badge">
                -<?=$arResult['DISCOUNT']['VALUE']?>%
            </div>
        <?}?>
        <?foreach ($arResult['PROPERTIES']['HIT']['VALUE_XML_ID'] as $XML_ID) {?>
            <?if ($XML_ID == 'NEW') {?>
                <div class="badge">NEW</div>
            <?}?>
        <?}?>
        <div class="title">
            <?=$arResult['NAME']?>
        </div>
        <div class="price" data-ratio="<?=$arResult['CATALOG_MEASURE_RATIO']?>">
            <div class="new">
                <?$isMinPrice = isset($arResult['MIN_PRICE']['VALUE']) && ($arResult['MIN_PRICE']['VALUE'] < $arResult['PRICE_ITEM']['PRICE']); ?>
                <?=$isMinPrice ? 'от ' : ''?>
                <?=$isMinPrice ? $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] : $arResult['PRICE_ITEM']['PRINT_PRICE']?>
                <?=$arResult['CATALOG_MEASURE_NAME'] ? ' / ' . $arResult['CATALOG_MEASURE_NAME'] : ''?>
            </div>
            <?if (isset($arResult['DISCOUNT']) && $arResult['DISCOUNT']['VALUE'] > 0) {

                ?>
                <?$price = $arResult['MIN_PRICE']['VALUE'] ?: $arResult['PRICE_ITEM']['PRICE']?>
                <div class="last"><?=round(($price/(100 - $arResult['DISCOUNT']['VALUE'])) * 100, 2)?> руб.</div>
            <?}?>
        </div>
    </a>
    <div class="hover">
        <?if ($arParams['PROPERTY_CODE']) {?>
            <?
            $i=0;
            foreach ($arParams['PROPERTY_CODE'] as $CODE) {?>
                <?if (isset($arResult['PROPERTIES'][$CODE]) && strlen($arResult['PROPERTIES'][$CODE]['VALUE'] > 0) && $i < 3) {?>
                    <?$arProp = $arResult['PROPERTIES'][$CODE];?>
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
        <a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="btn outline">подробнее</a>
    </div>
    <div class="buttons-block">
        <button data-id="<?=$arResult['ID']?>" data-action="add_favorites" class="js-init-action favorites"></button>
        <button data-id="<?=$arResult['ID']?>" data-action="add_compare" class="js-init-action compare"></button>
    </div>

