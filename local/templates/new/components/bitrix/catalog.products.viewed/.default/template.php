<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<?if ($arResult['ITEMS']) {?>
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