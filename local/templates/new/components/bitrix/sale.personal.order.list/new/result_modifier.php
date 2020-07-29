<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\Loader,
    Bitrix\Sale;

Loader::includeModule('sale');
Loader::includeModule('iblock');

foreach ($arResult['ORDERS'] as $k => $arOrder) 
{
    //dump($arOrder['ORDER'], true);
    $order = Sale\Order::loadByAccountNumber($arOrder['ORDER']['ID']);
    $propertyCollection = $order->getPropertyCollection();
    $arProps = $propertyCollection->getArray();


    //

    $arPayments = [];
    $paymentCollection = $order->getPaymentCollection();

    foreach ($paymentCollection as $payment)
    {
        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $arPayments[] = [
            'NAME' => $payment->getPaymentSystemName(),
            'SUM' => $payment->getSum(),
            'IS_PAID' => $payment->isPaid(),
            'ID' => $payment->getId()
        ];
    }

    $arResult['ORDERS'][$k]['PAYMENTS'] = $arPayments;

    if ($arProps['properties'])
    {
        foreach ($arProps['properties'] as $key => $arProp)
        {
            switch ($arProp['CODE']) {
                case 'LOCATION':
                case 'IPOLSDEK_CNTDTARIF':
                    unset($arProps['properties'][$key]);
                    break;
            }
        }

        $arDeliveryStatus = [
            'DN' => 'Ожидает обработки',
            'DA' => 'Комплектация заказа',
            'DG' => 'Ожидаем приход товара',
            'DT' => 'Ожидаем забора транспортной компанией',
            'DS' => 'Передан в службу доставки',
            'DF' => 'Отгружен'
        ];
        $deliveryCollection = $order->getShipmentCollection();
        $delivery = $deliveryCollection->current();
        $arDelivery = [
            'NAME' => $delivery->getField('DELIVERY_NAME'),
            'ID' => $delivery->getId(),
            'STATUS_ID' => $delivery->getField('STATUS_ID'),
            'STATUS' => $arDeliveryStatus[$delivery->getField('STATUS_ID')] ?: '-'
        ];
        $arResult['ORDERS'][$k]['DELIVERY'] = $arDelivery;

        $arProps['properties'][] = [
            'NAME' => 'Служба доставки',
            'VALUE' => [$arDelivery['NAME'] ?: 'Не выбрано'],
            'PROPS_GROUP_ID' => 2
        ];

        if ($arDelivery['STATUS_ID']) {
            $arProps['properties'][] = [
                'NAME' => 'Статус доставки',
                'VALUE' => [$arDeliveryStatus[$arDelivery['STATUS_ID']] ?: 'Не известно'],
                'PROPS_GROUP_ID' => 2
            ];
        }
    }

    $arResult['ORDERS'][$k]['PROPS'] = $arProps;


    foreach ($arOrder['BASKET_ITEMS'] as $key => $item)
    {
        $arProduct = CIBlockElement::GetByID($item['PRODUCT_ID'])->Fetch();
        $img = '';
        if (isset($arProduct['PREVIEW_PICTURE']) && !empty($arProduct['PREVIEW_PICTURE'])) {
            $img = CFile::GetPath($arProduct['PREVIEW_PICTURE']);
        } elseif (isset($arProduct['DETAIL_PICTURE']) && !empty($arProduct['DETAIL_PICTURE'])) {
            $img = CFile::GetPath($arProduct['PREVIEW_PICTURE']);
        } else {
            $img = '/local/front/files/img/simple.png';
        }
        $arResult['ORDERS'][$k]['BASKET_ITEMS'][$key]['PICTURE'] = $img;
    }


}