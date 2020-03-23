<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<section class="block sales slider-block">
    <div class="wrapper">
        <div class="sales-block">
            <div class="header">
                <h2><?=Loc::getMessage('PRODUCT_VIEWED_TITLE')?></h2>
                <div class="сontrol">
                    <button class="arrow left"></button>
                    <div class="count"></div>
                    <button class="arrow right"></button>
                </div>
            </div>
            <div class="slider">
                <?foreach ($arResult['ITEMS'] as $arItem) {?>
                    <div class="product-card">
                        <div class="image" style="background-image: url(<?=$arItem['PREVIEW_PICTURE']['SRC']?>);"></div>
                        <?$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arItem['ID'], $USER->GetUserGroupArray(), "N", 1, SITE_ID);
                        if ($arDiscounts) {
                            $arDiscount = current($arDiscounts);
                        }?>
                        <?if ($arDiscount['VALUE']) {?>
                        <div class="badge">
                            -<?=$arDiscount['VALUE']?>%
                        </div>
                        <?}?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="title">
                            <?=$arItem['NAME']?>
                        </a>
                        <div class="price">
                            <div class="new"><?=$arItem['ITEM_PRICES'][0]['PRINT_PRICE']?><?=$arItem['ITEM_MEASURE']['TITLE'] ? ' / ' .$arItem['ITEM_MEASURE']['TITLE']. '.' : ''?></div>
                            <?if (count($arItem['ITEM_PRICES']) > 1 || $arItem['ITEM_PRICES'][0]['DISCOUNT']) {?>
                                <div class="last">990 руб.</div>
                            <?}?>
                        </div>
                    </div>
                <?}?>
            </div>
        </div>
    </div>
</section>
