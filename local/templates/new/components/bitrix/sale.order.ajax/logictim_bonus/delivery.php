<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<script type="text/javascript">
    function fShowStore(id, showImages, formWidth, siteId)
    {
        var strUrl = '<?=$templateFolder?>' + '/map.php';
        var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId;

        var storeForm = new BX.CDialog({
            'title': '<?=GetMessage('SOA_ORDER_GIVE')?>',
            head: '',
            'content_url': strUrl,
            'content_post': strUrlPost,
            'width': formWidth,
            'height':450,
            'resizable':false,
            'draggable':false
        });

        var button = [
            {
                title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
                id: 'crmOk',
                'action': function ()
                {
                    GetBuyerStore();
                    BX.WindowManager.Get().Close();
                }
            },
            BX.CDialog.btnCancel
        ];
        storeForm.ClearButtons();
        storeForm.SetButtons(button);
        storeForm.Show();
    }

    function GetBuyerStore()
    {
        BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
        //BX('ORDER_DESCRIPTION').value = '<?=GetMessage("SOA_ORDER_GIVE_TITLE")?>: '+BX('POPUP_STORE_NAME').value;
        BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
        BX.show(BX('select_store'));
    }

    function showExtraParamsDialog(deliveryId)
    {
        var strUrl = '<?=$templateFolder?>' + '/delivery_extra_params.php';
        var formName = 'extra_params_form';
        var strUrlPost = 'deliveryId=' + deliveryId + '&formName=' + formName;

        if(window.BX.SaleDeliveryExtraParams)
        {
            for(var i in window.BX.SaleDeliveryExtraParams)
            {
                strUrlPost += '&'+encodeURI(i)+'='+encodeURI(window.BX.SaleDeliveryExtraParams[i]);
            }
        }

        var paramsDialog = new BX.CDialog({
            'title': '<?=GetMessage('SOA_ORDER_DELIVERY_EXTRA_PARAMS')?>',
            head: '',
            'content_url': strUrl,
            'content_post': strUrlPost,
            'width': 500,
            'height':200,
            'resizable':true,
            'draggable':false
        });

        var button = [
            {
                title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
                id: 'saleDeliveryExtraParamsOk',
                'action': function ()
                {
                    insertParamsToForm(deliveryId, formName);
                    BX.WindowManager.Get().Close();
                }
            },
            BX.CDialog.btnCancel
        ];

        paramsDialog.ClearButtons();
        paramsDialog.SetButtons(button);
        //paramsDialog.adjustSizeEx();
        paramsDialog.Show();
    }

    function insertParamsToForm(deliveryId, paramsFormName)
    {
        var orderForm = BX("ORDER_FORM"),
            paramsForm = BX(paramsFormName);
        wrapDivId = deliveryId + "_extra_params";

        var wrapDiv = BX(wrapDivId);
        window.BX.SaleDeliveryExtraParams = {};

        if(wrapDiv)
            wrapDiv.parentNode.removeChild(wrapDiv);

        wrapDiv = BX.create('div', {props: { id: wrapDivId}});

        for(var i = paramsForm.elements.length-1; i >= 0; i--)
        {
            var input = BX.create('input', {
                    props: {
                        type: 'hidden',
                        name: 'DELIVERY_EXTRA['+deliveryId+']['+paramsForm.elements[i].name+']',
                        value: paramsForm.elements[i].value
                    }
                }
            );

            window.BX.SaleDeliveryExtraParams[paramsForm.elements[i].name] = paramsForm.elements[i].value;

            wrapDiv.appendChild(input);
        }

        orderForm.appendChild(wrapDiv);

        BX.onCustomEvent('onSaleDeliveryGetExtraParams',[window.BX.SaleDeliveryExtraParams]);
    }

    if(typeof submitForm === 'function')
        BX.addCustomEvent('onDeliveryExtraServiceValueChange', function(){ submitForm(); });

</script>

<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult["BUYER_STORE"]?>">
<div class="order-info-name">3. Выберите способ получения</div>
<div class="order-info-item">
    <?foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery){
        if ($arDelivery["ISNEEDEXTRAINFO"] == "Y") {
            $extraParams = "showExtraParamsDialog('".$delivery_id."');";
        } else {
            $extraParams = "";
        }

        if (count($arDelivery["STORE"]) > 0) {
            $clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."')\";";
        } else {
            $clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true;".$extraParams."submitForm();\"";
        }
        ?>
        <label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" class="radio-box" <?=$clickHandler?>>
            <input type="radio"
                   id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"
                   name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>"
                   onclick="submitForm();"
                   value="<?= $arDelivery["ID"] ?>"
                <?=$arDelivery['CHECKED'] == 'Y' ? 'checked="checked"' : ''?>
            >
            <span class="checkmark"></span>
            <?=$arDelivery['NAME']?>
            <?if ($arDelivery['ID'] == 42) {?>
                (<button class="SDEK_selectPVZ" onclick="IPOLSDEK_pvz.selectPVZ('42','PVZ'); return false;">выбрать место получения</button>)
            <?}?>

            <div class="bx_description">
                <span class="bx_result_price"></span>
            </div>

        </label>
    <?}?>
</div>
<div class="order-info-item">
    <div class="title">Комментарий к заказу</div>
    <div class="form-item">
        <textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" cols="30" rows="10"></textarea>
    </div>
</div>