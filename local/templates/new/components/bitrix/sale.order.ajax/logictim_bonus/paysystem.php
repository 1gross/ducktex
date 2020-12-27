<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<script type="text/javascript">
    function changePaySystem(param)
    {
        if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
        {
            if (param == 'account')
            {
                if (BX("PAY_CURRENT_ACCOUNT"))
                {
                    BX("PAY_CURRENT_ACCOUNT").checked = true;
                    BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                    BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

                    // deselect all other
                    var el = document.getElementsByName("PAY_SYSTEM_ID");
                    for(var i=0; i<el.length; i++)
                        el[i].checked = false;
                }
            }
            else
            {
                BX("PAY_CURRENT_ACCOUNT").checked = false;
                BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
            }
        }
        else if (BX("account_only") && BX("account_only").value == 'N')
        {
            if (param == 'account')
            {
                if (BX("PAY_CURRENT_ACCOUNT"))
                {
                    BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

                    if (BX("PAY_CURRENT_ACCOUNT").checked)
                    {
                        BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                        BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                    else
                    {
                        BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                        BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                }
            }
        }

        submitForm();
    }
</script>

<div class="order-info-name">2. Выберите способ оплаты</div>
<div class="order-info-item">
    <?uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

    foreach($arResult["PAY_SYSTEM"] as $arPaySystem) {
        if ($arPaySystem["CODE"] == 'LOGICTIM_PAYMENT_BONUS') {
            continue;
        }
        ?>
        <label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
               onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();"
               class="radio-box">
            <input type="radio"
                   value="<?=$arPaySystem["ID"]?>"
                   id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                   name="PAY_SYSTEM_ID"
                   onclick="changePaySystem();"
                   <?=$arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") ? 'checked="checked"' : ''?>
            >
            <span class="checkmark"></span>
            <?=$arPaySystem['NAME']?>
        </label>
        <?
    }
    ?>
</div>

<? //-------LOGICTIM BONUS FEILD-------//
$this->SetViewTarget('bonus_pay');
if(isset($arResult["PAY_BONUS"]) && $arResult["PAY_BONUS"] >= 0  && $arResult["MAX_BONUS"] > 0) {?>
    <div class="bx_block w100 vertical">
        <div class="bx_description">
            <strong><?=COption::GetOptionString("logictim.balls", "HAVE_BONUS_TEXT", 'Have bonus')?> <?=$arResult["USER_BONUS"]?></strong>
            <span>
                <?=COption::GetOptionString("logictim.balls", "CAN_BONUS_TEXT", 'Can use bonus')?>
                <br>
                <? if($arResult["MIN_BONUS"] > 0 || $arResult["MAX_BONUS"] > 0) {?>
                    (
                    <?	if($arResult["MIN_BONUS"] > 0) {
                            echo COption::GetOptionString("logictim.balls", "MIN_BONUS_TEXT", 'Min use bonus').$arResult["MIN_BONUS"];
                        }
                        if($arResult["MIN_BONUS"] > 0 && $arResult["MAX_BONUS"] > 0) {
                            echo ', ';
                        }
                        if($arResult["MAX_BONUS"] > 0) {
                            echo COption::GetOptionString("logictim.balls", "MAX_BONUS_TEXT", 'Max use bonus').$arResult["MAX_BONUS"];
                        }?>
                     )
               <? } ?>
            </span>
        </div>
        <div class="bonus_left">
            <strong><?=COption::GetOptionString("logictim.balls", "PAY_BONUS_TEXT", 'Pay from bonus')?></strong>
            <input type="text" onchange="submitForm();" maxlength="250" size="0" value="<?=$arResult["PAY_BONUS"]?>" name="ORDER_PROP_<?=$arResult["ORDER_PROP_PAYMENT_BONUS_ID"]?>" id="ORDER_PROP_<?=$arResult["ORDER_PROP_PAYMENT_BONUS_ID"]?>">
        </div>
    </div>
<? }
$this->EndViewTarget();
//-------LOGICTIM BONUS FEILD-------//?>