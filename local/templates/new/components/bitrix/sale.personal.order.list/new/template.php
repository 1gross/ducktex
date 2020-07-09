<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>

<section class="block-overlay">
    <h2><?=$APPLICATION->GetTitle()?></h2>
    <?if ($arResult['ORDERS']) {?>
    <div class="order-list">
        <table class="order-list__table">
            <thead>
            <tr>
                <th>Номер заказа</th>
                <th>Дата заказа</th>
                <th>Статус</th>
                <th>Трек-номер</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?foreach ($arResult['ORDERS'] as $arOrder) {
                $arProducts = [];
                foreach ($arOrder['BASKET_ITEMS'] as $arProduct) {
                    $arProducts[$arProduct['PRODUCT_ID']] = [
                            'id' => $arProduct['PRODUCT_ID'],
                            'quantity' => $arProduct['QUANTITY']
                    ];
                }
                $arOrder['ORDER']['STATUS'] = $arResult['INFO']['STATUS'][$arOrder['ORDER']['STATUS_ID']]['NAME'];
                if ($arOrder['ORDER']['STATUS_ID'] == 'P') {
                    if ($arOrder['ORDER']['PAYED'] == 'N') {
                        $arOrder['ORDER']['STATUS'] = 'Обработан, не оплачен';
                    }
                }
                if ($arOrder['ORDER']['CANCELED'] == 'Y') {
                    $arOrder['ORDER']['STATUS'] = 'Отменен';
                }
                ?>
                <tr data-id="<?=$arOrder['ORDER']['ID']?>">
                    <td><span class="js-init-order__show btn-link__underline">№<?=$arOrder['ORDER']['ID']?></span></td>
                    <td><?=$arOrder['ORDER']['DATE_INSERT_FORMATED']?></td>
                    <td><?=$arOrder['ORDER']['STATUS']?></td>
                    <td><?=$arOrder['ORDER']['TRACKING_NUMBER'] ?: '-'?></td>
                    <td><span class="js-init-order__show btn-link__underline btn-icon">Посмотреть</span></td>
                    <td><span class="js-init-order__buy btn-link" data-products='<?=json_encode($arProducts)?>'>Повторить</span></td>
                </tr>
            <?}?>
            </tbody>
        </table>
    </div>
    <?} else {?>
        <div class="personal-order-clear-block">
            <p class="title">У вас пока нет заказов</p>
            <br>
            <a href="<?=SITE_DIR?>catalog/" class="btn outline">начать покупки</a>
        </div>
    <?}?>
</section>
<?if ($arResult['ORDERS']) {?>
<div style="display:none;">
    <?foreach ($arResult['ORDERS'] as $arOrder) {
        $arOrder['ORDER']['STATUS'] = $arResult['INFO']['STATUS'][$arOrder['ORDER']['STATUS_ID']]['NAME'];
        if ($arOrder['ORDER']['STATUS_ID'] == 'P') {
            if ($arOrder['ORDER']['PAYED'] == 'N') {
                $arOrder['ORDER']['STATUS'] = 'Обработан, не оплачен';
            }
        }
        if ($arOrder['ORDER']['CANCELED'] == 'Y') {
            $arOrder['ORDER']['STATUS'] = 'Отменен';
        }

        ?>
        <div class="order_<?=$arOrder['ORDER']['ID']?>">
            <div class="group-field">
                <div class="group-field__title">Заказ</div>
                <div class="group-field__item">
                    <div class="group-field__cell group-field__item-name">Статус:</div>
                    <div class="group-field__cell group-field__item-value"><?=$arOrder['ORDER']['STATUS']?></div>
                </div>
                <div class="group-field__item">
                    <div class="group-field__cell group-field__item-name">Дата:</div>
                    <div class="group-field__cell group-field__item-value"><?=$arOrder['ORDER']['DATE_INSERT_FORMATED']?></div>
                </div>
            </div>
            <?foreach ($arOrder['PROPS']['groups'] as $groupId => $group) {?>
                <div class="group-field" style="text-align:left">
                    <div class="group-field__title"><?=$group['NAME']?></div>
                    <?foreach ($arOrder['PROPS']['properties'] as $property) {?>
                        <?if ($groupId == $property['PROPS_GROUP_ID'] && strlen($property['VALUE'][0]) > 0) {

                            if ($property['CODE'] == 'IPOLSDEK_CNTDTARIF') {
                                $show = false;
                                foreach ($arOrder['SHIPMENT'] as $shipment) {
                                    if (strpos(strtolower($shipment['NAME']), 'сдэк') !== false) {
                                        $show = true;
                                    }
                                }
                                if ($show == false) {
                                    continue;
                                }
                            }
                            switch ($property['TYPE']) {
                                case 'Y/N':
                                    $value = $property['VALUE'][0] == 'Y' ? 'Да' : 'Нет';
                                    break;
                                case 'ENUM':
                                    $value = $property['OPTIONS'][$property['VALUE'][0]] ?: '';
                                    break;
                                default:
                                    $value = $property['VALUE'][0];
                            } ?>
                            <div class="group-field__item">
                                <div class="group-field__cell group-field__item-name"><?=$property['NAME']?></div>
                                <div class="group-field__cell group-field__item-value"><?=$value?></div>
                            </div>
                        <?}?>
                    <?}?>
                </div>
            <?}?>
            <button class="btn blue js-init-order__show-products"><span class="btn-arrow arrow-down">Показать состав заказа</span></button>
            <div class="order-basket" style="display: none">
                <h3>Состав заказа:</h3>
                <div class="order-basket__list">
                    <div class="order-basket__list-head products-row">
                        <div class="head basket-cell"></div>
                        <div class="head basket-cell name"><div class="cell-title">Наименование</div></div>
                        <div class="head basket-cell price"><div class="cell-title">Стоимость</div></div>
                        <div class="head basket-cell quantity"><div class="cell-title">Кол-во</div></div>
                        <div class="head basket-cell sum"><div class="cell-title">Итого</div></div>
                    </div>
                    <div class="order-basket__list-body">
                        <?foreach ($arOrder['BASKET_ITEMS'] as $arProduct) {?>
                            <div class="order-basket__list-item products-row">
                                <div class="basket-cell">
                                    <div class="basket-item__picture" style="background-image: url(<?=$arProduct['PICTURE']?>);"></div>
                                </div>
                                <div class="basket-cell name">
                                    <div class="cell-title">Наименование</div>
                                    <div class="cell-value"> <?=$arProduct['NAME']?></div>
                                </div>
                                <div class="basket-cell price">
                                    <div class="cell-title">Стоимость</div>
                                    <div class="cell-value"><?=$arProduct['PRICE'] ? round($arProduct['PRICE'],2) . ' руб. / ' .$arProduct['MEASURE_NAME'] : ''?></div>
                                </div>
                                <div class="basket-cell quantity">
                                    <div class="cell-title">Кол-во</div>
                                    <div class="cell-value"><?=$arProduct['QUANTITY'] ? $arProduct['QUANTITY'] .' '. $arProduct['MEASURE_NAME'] : ''?></div>
                                </div>
                                <div class="basket-cell sum">
                                    <div class="cell-title">Итого</div>
                                    <div class="cell-value"><?=$arProduct['BASE_PRICE'] ? round($arProduct['BASE_PRICE'] * $arProduct['QUANTITY'],2)  . ' руб. / ' .$arProduct['MEASURE_NAME'] : ''?></div>
                                </div>
                            </div>
                        <?}?>
                    </div>
                </div>
                <div class="order-basket__sum">
                    <div><strong>ИТОГО: <?=round($arOrder['ORDER']['PRICE'], 2) . ' руб.'?></strong></div>
                </div>
            </div>
        </div>
    <?}?>
</div>
<?=$arResult['NAV_STRING']?>
<?}?>
