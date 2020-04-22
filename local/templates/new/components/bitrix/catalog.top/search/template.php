<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="search-list">
    <?foreach ($arResult['ITEMS'] as $arItem) {?>
    <div class="product-card">
        <?$APPLICATION->IncludeComponent(
            'bitrix:catalog.item',
            '',
            array(
                'ITEM' => $arItem,
                'PROPERTY_CODE' => $arParams['PROPERTY_CODE']
            )
        )?>
    </div>
    <?}?>
    <?=$arResult['NAV_STRING']?>
</div>
