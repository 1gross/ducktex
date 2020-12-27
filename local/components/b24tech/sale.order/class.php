<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 * Date: 02.04.2020
 * Time: 12:35
 */


use Bitrix\Main\Loader;


class SaleOrder extends CBitrixComponent
{
    public function prepareData()
    {
        $this->arResult['PERSON_TYPE'] = $this->getPersonTypes();
        $this->arResult['BONUS'] = $this->getBonus();
        $this->arResult['DELIVERY'] = $this->getDelivery();
        $this->getPrice();
    }

    public function getOrderProps($personTypeId) {
        $arProps = array();
        $db_props = CSaleOrderProps::GetList(
            array("SORT" => "ASC"),
            array(
                "PERSON_TYPE_ID" => $personTypeId,
                "USER_PROPS" => "Y"
            ),
            false,
            false,
            array()
        );

        while ($props = $db_props->Fetch())
        {
            $arProps[$props['ID']] = $props;
        }
        return $arProps;
    }

    public function getDelivery()
    {
        $arResult = array();
        $db_dtype = CSaleDelivery::GetList(
            array(
                "SORT" => "ASC",
                "NAME" => "ASC"
            ),
            array(
                //"SITE_ID" => SITE_ID,
                /*"+<=WEIGHT_FROM" => $ORDER_WEIGHT,
                "+>=WEIGHT_TO" => $ORDER_WEIGHT,
                "+<=ORDER_PRICE_FROM" => $ORDER_PRICE,
                "+>=ORDER_PRICE_TO" => $ORDER_PRICE,*/
                "ACTIVE" => "Y",
            ),
            false,
            false,
            array()
        );

        while ($ar_dtype = $db_dtype->Fetch()){
            $arResult[] = $ar_dtype;
        }
        foreach ($this->getDeliveryHandler() as $arItem) {
            $arResult[] = $arItem;
        }
        return $arResult;
    }

    public function getBasketItems()
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

        while ($item = $dbRes->fetch())
        {
            $arResult[] = $item;
        }
        return $arResult;
    }
    public function getBonus()
    {
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            \Bitrix\Main\Context::getCurrent()->getSite()
        );
        CModule::IncludeModule('logictim.balls');

        return cHelperCalc::CartBonus($this->getBasketItems());
    }

    public function getDeliveryHandler()
    {
        $arResult = array();
        $db_dtype = CSaleDeliveryHandler::GetList(
            array(
                "SORT" => "ASC",
                "NAME" => "ASC"
            ),
            array(
                "ACTIVE" => "Y",
            )
        );

        while ($ar_dtype = $db_dtype->Fetch()){
            $arResult[] = $ar_dtype;
        }
        return $arResult;
    }

    public function getPaySystems($personTypeId)
    {
        $arResult = array();
        $db_ptype = CSalePaySystem::GetList($arOrder =
            Array("SORT"=>"ASC", "PSA_NAME"=>"ASC"),
            Array("LID"=>SITE_ID, "CURRENCY"=>"RUB", "ACTIVE"=>"Y", "PERSON_TYPE_ID"=>$personTypeId));
        while ($ptype = $db_ptype->Fetch())
        {
            $arResult[$ptype['ID']] = $ptype;
        }
        return $arResult;
    }
    public function getPersonTypes()
    {
        $arResult = array();

        $db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), Array("LID"=>SITE_ID));
        while ($ptype = $db_ptype->Fetch())
        {
            $ptype['PROPS'] = $this->getOrderProps($ptype['ID']);
            $ptype['PAY_SYSTEMS'] = $this->getPaySystems($ptype['ID']);
            $arResult[$ptype['ID']] = $ptype;
        }
        return $arResult;
    }

    public function getPrice()
    {
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            \Bitrix\Main\Context::getCurrent()->getSite()
        );
        $fullPrice = $basket->getPrice();
        $price = $basket->getBasePrice();

        if ($fullPrice != $price) {
            $this->arResult['DISCOUNT_PRICE'] = $fullPrice - $price;
        }
        $this->arResult['FULL_PRICE'] = $fullPrice;
        $this->arResult['PRICE'] = $price;
        $this->arResult['DELIVERY_PRICE'] = $this->getDeliveryPrice($this->arResult['DELIVERY'][0]['ID']);
    }

    public function getDeliveryPrice($id) {
        return CSaleDelivery::GetByID($id);
    }

    public function executeComponent()
    {
        $this->prepareData();

        if (isset($_REQUEST['action']) && strlen($_REQUEST['action']) > 0 && check_bitrix_sessid()) {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();

            $arResponse = array();

            $error = false;
            switch ($_REQUEST['action']) {
                case 'order':
                    $personTypeID = $_REQUEST['fields']['person_type'] == 'on' ? 2 : 1;
                    foreach ($this->arResult['PERSON_TYPE'][$personTypeID]['PROPS'] as $PROP) {
                        //dump($PROP['TYPE']);
                        if ($PROP['REQUIED'] == 'Y') {
                            if (isset($_REQUEST['props'][$PROP['ID']]) && strlen($_REQUEST['props'][$PROP['ID']]) > 2) {

                            } else {
                                $error = true;
                                $arResponse['message']['props'][$PROP['ID']] = 'Поле обязательно для заполнения';
                            }
                        }
                    }
                    break;
                default:

                    $this->includeComponentTemplate();
            }
            $arResponse['result'] = !$error;
            echo json_encode($arResponse);
            die();
        } else {
            $this->includeComponentTemplate();
        }

    }
}