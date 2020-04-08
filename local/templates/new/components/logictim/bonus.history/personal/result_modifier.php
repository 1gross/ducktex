<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
CModule::IncludeModule('sale');
$rs = CSaleOrder::GetList(
    array(),
    array(
        'USER_ID' => $USER->GetID(),
        'STATUS_ID' => array('F')
    )
);
$price = 0;
while ($ar = $rs->Fetch()) {
    $price += $ar['PRICE'];
}
$arResult['PAID_STATUS_CURRENT'] = 1;
$arResult['SUM_ORDER_PRICE'] = $price;
$arResult['PAID_STATUS'] = array(
    1 => array(
        'NAME' => 'Хрустальный',
        'PERCENT' => 2,
        'SUM' => 10000,
        'ACTIVE' => 'Y'
    ),
    2 => array(
        'NAME' => 'Бронзовый',
        'PERCENT' => 3,
        'SUM' => 30000,
        'ACTIVE' => 'N'
    ),
    3 => array(
        'NAME' => 'Серебряный',
        'PERCENT' => 4,
        'SUM' => 50000,
        'ACTIVE' => 'N'
    ),
    4 => array(
        'NAME' => 'Золотой',
        'PERCENT' => 5,
        'SUM' => 100000,
        'ACTIVE' => 'N'
    ),
    5 => array(
        'NAME' => 'Бриллиантовый',
        'PERCENT' => 6,
        'SUM' => 200000,
        'ACTIVE' => 'N'
    ),
    6 => array(
        'NAME' => 'Платиновый',
        'PERCENT' => 10,
        'SUM' => 300000,
        'ACTIVE' => 'N'
    )
);
foreach ($arResult['PAID_STATUS'] as $k => $arStatus) {
    if ($price >= $arStatus['SUM']) {
        $arResult['PAID_STATUS'][$k]['ACTIVE'] = 'Y';
        $arResult['PAID_STATUS_CURRENT'] = $k;
    }
}
