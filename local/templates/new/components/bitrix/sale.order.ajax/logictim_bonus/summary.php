<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if ($arResult["DELIVERY"][2]['CHECKED'] == 'Y') {
    $deliveryPrice = '0 руб.';
} elseif (strlen($arResult["DELIVERY_PRICE"]) > 0) {
    $deliveryPrice = $arResult["DELIVERY_PRICE_FORMATED"];
} else {
    $deliveryPrice = 'Не указано';
}

?>
<div class="bx_ordercart order-check-block">
    <div class="title">Ваш заказ</div>
	<div class="bx_ordercart_order_pay">
        <?if (floatval($arResult['ORDER_WEIGHT']) > 0) {?>
            <div class="order-check-item">
                <div class="order-check-title custom_t1"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></div>
                <div class="order-check-sum custom_t2 price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></div>
            </div>
        <?}?>
        <div class="order-check-item">
            <div class="order-check-title custom_t1 itog">Сумма заказа:</div>
            <div class="order-check-sum custom_t2 price"><?=$arResult['PRICE_WITHOUT_DISCOUNT']?></div>
        </div>
        <?if (doubleval($arResult["DISCOUNT_PRICE"]) > 0) {?>
            <div class="order-check-item">
                <div class="order-check-title custom_t1"></div>
                <div class="order-check-sum custom_t2"><?=$arResult["DISCOUNT_PRICE_FORMATED"]?></div>
            </div>
        <?}?>
        <?if (!empty($arResult["TAX_LIST"])) {?>
            <?foreach ($arResult['TAX_LIST'] as $val) {?>
                <div class="order-check-item">
                    <div class="order-check-title custom_t1"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?></div>
                    <div class="order-check-sum custom_t2"><?=$val["VALUE_MONEY_FORMATED"]?></div>
                </div>
            <?}?>
        <?}?>

        <div class="order-check-item">
            <div class="order-check-title custom_t1"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></div>
            <div class="order-check-sum custom_t2"><?=$deliveryPrice?></div>
        </div>

        <?if (COption::GetOptionString("logictim.balls", "ORDER_TOTAL_BONUS", 'Y') == 'Y' && isset($arResult["PAY_BONUS"]) && $arResult["PAY_BONUS"] > 0) {?>
            <div class="order-check-item">
                <div class="order-check-title custom_t1 fwb"><?=COption::GetOptionString("logictim.balls", "TEXT_BONUS_PAY", 'pay from bonus:')?></div>
                <div class="order-check-sum custom_t2 fwb"><?=$arResult["PAY_BONUS_FORMATED"]?> руб.</div>
            </div>
        <?}?>


        <?if ($arResult["MAX_BONUS"] > 0) {?>
            <div class="order-check-item">
                <div class="order-check-title custom_t1 fwb">Максимальное кол-во бонусов для списания</div>
                <div class="order-check-sum custom_t2 fwb2"><?=$arResult["MAX_BONUS"]?> руб.</div>
            </div>
        <?}?>
        <hr>
        <div class="order-check-item">
            <div class="order-check-title custom_t1 fwb"><?=COption::GetOptionString("logictim.balls", "TEXT_BONUS_BALLS", 'bonus:')?></div>
            <div class="order-check-sum custom_t2 fwb">
                <div class="switch-box">
                    <input type="checkbox"
                           onclick="submitForm();"
                           name="USE_BONUS"
                           class="switch"
                           <?=$_REQUEST['USE_BONUS'] == 'on' ? 'checked' : ''?>
                    >
                    <span class="first">Начислить</span>
                    <span class="second">Списать</span>
                </div>
                <?if ($_REQUEST['USE_BONUS'] == 'on') {?>
                    <input type="hidden"
                           onchange="submitForm();"
                           value="<?=$arResult["MAX_BONUS"]?>"
                           name="ORDER_PROP_<?=$arResult["ORDER_PROP_PAYMENT_BONUS_ID"]?>"
                           id="ORDER_PROP_<?=$arResult["ORDER_PROP_PAYMENT_BONUS_ID"]?>">
                    <?//$APPLICATION->ShowViewContent('bonus_pay')?>
                <?}?>
            </div>

        </div>

        <div class="order-check-footer">
            <div class="order-check-item small">
                <div class="order-check-title"><?=$_REQUEST['USE_BONUS'] == 'on' ? 'БОНУСОВ К СПИСАНИЮ' : 'БОНУСОВ К НАЧИСЛЕНИЮ'?>:</div>
                <div class="order-check-sum"><?=$_REQUEST['USE_BONUS'] == 'on' ? '- '.$arResult["MAX_BONUS"] : $arResult["ADD_BONUS"]?> руб.</div>
            </div>
            <div class="order-check-item">
                <div class="order-check-title">ИТОГО:</div>
                <div class="order-check-sum"><?=$_REQUEST['USE_BONUS'] == 'on' && $arResult["MAX_BONUS"] > 0 ? $arResult["ORDER_TOTAL_PRICE"] - $arResult["MAX_BONUS"] : $arResult["ORDER_TOTAL_PRICE"]?> руб.</div>
            </div>
            <button class="btn blue"  onclick="submitForm('Y'); return false;" id="ORDER_CONFIRM_BUTTON" >оформить заказ</button>
            <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c политикой конфиденциальности</div>
        </div>
	</div>
</div>
