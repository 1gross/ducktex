<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle('Спасибо за заказ!');
$APPLICATION->AddChainItem('Спасибо за заказ!', $APPLICATION->GetCurPage(false));
?>
    <section class="order-complete">
        <div class="wrapper">
            <h1>ЗАКАЗ СФОРМИРОВАН №<?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?></h1>

            <p>
                <?if ($arResult['PAY_SYSTEM']['DESCRIPTION']) {
                    echo $arResult['PAY_SYSTEM']['DESCRIPTION'];
                } else {?>
                    Спасибо за заказ, мы с вами свяжемся в ближайшее время!
                <?}?>
            </p>

            <div class="order-complete-block">
                <table class="order-complete-table">
                    <thead>
                    <tr>
                        <th>Номер заказа</th>
                        <th>Дата</th>
                        <th>Время заказа</th>
                        <th>Статус</th>
                        <?if (!empty($arResult["PAY_SYSTEM"])) {?>
                            <th>Оплата</th>
                        <?}?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>№<?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?></td>
                        <td><?=explode(' ', $arResult["ORDER"]["DATE_INSERT"])[0]?></td>
                        <td><?=explode(' ', $arResult["ORDER"]["DATE_INSERT"])[1]?></td>
                        <td><?=$arResult["ORDER"]['PAYED'] == 'Y' ? 'заказ оплачен' : 'принят, ожидается оплата'?></td>
                        <?if (!empty($arResult["PAY_SYSTEM"])) {?>
                            <td><?=$arResult["PAY_SYSTEM"]['NAME']?></td>
                        <?}?>
                    </tr>
                    </tbody>
                </table>
                <div class="order-complete-footer">
                    <a href="<?=SITE_DIR?>catalog/" class="btn outline big">Вернуться к покупкам</a>
                    <div class="notif">
                        Вы можете следить за выполнением своего заказа в <a href="<?=SITE_DIR?>personal/">личном кабинете в разделе Заказы</a>.
                    </div>
                </div>
            </div>
        </div>
    </section>
<?
if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
{
    $service = \Bitrix\Sale\PaySystem\Manager::getObjectById($arResult["ORDER"]['PAY_SYSTEM_ID']);

    if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
    {
        ?>
        <script language="JavaScript">
            window.location ='<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>&PAYMENT_ID=<?=$arResult["ORDER"]["PAYMENT_ID"]?>';

        </script>
        <?
        /*
         *  // window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>&PAYMENT_ID=<?=$arResult['ORDER']["PAYMENT_ID"]?>');
         */
    }
    else
    {
        if ($service)
        {
            /** @var \Bitrix\Sale\Order $order */
            $order = \Bitrix\Sale\Order::load($arResult["ORDER_ID"]);

            /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
            $paymentCollection = $order->getPaymentCollection();

            /** @var \Bitrix\Sale\Payment $payment */
            foreach ($paymentCollection as $payment)
            {
                if (!$payment->isInner())
                {
                    $context = \Bitrix\Main\Application::getInstance()->getContext();
                    //$service->initiatePay($payment, $context->getRequest());
                    break;
                }
            }
        }
    }
}
?>