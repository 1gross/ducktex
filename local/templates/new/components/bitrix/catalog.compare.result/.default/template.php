<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>

<div class="compare-block">
    <?if ($arResult['ITEMS']) {?>
    <div class="compare-name-list">
        <?if ($arResult['SHOW_PROPERTIES']) {?>
            <?foreach ($arResult['SHOW_PROPERTIES'] as $arItem) {?>
                <div class="compare-name-item"><?=$arItem['NAME']?></div>
            <?}?>
        <?}?>
    </div>
    <button data-action="clear_compare" data-refresh="true" class="js-init-action btn outline small clear-compare">очистить сравнение</button>
    <div class="сontrol">
        <button class="arrow left"></button>
        <span class="count"></span>
        <button class="arrow right"></button>
    </div>
    <div class="compare-list">
        <?foreach ($arResult['ITEMS'] as $arItem) {?>
            <div class="product-card">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="product-card-front">
                <div class="image" style="background-image: url('<?=$arItem['PICTURE']?>');"></div>
                <?if ($arItem['DISCOUNT']['VALUE']) {?>
                    <div class="badge">
                        -<?=$arItem['DISCOUNT']['VALUE']?>%
                    </div>
                <?}?>
                <div class="title"><?=$arItem['NAME']?></div>
            </a>
            <div class="hover">
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn outline">подробнее</a>
            </div>
                <?if ($arResult['SHOW_PROPERTIES']) {?>
                    <div class="product-compare">
                        <?foreach ($arResult['SHOW_PROPERTIES'] as $PROP) {?>
                            <?$PROPERTY = $arItem['PROPERTIES'][$PROP['CODE']];?>
                            <?if (isset($PROPERTY) /*&& !is_array($PROPERTY['VALUE'])*/) {?>
                                <div class="product-compare-item" data-id="<?=$PROP['CODE']?>">
                                    <? if (is_array($PROPERTY['VALUE'])) { ?>
                                        <?=implode(', ', $PROPERTY['VALUE'])?>
                                    <?} else {?>
                                        <? if (!empty($PROPERTY['VALUE'])) {
                                            switch ($PROPERTY['VALUE']) {
                                                case 'Y':
                                                    echo 'Да';
                                                    break;
                                                case 'N':
                                                    echo 'Нет';
                                                    break;
                                                default:
                                                    echo $PROPERTY['VALUE'];
                                            }
                                        }?>
                                    <?}?>
                                </div>
                            <?}?>
                        <?}?>
                    </div>
                <?}?>
            <div class="compare-buttons">
                <button data-action="add_basket" data-id="<?=$arItem['ID']?>" class="js-init-action btn blue">в корзину</button>
                <button data-action="add_compare" data-refresh="true" data-id="<?=$arItem['ID']?>" class="js-init-action btn outline big">удалить</button>
            </div>
        </div>
        <?}?>
    </div>
    <?} else {?>
        <p>Товаров в сравнении нет</p>
    <?}?>
</div>

