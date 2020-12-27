<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="products-list">
    <?foreach ($arResult['ITEMS'] as $arItem) {?>
        <div class="product-card" id="<?=$this->GetEditAreaId($arResult['ID']);?>">
            <?$APPLICATION->IncludeComponent(
                'bitrix:catalog.item',
                '',
                array(
                    'ITEM' => $arItem,
                    'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
                    'IS_NEW' => $arParams['IS_NEW'] ?: 'N'
                )
            )?>
        </div>
    <?}?>
</div>
<?=$arResult['NAV_STRING']?>