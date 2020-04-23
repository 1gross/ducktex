<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
CModule::IncludeModule('sale');
$userOrderSum = 0;
$rs = CSaleOrder::GetList(
    array(),
    array(
        'USER_ID' => $USER->GetID(),
        'STATUS_ID' => array('F')
    )
);
$userOrdersSum = 0;
while ($ar = $rs->Fetch()) {
    $userOrdersSum += $ar['PRICE'];
}
$arResult['SUM_ORDER_PRICE'] = $userOrdersSum;

global $DB;
$res = $DB->Query('SELECT * FROM `logictim_balls_profiles` ORDER BY `sort` LIMIT 50');

$arResult['PAID_STATUS'] = array();
$arResult['PAID_SUM_MIN'] = 0;
while ($arProfile = $res->Fetch()) {
    if ($arProfile['type'] == 'order') {
        $arConditions = unserialize($arProfile['conditions']);
        $arProfileConditions = unserialize($arProfile['profile_conditions']);
        $ordersBonusType = '';
        $ordersBonus = '';
        $ordersSum = '';

        foreach ($arProfileConditions['children'] as $profileCondition) {
            if (isset($profileCondition['values']['ordersSum'])) {
                if (!empty($profileCondition['values']['ordersSum'])) {
                    $ordersSum = $profileCondition['values']['ordersSum'];
                }
            }
        }

        $ordersBonusType = $arConditions['children'][0]['values']['bonus_type'];
        $ordersBonus = $arConditions['children'][0]['values']['bonus'];

        if ($ordersBonusType == 'percent') {
            if ($arResult['PAID_SUM_MIN'] == 0 || $ordersSum < $arResult['PAID_SUM_MIN']) {
                $arResult['PAID_SUM_MIN'] = $ordersSum;
                $arResult['PAID_SUM_MIN_ID'] = $arProfile['id'];
            }
            $active = $userOrdersSum >= $ordersSum ? 'Y' : 'N';
            $arResult['PAID_STATUS'][$arProfile['id']] = array(
                'ID' => $arProfile['id'],
                'NAME' => $arProfile['name'],
                'PERCENT' => $ordersBonus,
                'SUM' => $ordersSum,
                'ACTIVE' => $active
            );

            $arResult['PAID_STATUS_CURRENT'] = $active == 'Y' ? $arProfile['id'] : false;
            if ($arResult['PAID_STATUS_CURRENT'] !== false) {
                if ($arResult['PAID_STATUS'][$arResult['PAID_STATUS_CURRENT']]['SUM'] < $arResult['PAID_STATUS'][$arProfile['id']]['SUM']) {
                    $arResult['PAID_STATUS_CURRENT'] = $arProfile['id'];
                }
            }
        }
    }
}
if (isset($arResult['PAID_STATUS'][$arResult['PAID_STATUS_CURRENT']]['ACTIVE'])) {
    $arResult['PAID_STATUS'][$arResult['PAID_STATUS_CURRENT']]['ACTIVE'] = 'Y';
}
/*


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
}*/
