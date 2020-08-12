<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\UserPhoneAuthTable,
    Bitrix\Main\Controller\PhoneAuth;

define('STOP_STATISTICS', true);
define("NOT_CHECK_PERMISSIONS", true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('XHR_REQUEST', true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header("Access-Control-Allow-Origin: *");
CModule::IncludeModule('sale');

$smsTestMode = false;

if (isset($_REQUEST['action']) && strlen($_REQUEST['action']) > 0) {
    global $USER, $APPLICATION;

    $arResponse = array();
    switch ($_REQUEST['action']) {
        case 'send_form':
            $isError = false;
            if (isset($_REQUEST['data'])) {
                $arFields = array();
                $arDataTemp = explode('&', $_REQUEST['data']);
                foreach ($arDataTemp as $arTemp) {
                    $arTemp = urldecode($arTemp);
                    list($code, $value) = explode('=', $arTemp);
                    if ($code == 'PHONE_NUMBER') {
                        $value = checkPhone(preg_replace('/[^0-9]/', '', $value));
                        if (empty($value) || strlen($value) != 12) {
                            $arResponse['message']['PHONE_NUMBER'] = 'Некорректный номер';
                            $arResponse['result'] = false;
                            $isError = true;
                        }

                    }
                    if (strpos($code, 'CODE') !== false) {
                        $arFields['CODE'][] = $value;
                    } else {
                        $arFields[$code] = $value;
                    }
                }
            }
            switch ($_REQUEST['id']) {
                case 'auth_pass':
                    $isError = false;
                    if (!isset($arFields['PHONE_NUMBER']) || empty($arFields['PHONE_NUMBER'])) {
                        $arResponse['message']['PHONE_NUMBER'] = 'Поле "Телефон" обязательно для заполнения';
                        $isError = true;
                    }
                    if (!isset($arFields['PASS']) || empty($arFields['PASS'])) {
                        $arResponse['message']['PASS'] = 'Поле "Пароль" обязательно для заполнения';
                        $isError = true;
                    }

                    if ($isError) {
                        $arResponse['result'] = false;
                    } else {
                        $rsUserPhoneAuth = UserPhoneAuthTable::getList(
                            array(
                                "filter" => array(
                                    "?PHONE_NUMBER" => $arFields['PHONE_NUMBER']
                                )
                            ));
                        $arUserPhoneAuth = $rsUserPhoneAuth->fetch();

                        if ($arUserPhoneAuth['USER_ID']) {
                            $arUser = CUser::GetList($by, $order, [
                                'ID' => $arUserPhoneAuth['USER_ID']
                            ], [])->Fetch();

                            $salt = substr($arUser['PASSWORD'], 0, (strlen($arUser['PASSWORD']) - 32));
                            $realPassword = substr($arUser['PASSWORD'], -32);
                            $password = md5($salt.$arFields['PASS']);

                            if ($password == $realPassword) {
                                $USER->Authorize($arUser['ID']);
                                $arResponse['result'] = true;
                            } else {
                                $arResponse['message']['MAIN'] = 'Неверный телефон или пароль!';
                                $arResponse['result'] = false;
                            }
                        } else {
                            $arResponse['message']['MAIN'] = 'Неверный телефон или пароль!';
                            $arResponse['result'] = false;
                        }
                    }

                    break;
                case 'auth':
                    $phone = UserPhoneAuthTable::normalizePhoneNumber($arFields['PHONE_NUMBER']);
                    if (strpos($phone, '+') === false) {
                        $phone = '+'.$phone;
                    }
                    $rsUser = UserPhoneAuthTable::getList(
                        array(
                            "filter" => array(
                                "?PHONE_NUMBER" => $arFields['PHONE_NUMBER']
                            )
                        ));
                    $arUser = $rsUser->fetch();
                    $isNewUser = false;
                    $userID = $arUser['USER_ID'];
                    if (!$userID) {
                        $pass = substr(md5("pass_" . $arFields['PHONE_NUMBER'] . rand(0, 99)), 0, 8);
                        $arResult = $USER->Register(checkPhone($arFields['PHONE_NUMBER']),
                            "", "", $pass, $pass, '', SITE_ID,
                            '', '', '', $phone);
                        $arResponse['add_new_user'] = true;
                        $isNewUser = true;
                        $userID = $arResult["ID"];
                    }

                    list($code, $phoneNumber) = CUser::GeneratePhoneCode($userID);

                    $us = new CUser();
                    $rs = $us->Update($userID, array('UF_HASHKEY' => $code));

                    $sms = new Bitrix\Main\Sms\Event(
                        'SMS_USER_CONFIRM_NUMBER',
                        [
                            "USER_PHONE" => $phoneNumber,
                            "CODE" => 'Код подтверждения: ' . $code,
                        ]
                    );
                    $sms->setSite('s1');
                    if ($isNewUser) {
                        $smsP = new Bitrix\Main\Sms\Event(
                            'SMS_USER_CONFIRM_NUMBER',
                            [
                                "USER_PHONE" => $phoneNumber,
                                "CODE" => 'Пароль для входа на сайт ducktex.ru: ' . $pass,
                            ]
                        );
                        $smsP->setSite('s1');
                        if ($smsTestMode == false) {
                            $smsP->send();
                        }
                    }

                    if ($smsTestMode) {
                        $arResponse['code'] = $code;
                        if ($isNewUser) {
                            $arResponse['pass'] = $pass;
                        }
                    }


                    if ($smsTestMode) {
                        $res = true;
                    } else {
                        $result = $sms->send();
                        $res = $result->isSuccess();
                    }

                    if ($arFields['REDIRECT_URL']) {
                        $arResponse['redirect_url'] = $arFields['REDIRECT_URL'];
                    }

                    if ($res) {
                        $arResponse['result'] = true;
                        $arResponse['phone'] = $phoneNumber;
                        $arResponse['user_id'] = $userID;
                        $arResponse['send_sms'] = true;
                        $arResponse['sign_data'] = PhoneAuth::signData([
                            'phoneNumber' => $phoneNumber
                        ]);
                    } else {
                        $arResponse['false'] = false;
                        $arResponse['send_sms'] = false;
                        $arResponse['message'] = $result->getErrors();
                    }

                    break;
                case 'auth_check_code':
                    if (isset($arFields['SIGN_DATA']) && !empty($arFields['SIGN_DATA'])) {
                        $params = PhoneAuth::extractData($arFields['SIGN_DATA']);
                        $verificationCode = implode('', $arFields['CODE']);
                        $basketItems = B24TechSiteHelper::getBasket();

                        if (strlen(trim($verificationCode)) == 6) {
                            $arUser = CUser::GetList($by, $order, ['ID' => $arFields['USER_ID']], ['SELECT' => ['UF_HASHKEY']])->Fetch();

                            if ($arUser['UF_HASHKEY'] == $verificationCode) {
                                $us = new CUser();
                                $rs = $us->Update($arFields['USER_ID'], array('UF_HASHKEY' => ''));

                                //$userId = CUser::VerifyPhoneCode($params['phoneNumber'], $verificationCode);

                                if ($arFields['REDIRECT_URL']) {
                                    $arResponse['redirect_url'] = $arFields['REDIRECT_URL'];
                                }

                                CModule::IncludeModule('sale');
                                $user = new CSaleUser();
                                $arFUser = $user->GetList(array('USER_ID' => $arFields['USER_ID']));

                                if ($arFUser) {
                                    $basket = new CSaleBasket();
                                    $result = $basket->DeleteAll($arFUser['ID'], False);
                                }

                                $USER->Authorize($arFields['USER_ID']);
                                $usr = new CUser();
                                $usr->Update($arFields['USER_ID'], array('UF_HASHKEY' => ''));
                                /*if ($basketItems['items']) {
                                    $products = B24TechSiteHelper::getBasket();

                                    if ($products['items']) {
                                        foreach ($products['items'] as $product) {
                                            CSaleBasket::Delete(intval($products['items'][$_REQUEST['id']]['ids']));
                                        }
                                    }

                                    foreach ($basketItems['items'] as $basketItem) {
                                        Add2BasketByProductID($basketItem['id'], $basketItem['quantity'], array(), array());
                                    }
                                }*/
                                $arResponse['result'] = true;

                            } else {
                                $arResponse['result'] = false;
                                $arResponse['message']['CODE'] = 'Неверный код';
                            }
                        } else {
                            $arResponse['result'] = false;
                            $arResponse['message']['CODE'] = 'Не корректная длина кода';
                        }
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message']['system'] = 'Ошибка: Отсутствует подпись';
                    }
                    break;
                case 'resend_code':
                    if (isset($arFields['SIGN_DATA']) && !empty($arFields['SIGN_DATA']) && isset($arFields['USER_ID']) && !empty($arFields['USER_ID']) ) {
                        $params = PhoneAuth::extractData($arFields['SIGN_DATA']);

                        if (strlen(trim($params['phoneNumber'])) > 0)
                        {
                            $arUserFields = CUser::GetList($by, $order, ['ID' => $arFields['USER_ID']], ['SELECT' => ['UF_HASHKEY']])->Fetch();
                            $arUserAuth = UserPhoneAuthTable::getList(
                                array(
                                    "filter" => array(
                                        "USER_ID" => $arFields['USER_ID']
                                    )
                                ))->fetch();

                            $sms = new Bitrix\Main\Sms\Event(
                                'SMS_USER_CONFIRM_NUMBER',
                                [
                                    "USER_PHONE" => $arUserAuth['PHONE_NUMBER'],
                                    "CODE" => 'Код подтверждения: '.$arUserFields['UF_HASHKEY'],
                                ]
                            );
                            $sms->setSite('s1');

                            if ($smsTestMode) {
                                $res = true;
                                $arResponse['code'] = $code;
                            } else {
                                $result = $sms->send();
                                $res = $result->isSuccess();
                            }

                            if ($res) {
                                $arResponse['result'] = true;
                                $arResponse['user_id'] = $arFields['USER_ID'];
                                $arResponse['send_sms'] = true;
                                $arResponse['sign_data'] = PhoneAuth::signData([
                                    'phoneNumber' => $phoneNumber
                                ]);
                            } else {
                                $arResponse['false'] = false;
                                $arResponse['send_sms'] = false;
                                $arResponse['message'] = $result->getErrors();
                            }

                        }
                    } else {
                        $arResponse['false'] = false;
                    }
                    break;
                case 'profile_edit':
                    $arUserProps = array();
                    $arUserOrderProps = array();
                    foreach ($arFields as $CODE => $PROP) {
                        switch ($CODE) {
                            case 'PERSONAL_BIRTHDAY':
                            case 'UF_INSTAGRAM':
                            case 'UF_FB':
                            case 'UF_VK':
                                $arUserProps[$CODE] = $PROP;
                                break;
                            case 'NEW_PASS':
                                if (strlen($PROP) >= 8 && $PROP == $arFields['CONFIRM_PASS']) {
                                    global $USER;
                                    $user = new CUser;
                                    $user->Update($USER->GetID(), [
                                        "PASSWORD"          => $PROP,
                                        "CONFIRM_PASSWORD"  => $arFields['CONFIRM_PASS']
                                    ]);

                                    $arUserFields = UserPhoneAuthTable::getList(
                                        [
                                            "filter" => [
                                                "USER_ID" => $USER->GetID()
                                            ]
                                        ]
                                    )->fetch();

                                    if ($arUserFields['PHONE_NUMBER']) {
                                        $smsEv = new Bitrix\Main\Sms\Event(
                                            'SMS_USER_CONFIRM_NUMBER',
                                            [
                                                "USER_PHONE" => $arUserFields['PHONE_NUMBER'],
                                                "CODE" => 'Новый пароль для входа ducktex.ru: '.$arFields['CONFIRM_PASS'],
                                            ]
                                        );
                                        $smsEv->send();
                                    }

                                }
                                break;
                            case 'FIO':
                                $arTmp = explode(' ', $PROP);
                                if (isset($arTmp[0])) {
                                    $arUserProps['LAST_NAME'] = $arTmp[0];
                                }
                                if (isset($arTmp[1])) {
                                    $arUserProps['NAME'] = $arTmp[1];
                                }
                                if (isset($arTmp[2])) {
                                    $arUserProps['SECOND_NAME'] = $arTmp[2];
                                }
                                $arUserOrderProps[$CODE] = $PROP;
                                break;
                            case 'PHONE':
                                if ($arFields['OLD_PHONE'] != $PROP) {
                                    $ph = checkPhone($PROP);
                                    $arUserProps['PHONE_NUMBER'] = $ph;
                                    $arUserOrderProps[$CODE] = $ph;
                                }
                                break;
                            case 'EMAIL':
                                $arUserOrderProps[$CODE] = $PROP;
                                $arUserProps[$CODE] = $PROP;
                                break;
                            case 'ADDRESS':
                                $arUserOrderProps[$CODE] = $PROP;
                                break;
                            case 'PERSON_TYPE_ID':
                                //$arUserOrderProps[$CODE] = $PROP;
                                break;
                        }
                    }

                    if (count($arUserProps) > 0) {
                        $us = new CUser();
                        $result = $us->Update($USER->GetID(), $arUserProps);
                    }
                    if (count($arUserOrderProps) > 0) {
                        $db_sales = CSaleOrderUserProps::GetList(
                            array(
                                'ID' => 'DESC'
                            ),
                            array(
                                "USER_ID" => $USER->GetID(),
                                "PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID']
                            )
                        );
                        $arUserProfile = $db_sales->Fetch();


                        if ($arUserProfile['ID']) {
                            $USER_PROPS_ID = $arUserProfile['ID'];
                        } else {
                            $arFields = array(
                                "NAME" => $arUserOrderProps['FIO'] ?: 'Профиль пользователя ID: '.$USER->GetID(),
                                "USER_ID" => $USER->GetID(),
                                "PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID']
                            );
                            $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);
                        }

                        if ($USER_PROPS_ID) {
                            foreach ($arUserOrderProps as $CODE => $VALUE) {
                                $rs = CSaleOrderUserPropsValue::GetList(
                                    array(),
                                    array(
                                        'USER_PROPS_ID' => $USER_PROPS_ID,
                                        "PROP_PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID'],
                                        'PROP_CODE' => $CODE
                                    ));
                                $PROP = $rs->Fetch();

                                if ($PROP['ID'] > 0) {
                                    if ($PROP['VALUE'] != $VALUE) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "ORDER_PROPS_ID" => $PROP['ORDER_PROPS_ID'],
                                            "NAME" => $PROP['NAME'],
                                            "VALUE" => $VALUE
                                        );

                                        CSaleOrderUserPropsValue::Update($PROP['ID'], $arFieldsProps);
                                    }

                                } else {
                                    $rs = CSaleOrderUserPropsValue::GetList(
                                        array(),
                                        array(
                                            "PROP_PERSON_TYPE_ID" => $arFields['PERSON_TYPE_ID'],
                                            'PROP_CODE' => $CODE
                                        ));
                                    $PROP = $rs->Fetch();

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "ORDER_PROPS_ID" => $PROP['ORDER_PROPS_ID'],
                                        "NAME" => $PROP['NAME'],
                                        "VALUE" => $VALUE
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                            }
                        }
                    }

                    if ($result) {
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message'] = $us->LAST_ERROR;
                        $arResponse['fields'] =  $arFields;
                        $arResponse['request'] =  $_REQUEST['data'];
                    }
                    break;
                case 'set_coupon':
                    if (CCatalogDiscountCoupon::SetCoupon($_REQUEST['coupon_code'])) {
                        CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['result'] = false;
                        $arResponse['message']['coupon_code'] = 'Error';
                    }

                    break;
                case 'subscribe':
                    if (check_email($_REQUEST['email'])) {
                        $list = array();
                        foreach (\Bitrix\Sender\Subscription::getMailingList(array('IS_PUBLIC' => 'Y')) as $l) {
                            $list[] = $l['ID'];
                        }
                        \Bitrix\Sender\Subscription::add($_REQUEST['email'], $list, SITE_ID);
                        $arResponse['result'] = true;
                    } else {
                        $arResponse['message'] = 'Некорректный email';
                        $arResponse['result'] = false;
                    }
                    break;
            }

            break;
        case 'clear_compare':
            if(empty($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID])) {
                $arResponse['result'] = false;
            } else {
                unset($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]);
                $arResponse['result'] = true;
            }
            break;
        case 'add_compare':
            $isAdd = true;
            if(!empty($_SESSION["CATALOG_COMPARE_LIST"]) && !empty($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]) && array_key_exists($_REQUEST["id"], $_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"])){
                unset($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"][$_REQUEST["id"]]);
                $isAdd = false;
            }
            else{
                $_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"][$_REQUEST["id"]] = CIBlockElement::GetByID($_REQUEST["id"])->Fetch();
            }
            $arResponse['result'] = true;
            $arResponse['count'] = count($_SESSION["CATALOG_COMPARE_LIST"][IBLOCK_CATALOG_ID]["ITEMS"]);
            $arResponse['isAdd'] = $isAdd;
            break;
        case 'remove_basket':
            $products = B24TechSiteHelper::getBasket();

            $arResponse['result'] = CSaleBasket::Delete(intval($products['items'][$_REQUEST['id']]['ids']));
            break;
        case 'update_basket':
            $products = B24TechSiteHelper::getBasket();

            $arFields = array(
                "QUANTITY" => $_REQUEST['quantity']
            );
            $result = CSaleBasket::Update(intval($products['items'][$_REQUEST['id']]['ids']), $arFields);
            if ($result) {
                $arResponse['result'] = true;
            } else {
                $arResponse['result'] = false;
                if($ex = $APPLICATION->GetException()) {
                    $arResponse['message'] = $ex->GetString();
                }
            }
            $arResponse['result'] = true;
            break;
        case 'add_basket':
            $quantity = $_REQUEST['quantity'] ?: 0.1;
            $id = $_REQUEST['id'];
            $rs = Add2BasketByProductID($id, $quantity, array(), array());
            if ($rs) {
                $arResponse['result'] = true;
                $arResponse['basket'] = B24TechSiteHelper::getBasket();
            }
            break;
        case 'add_products_basket':
            foreach ($_REQUEST['products'] as $product) {
                Add2BasketByProductID($product['id'], $product['quantity'], array(), array());
            }
            $arResponse['result'] = true;
            $arResponse['basket'] = B24TechSiteHelper::getBasket();
            break;
        case 'add_favorites':
            $isAdd = false;
            if(!$USER->IsAuthorized()) {
                $arElements = unserialize($APPLICATION->get_cookie('favorites'));
                if(!in_array($_REQUEST['id'], $arElements)) {
                    $arElements[] = $_REQUEST['id'];
                    $isAdd = true;
                } else {
                    $key = array_search($_REQUEST['id'], $arElements);
                    unset($arElements[$key]);
                }
                $APPLICATION->set_cookie("favorites", serialize($arElements));
            } else {
                $rsUser = CUser::GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();
                $arElements = $arUser['UF_FAVORITES'] ?: array();
                if(!in_array($_REQUEST['id'], $arElements)) {
                    $arElements[] = $_REQUEST['id'];
                    $isAdd = true;
                } else {
                    $key = array_search($_REQUEST['id'], $arElements);
                    unset($arElements[$key]);
                }

                $USER->Update($USER->GetID(), Array("UF_FAVORITES"=>$arElements));
            }
            $arResponse['count'] = count($arElements);
            $arResponse['result'] = true;
            $arResponse['isAdd'] = $isAdd;
            break;
        case 'clear_favorites':
            break;
    }
    echo json_encode($arResponse);
    die();
}

