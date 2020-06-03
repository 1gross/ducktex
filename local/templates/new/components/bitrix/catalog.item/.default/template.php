<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

//dump($arResult);
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
                <?$isMinPrice = isset($arResult['MIN_PRICE']['VALUE']); ?>
                <?=$isMinPrice ? 'от ' : ''?>
                <?=$isMinPrice ? $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] : $arResult['PRICE_ITEM']['PRINT_PRICE']?>
                <?$price = $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] ?: $arResult['PRICE_ITEM']['PRINT_PRICE']?>
                <?if ($price) {?>
                    <?=$arResult['CATALOG_MEASURE_NAME'] ? ' / ' . $arResult['CATALOG_MEASURE_NAME'] : ''?>
                <? } else {?>
                    Цена отсутствует
                <?}?>
            </div>
            <?if (isset($arResult['PRICES']['BASE']['DISCOUNT_DIFF']) && $arResult['PRICES']['BASE']['DISCOUNT_DIFF'] > 0) {?>
                <div class="last"><?=$arResult['PRICES']['BASE']['PRINT_VALUE_VAT']?></div>
            <?} elseif (isset($arResult['DISCOUNT']) && $arResult['DISCOUNT']['VALUE'] > 0) {?>
                <?$price = $arResult['MIN_PRICE']['VALUE'] ?: $arResult['PRICE_ITEM']['PRICE']?>
                <div class="last"><?=round(($price/(100 - $arResult['DISCOUNT']['VALUE'])) * 100, 2)?> руб.</div>
            <?}?>
        </div>
    </a>
    <div class="hover">

            <?
            $i=0;
            foreach ($arResult['SHOW_PROPERTIES'] as $CODE) {?>
                <?if (isset($arResult['PROPERTIES'][$CODE]) && !empty($arResult['PROPERTIES'][$CODE]['VALUE']) && $i < 3) {?>
                    <?$arProperty = $arResult['PROPERTIES'][$CODE];?>
                    <?if (!empty($arProperty['VALUE'])) {?>
                        <div class="hover-item">
                            <span class="hover-item-name"><?=$arProperty['NAME']?>:</span>
                            <?switch ($arProperty['PROPERTY_TYPE']) {
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
                                case 'E':
                                    $arProperty['VALUE'] = CIBlockElement::GetByID($arProperty['VALUE'])->Fetch()['NAME'];
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
                            } ?>
                            <?if ($arProperty['VALUE']) {?>
                                <span class="hover-item-value"><?=is_array($arProperty['VALUE']) ? implode(', ', $arProperty['VALUE']) : $arProperty['VALUE']?></span>
                            <?}?>
                        </div>
                        <?$i++;?>
                    <?}?>
                <?}?>
            <?}?>

        <a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="btn outline">подробнее</a>
    </div>
    <div class="buttons-block">
        <?if ($USER->IsAuthorized()) {?>
            <button data-id="<?=$arResult['ID']?>" data-action="add_favorites" class="js-init-action favorites"></button>
        <?}?>
        <button data-id="<?=$arResult['ID']?>" data-action="add_compare" class="js-init-action compare"></button>
    </div>

