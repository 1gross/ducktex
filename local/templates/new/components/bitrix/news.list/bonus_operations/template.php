<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<?//dump($arResult['NAV_RESULT']);?>
<div class="personal-wrap personal-history">
    <h2>История операций</h2>
    <div class="history-count">
        Всего <span><?=$arResult['NAV_RESULT']->NavRecordCount?></span> <?=NumPluralForm($arResult['NAV_RESULT']->NavRecordCount, ['операция', 'операции', 'операций'])?></div>
    <table class="table-history">
        <thead>
        <tr>
            <td>Сумма</td>
            <td>Дата и время</td>
            <td>Операция</td>
            <td>Заказ</td>
        </tr>
        </thead>

        <tbody>
        <?foreach ($arResult['ITEMS'] as $arItem) {?>
            <tr>
                <td>
                    <?switch ($arItem['PROPERTIES']['OPERATION_TYPE']['VALUE_XML_ID']) {
                        case 'ADD_FROM_ORDER':
                        case 'BACK_FROM_CANCEL':
                        case 'BACK_FROM_DELETTE':
                        case 'ADD_FROM_REGISTER':
                        case 'ADD_FROM_BIRTHDAY':
                        case 'ADD_FROM_REPOST':
                        case 'ADD_FROM_REVIEW':
                        case 'ADD_FROM_LINK':
                        case 'ADD_FROM_REFERAL':
                            ?>
                            <span class="add__bonus">+ <?=$arItem['PROPERTIES']['OPERATION_SUM']['VALUE']?></span>
                            <?
                            break;
                        case 'MINUS_FROM_ORDER':
                        case 'DEACIVATE_FROM_DATE':
                        case 'EXIT_BONUS':
                        case 'EXIT_REFUND_BONUS':
                            ?>
                            <span class="remove__bonus">- <?=$arItem['PROPERTIES']['OPERATION_SUM']['VALUE']?></span>

                            <?
                            break;
                    }?>
                </td>
                <td><?=$arItem['PROPERTIES']['OPERATION_SUM']['TIMESTAMP_X']?></td>
                <td><?=$arItem['PROPERTIES']['OPERATION_TYPE']['VALUE']?></td>
                <td><?=$arItem['PROPERTIES']['ORDER_ID']['VALUE']?></td>
            </tr>
        <?}?>
        </tbody>
    </table>
    <?=$arResult['NAV_STRING']?>

</div>
