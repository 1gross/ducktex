<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
$frame = $this->createFrame()->begin("");

CJSCore::Init('jquery');
if (isset($arResult['REQUEST_ITEMS'])){
    CJSCore::Init(array('ajax'));
    // component parameters
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedParameters = $signer->sign(
        base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
        'bx.bd.products.recommendation'
    );
    $signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');?>


    <script type="application/javascript">
        BX.ready(function(){
            bx_rcm_get_from_cloud(
                'recom_product',
                <?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
                {
                    'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
                    'template': '<?=CUtil::JSEscape($signedTemplate)?>',
                    'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
                    'rcm': 'yes'
                }
            );
        });
    </script>

    <?$frame->end();
    return;
}?>
    <?if ($arResult['ITEMS']) {?>

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
                                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="product-card-front">
                                    <div class="image <?=empty($arItem['PICTURE']) ? 'no-image' : ''?>" style="background-image: url('<?=$arItem['PICTURE']?>');">

                                    </div>
                                    <?if ($arItem['DISCOUNT']) {?>
                                        <div class="badge">
                                            -<?=$arItem['DISCOUNT']['VALUE']?>%
                                        </div>
                                    <?}?>
                                    <?foreach ($arItem['PROPERTIES']['HIT']['VALUE_XML_ID'] as $XML_ID) {?>
                                        <?if ($XML_ID == 'NEW') {?>
                                            <div class="badge">NEW</div>
                                        <?}?>
                                    <?}?>
                                    <div class="title">
                                        <?=$arItem['NAME']?>
                                    </div>
                                    <div class="price" data-ratio="<?=$arItem['CATALOG_MEASURE_RATIO']?>">
                                        <div class="new">

                                            <?=$arItem['MIN_PRICE']['VALUE'] ? $arItem['MIN_PRICE']['PRINT_VALUE'] . ' / ' . $arItem['CATALOG_MEASURE_NAME'] : ''?>

                                        </div>
                                        <?if ($arItem['PRICES']['BASE']['DISCOUNT_DIFF'] > 0) {?>
                                            <div class="last"><?=$arItem['PRICES']['BASE']['PRINT_DISCOUNT_VATRATE_VALU']?></div>
                                        <?}?>
                                    </div>
                                </a>
                                <div class="hover">

                                    <?
                                    $i=0;
                                    foreach ($arResult['SHOW_PROPERTIES'] as $CODE) {?>
                                        <?if (isset($arItem['PROPERTIES'][$CODE]) && !empty($arItem['PROPERTIES'][$CODE]['VALUE']) && $i < 3) {?>
                                            <?$arProperty = $arItem['PROPERTIES'][$CODE];?>
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

                                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn outline">подробнее</a>
                                </div>
                                <div class="buttons-block">
                                    <?if ($USER->IsAuthorized()) {?>
                                        <button data-id="<?=$arItem['ID']?>" data-action="add_favorites" class="js-init-action favorites"></button>
                                    <?}?>
                                    <button data-id="<?=$arItem['ID']?>" data-action="add_compare" class="js-init-action compare"></button>
                                </div>

                            </div>
                        <?}?>
                    </div>
                </div>
            </div>

    <?}?>
<?
$frame->end();
?>