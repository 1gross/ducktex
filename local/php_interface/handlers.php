<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main,
    Bitrix\Main\EventManager,
    Bitrix\Main\UserPhoneAuthTable;

EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockSectionAdd', 'setSectionCode');
EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', 'setSectionCode');

EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'setSectionCode');
EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'setSectionCode');
function setSectionCode(&$arFields)
{

    if (isset($arFields['IBLOCK_ID']) && $arFields['IBLOCK_ID'] == IBLOCK_CATALOG_ID) {
        if (isset($arFields['ID']) && $arFields['ID']) {
            if (isset($arFields['CODE']) && $arFields['CODE']) {
                $arItem = CIBlockElement::GetByID($arFields['ID'])->Fetch();
                if ($arItem['CODE'] != $arFields['CODE']) {
                    $arFields['CODE'] = CUtil::translit($arFields['NAME'], 'ru');
                }
            }
        } else {
            $arFields['CODE'] = CUtil::translit($arFields['NAME'], 'ru');
        }
    }
}


EventManager::getInstance()->addEventHandler('main', 'OnProlog', 'checkCatalogProductRedirect');
function checkCatalogProductRedirect()
{
    global $APPLICATION;

    if (strpos($APPLICATION->GetCurPage(), '/catalog/') !== false) {
      
        $arPath = array_diff(explode('/', $APPLICATION->GetCurPage()),['']);
        $lastElPath = end($arPath);

        if (is_numeric($lastElPath)) {
            $productID = intval($lastElPath);
            $arProduct = CIBlockElement::GetList([],
                ['ID' => $productID, 'IBLOCK_ID' => IBLOCK_CATALOG_ID], false, false,
                ['ID', 'IBLOCK_ID', 'DETAIL_PAGE_URL'])->GetNext();


            if ($arProduct['DETAIL_PAGE_URL']) {
                LocalRedirect($arProduct['DETAIL_PAGE_URL'], false, '301 Moved permanently');
            }

        }
    }

}
//AddEventHandler("main", "OnProlog", 'authTempUser');
function authTempUser()
{
    global $USER, $APPLICATION;

    if (strpos($APPLICATION->GetCurPage(), 'basket') !== false) {

        $basket = B24TechSiteHelper::getBasket();

        if (!$USER->IsAuthorized() && $basket['count_items'] > 0) {
            $login = 'tmp_'.rand(1000000000, 9999999999).'@email'.rand(10, 99).'.com';
            $arResult = $USER->Register($login, "", "", "pass_".$login, "pass_".$login, $login);
            if ($arResult["ID"]) {
                $user = new CUser;
                $fields = Array(
                    "UF_TMP_USER"  => "Y",
                );
                $user->Update($arResult["ID"], $fields);
                $USER->Authorize($arResult["ID"]);
            }
        }
    }
}



Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    'checkAndSetPropertyUser'
);

//в обработчике получаем сумму, с которой планируются некоторые действия в дальнейшем:

function checkAndSetPropertyUser(Main\Event $event)
{
    global $USER;
    $arUserProp = array();
    $isTempUser = true;
    $order = $event->getParameter("ENTITY");
    $propertyCollection = $order->getPropertyCollection();
    foreach ($propertyCollection as $obProp) {
        $arProp = $obProp->getProperty();
        if ($arProp['IS_EMAIL'] == 'Y') {
            $arUserProp['EMAIL'] = $obProp->getValue();
        }
        if ($arProp['IS_PHONE'] == 'Y') {
            $phone = checkPhone($obProp->getValue());
            if (strpos($phone, '+') === false && strlen($phone) == 11) {
                $phone = '+'.$phone;
            }
            $arUserProp['PHONE_NUMBER'] = $phone;
            if (!$USER->IsAdmin()) {
                $arUserProp['LOGIN'] = $phone;
            }
        }
        if ($arProp['CODE'] == 'IS_BONUS_SYSTEM') {
            if ($obProp->getValue() == 'Y') {
                $isTempUser = false;
                $arUserProp['UF_TMP_USER'] = 'N';
            }
        }

        if ($arProp['CODE'] == 'FIO') {
            $arVal = explode(' ', $obProp->getValue());

            if (isset($arVal[0])) {
                $arUserProp['LAST_NAME'] = $arVal[0];
            }
            if (isset($arVal[1])) {
                $arUserProp['NAME'] = $arVal[1];
            }
            if (isset($arVal[2])) {
                $arUserProp['SECOND_NAME'] = $arVal[2];
            }
        }

    }

    $us = new CUser();
    $us->Update($USER->GetID(), $arUserProp);

    if ($isTempUser) {
       // $USER->Logout();
    }

}

function checkPhone($phone) {
    $count = strlen($phone);
    switch ($count) {
        case '10':
            if ($phone[0] == '9') {
                $phone = '+7' . $phone;
            }
            break;
        case '11':
            if ($phone[0] == '8') {
                $phone = '+7' . substr($phone, 1);
            } elseif ($phone[0] == '7') {
                $phone = '+' . $phone;
            }
            break;
    }
    return $phone;
}

AddEventHandler("main", "OnLayoutRender", function () {
    global $APPLICATION;
    $pageCanonicalUrl = $APPLICATION->GetPageProperty("canonical", false);
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    if ($pageCanonicalUrl === false) {
        $canonical = [
            ($request->isHttps() ? "https://" : "http://"),
            $request->getHttpHost(),
            $APPLICATION->GetCurDir(),
        ];
        $APPLICATION->SetPageProperty("canonical", implode("", $canonical));
    }
    $robotsContent = $APPLICATION->GetPageProperty("robots", false);
    if ($robotsContent === false) {
        $robotsFile = new \Bitrix\Seo\RobotsFile(SITE_ID);
        $disallowRules = $robotsFile->getRules("Disallow");
        $isRobotsDisallow = false;
        if (!empty($disallowRules)) {
            foreach ($disallowRules as $rule) {
                $matchRule = preg_quote($rule[1], "#");
                $matchRule = str_replace('\*', '.*', $matchRule);
                if (preg_match("#^" . $matchRule . "#", $request->getRequestUri())) {
                    $isRobotsDisallow = true;
                    break;
                }
            }
        }
        if (!$isRobotsDisallow) {
            $APPLICATION->SetPageProperty("robots", "index, follow");
        }
        else {
            $APPLICATION->SetPageProperty("robots", "noindex, nofollow");
        }
    }
});

//catalog handler


class CustomCatalog
{
    function addProduct($id, $quantity)
    {

    }
}