<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Loader;

Loader::includeModule('sale');

class B24TechSiteHelper
{
    public static function getBasket()
    {
        $arResult = array();
        $dbRes = \Bitrix\Sale\Basket::getList([
            'filter' => [
                '=FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
                '=ORDER_ID' => null,
                '=LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                '=CAN_BUY' => 'Y',
            ]
        ]);

        $basketSum = 0;
        while ($item = $dbRes->fetch())
        {
            $sum = round($item['PRICE'] * $item['QUANTITY'], 2);
            $arResult['items'][$item['PRODUCT_ID']] = array(
                'id' => $item['PRODUCT_ID'],
                'ids' => $item['ID'],
                'quantity' => round($item['QUANTITY'], 2),
                'price' => round($item['PRICE'], 2),
                'sum' => $sum
            );
            $basketSum += $sum;
        }
        $arResult['sum'] = $basketSum;
        $arResult['count_items'] = count($arResult['items']) ?: 0;
        return $arResult;
    }

    public static function getCompareList()
    {
        $arCompareList = $_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"];
        if (isset($arCompareList)) {
            foreach ($arCompareList as $k => $arItem) {
                if ($arItem['ACTIVE'] == 'N') {
                    unset($arCompareList[$k]);
                }
            }
        }
        return $arCompareList;
    }
    public static function checkFavoritesById($id)
    {
        return in_array($id, self::getFavorites());
    }

    public static function getFavorites()
    {
        global $USER, $APPLICATION;
        if(!$USER->IsAuthorized()) {
            return unserialize($APPLICATION->get_cookie('favorites')) ?: array();
        } else {
            $rsUser = CUser::GetByID($USER->GetID());
            $arUser = $rsUser->Fetch();
            return $arUser['UF_FAVORITES'] ?: array();
        }
    }

}