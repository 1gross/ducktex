<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<table class="basket-table">
    <thead>
    <tr>
        <th></th>
        <th><?=Loc::getMessage('HEADER_NAME')?></th>
        <th><?=Loc::getMessage('HEADER_PRICE')?></th>
        <th><?=Loc::getMessage('HEADER_QUANTITY')?></th>
        <th><?=Loc::getMessage('HEADER_SUM')?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($arResult['GRID']["ROWS"] as $ROW) {?>
        <tr>
            <td>
                <?if ($ROW['PREVIEW_PICTURE'] || $ROW['DETAIL_PICTURE']) {?>
                    <?$img = $ROW['PREVIEW_PICTURE'] ?: $ROW['DETAIL_PICTURE']?>
                    <a href="<?=$ROW['DETAIL_PAGE_URL']?>" class="product-image" style="background-image: url('<?=$img['SRC']?>');"></a>
                <?}?>
            </td>
            <td><a href="<?=$ROW['DETAIL_PAGE_URL']?>" class="product-name"><?=$ROW['NAME']?></a></td>
            <td>
                <div class="product-price">
                    <?if (floatval($ROW['DISCOUNT_PRICE']) > 0) {?>
                        <div class="last"><?=$ROW['FULL_PRICE_FORMATED']?> / <?=$ROW['MEASURE_TEXT']?></div>
                    <?}?>
                    <?if (floatval($ROW['DISCOUNT_PRICE']) > 0) {?>
                        <div class="new"><?=$ROW['FULL_PRICE'] - $ROW['DISCOUNT_PRICE']?> руб. / <?=$ROW['MEASURE_TEXT']?></div>
                    <?} else {?>
                        <div class="new"><?=$ROW['FULL_PRICE_FORMATED']?> / <?=$ROW['MEASURE_TEXT']?></div>
                    <?}?>
                </div>
            </td>
            <td>
                <?$ROW["MEASURE_RATIO"] = isset($ROW["MEASURE_RATIO"]) ? $ROW["MEASURE_RATIO"] : 1; ?>
                <div class="quantity-block" data-page="basket">
                    <button class="quant-btn quantity-arrow-minus js-init-action" data-action="update_basket" data-type="de" data-id="<?=$ROW['PRODUCT_ID']?>"> - </button>
                    <input class="quantity-num"
                           id="quantity-c"
                           data-value="<?=$ROW['QUANTITY']?>"
                           data-min="<?=$ROW["MEASURE_RATIO"]?>"
                           data-max="<?=$ROW["AVAILABLE_QUANTITY"]?>"
                           data-step="<?=$ROW["MEASURE_RATIO"]?>"
                           data-unit="<?=$ROW['MEASURE_TEXT']?>"
                           type="text"
                           value="<?=$ROW['QUANTITY']?> <?=$ROW['MEASURE_TEXT']?>" />
                    <button class="quant-btn quantity-arrow-plus js-init-action" data-action="update_basket" data-type="in" data-id="<?=$ROW['PRODUCT_ID']?>"> + </button>
                </div>
            </td>
            <td>
                <div class="product-final-price">
                    <?if (floatval($ROW['DISCOUNT_PRICE_PERCENT']) > 0) {?>
                        <div class="last"><?=$ROW['SUM_FULL_PRICE_FORMATED']?></div>
                    <?}?>
                    <?if (floatval($ROW['DISCOUNT_PRICE_PERCENT']) > 0) {?>
                        <div class="new"><?=$ROW['SUM_VALUE'] - $ROW['SUM_DISCOUNT_PRICE']?> руб. / <?=$ROW['MEASURE_TEXT']?></div>
                    <?} else {?>
                        <div class="new"><?=$ROW['SUM_FULL_PRICE_FORMATED']?></div>
                    <?}?>
                </div>
            </td>
            <td>
                <button class="product-delete js-init-action" data-action="remove_basket" data-id="<?=$ROW['PRODUCT_ID']?>"></button>
            </td>
        </tr>
    <?}?>
    </tbody>
</table>
<div class="basket-footer">
    <?
    $setCoupon = false;
    if (isset($arResult['COUPON_LIST'][0]['DISCOUNT_NAME']) && $arResult['COUPON_LIST'][0]['JS_STATUS'] == 'APPLYED') {
        $setCoupon = true;
    }?>
    <div class="promocode">
        <input type="text"
               placeholder="Введите код купона для скидки"
               name="coupon_code"
            <?if ($setCoupon) {?>
                disabled="disabled"
                value='Купон "<?=$arResult['COUPON_LIST'][0]['COUPON']?>" применен'
            <?}?>
        >
        <button class="btn outline big js-init-action" data-action="send_form" <?=$setCoupon ? 'disabled="disabled"' : ''?> data-id="set_coupon">применить</button>
    </div>
    <div class="bonus">
        Бонус за заказ: <span><?=$arParams['ALL_BONUS'] ? round($arParams['ALL_BONUS'], 2) : 0?> руб</span>
    </div>
    <div class="final-price">
        ИТОГО: <span><?=$arResult['allSum_FORMATED']?></span>
    </div>
</div>