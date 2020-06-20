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
<div class="order-check">
    <div class="order-check-block">
        <div class="title">ВАШ ЗАКАЗ</div>
        <div class="order-check-item">
            <div class="order-check-title custom_t1 itog">Сумма заказа:</div>
            <div class="order-check-sum custom_t2 price"><?=$arResult['PRICE_WITHOUT_DISCOUNT']?></div>
        </div>
        <?if (isset($arResult["DISCOUNT_PRICE"]) && $arResult["DISCOUNT_PRICE"] > 0) {?>
            <div class="order-check-item">
                <div class="order-check-title custom_t1">Скидка:</div>
                <div class="order-check-sum custom_t2"><?=$arResult["DISCOUNT_PRICE_FORMATED"]?></div>
            </div>
        <?}?>
        <div class="order-check-item">
            <div class="order-check-title custom_t1"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></div>
            <div class="order-check-sum custom_t2"><?=$deliveryPrice?></div>
        </div>
        <?if ($_REQUEST['USE_BONUS'] == 'on' && $arResult["MAX_BONUS"] > 0) {?>
            <div class="order-check-item bonus-order-sum">
                <div class="order-check-title">Оплачено бонусом:</div>
                <div class="order-check-sum"><?=round($arResult["MAX_BONUS"], 2)?> руб.</div>
            </div>
        <?}?>
        <div class="order-check-footer">
            <?if ($arResult["MAX_BONUS"] > 0) {?>
                <div class="order-check-item bonus-order-sum">
                    <div class="order-check-title">Бонусы:</div>
                    <div class="order-check-sum">
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
                    </div>
                </div>
            <?}?>
            <div class="order-check-gray">
                <?if ($arResult['ADD_BONUS'] > 0 && $_REQUEST['USE_BONUS'] != 'on') {?>
                    <div class="order-check-item bonus-order-sum">
                        <div class="order-check-title">БОНУС ЗА ЗАКАЗ:</div>
                        <div class="order-check-sum"><?=round($arResult["ADD_BONUS"], 2)?> руб.</div>
                    </div>
                <?}?>
                <div class="order-check-item">
                    <div class="order-check-title">ИТОГО:</div>
                    <div class="order-check-sum"><?=$_REQUEST['USE_BONUS'] == 'on' && $arResult["MAX_BONUS"] > 0 ? $arResult["ORDER_TOTAL_PRICE"] - $arResult["MAX_BONUS"] : $arResult["ORDER_TOTAL_PRICE"]?> руб.</div>
                </div>

            </div>
            <button class="btn blue order-submit"  onclick="submitForm('Y'); return false;" id="ORDER_CONFIRM_BUTTON" disabled style="<?=$USER->IsAuthorized() ? '' : 'display: none'?>">оформить заказ</button>
            <button class="btn blue order-submit js-init-action" data-action="show_modal" data-modal="#sign_basket" onclick="return false;" id="" disabled style="<?=$USER->IsAuthorized() ? 'display: none' : ''?>">оформить заказ</button>
            <div class="politic">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c <a href="/help/polzovatelskoe-soglashenie/">политикой конфиденциальности</a></div>
        </div>
    </div>
</div>