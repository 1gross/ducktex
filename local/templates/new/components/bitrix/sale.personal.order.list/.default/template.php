<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<div class="personal-wrap personal-orders">
    <h2><?=$APPLICATION->GetTitle()?></h2>
    <?if ($arResult['ORDERS']) {?>
        <?foreach ($arResult['ORDERS'] as $arOrder) {
            $arCssColor = array('F' => 'green', 'N' => 'yellow', 'P' => 'blue');
            $arStatus = array(
                'NAME' => $arResult['INFO']['STATUS'][$arOrder['ORDER']['STATUS_ID']]['NAME'],
                'CSS_CLASS' =>  $arCssColor[$arOrder['ORDER']['STATUS_ID']]
            ); ?>
            <div class="order-item">
                <div class="data">
                    <?=Loc::getMessage('ORDER')?> <span class="number">№<?=$arOrder['ORDER']['ID']?></span> <?=Loc::getMessage('FROM')?> <span class="date"><?=$arOrder['ORDER']['DATE_INSERT_FORMATED']?></span>
                </div>
                <div class="order-list">
                    <?if ($arOrder['BASKET_ITEMS']) {?>
                        <?foreach ($arOrder['BASKET_ITEMS'] as $arItem) {?>
                            <div class="order-list-item" data-id="<?=$arItem['PRODUCT_ID']?>">
                                <div class="info">
                                    <div class="image" style="background-image: url('<?=$arItem['PICTURE']?>');"></div>
                                    <div class="order-info">
                                        <div class="name"><?=$arItem['NAME']?></div>
                                        <div class="price"><?=round($arItem['PRICE'],2)?> <?=Loc::getMessage('CURRENCY_SHORT')?> / <?=$arItem['MEASURE_NAME']?></div>
                                        <div class="quantity"><?=$arItem['QUANTITY']?> <?=$arItem['MEASURE_NAME']?></div>
                                        <div class="status <?=$arStatus['CSS_CLASS']?>"><?=$arStatus['NAME']?></div>
                                    </div>
                                </div>
                                <button class="btn blue repeat js-init-action"
                                        <?=$arItem['CAN_BUY'] != 'Y' ? 'disabled' : ''?>
                                        data-action="add_basket"
                                        data-id="<?=$arItem['PRODUCT_ID']?>"><?=$arItem['CAN_BUY'] != 'Y' ? 'Не доступен' : Loc::getMessage('REORDER')?></button>
                            </div
                        <?}?>
                    <?} else {?>
                        <div class="info-message">
                            <h4><?=Loc::getMessage('NO_PRODUCTS_ORDER')?></h4>
                        </div>
                    <?}?>
                </div>
            </div>
        <?}?>
    <? } else {?>

            <div class="personal-order-clear-block">
                <div class="title">У вас пока нет заказов</div>
                <a href="<?=SITE_DIR?>catalog/" class="start">начать покупки</a>
            </div>
    <?}?>
</div>
<?if ($arResult['NAV_STRING']) {?>
    <?=$arResult['NAV_STRING']?>
<?}?>