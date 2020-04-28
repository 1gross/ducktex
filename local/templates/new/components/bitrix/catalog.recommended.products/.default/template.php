<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>

<?if ($arResult['ITEMS']) {?>
<section class="block sales slider-block">
    <div class="wrapper">
        <div class="sales-block">
            <div class="header">
                <h2>Рекомендованные товары</h2>
                <div class="сontrol">
                    <button class="arrow left"></button>
                    <div class="count"></div>
                    <button class="arrow right"></button>
                </div>
            </div>
            <div class="slider">
                <?foreach ($arResult['ITEMS'] as $arItem) {?>
                    <div class="product-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
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
            </div>
        </div>
    </div>
</section>
<?}?>