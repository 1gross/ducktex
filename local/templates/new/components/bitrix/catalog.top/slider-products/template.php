<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
//dump($arResult['ITEMS']);
?>
<section class="block sales slider-block">
    <div class="wrapper">
        <div class="sales-block">
            <div class="header">
                <h2><?=$arParams['BLOCK_TITLE']?></h2>
                <div class="сontrol">
                    <button class="arrow left"></button>
                    <div class="count"></div>
                    <button class="arrow right"></button>
                </div>
            </div>
            <div class="slider">
                <?foreach ($arResult['ITEMS'] as $arItem) {
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

                    $arDiscounts = CCatalogDiscount::GetDiscountByProduct($arItem['ID'], $USER->GetUserGroupArray(), "N", 1, SITE_ID);
                    if ($arDiscounts) {
                        $arDiscount = current($arDiscounts);
                    }
                    ?>
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
            <div class="center">
                <a href="/catalog/" class="btn blue">
                    Смотреть все предложения (<?= count($arResult['ITEMS'])?>)
                </a>
            </div>
        </div>
    </div>
</section>
