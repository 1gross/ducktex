<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>



	<script>
        BX.ready(function(){
            var arBonus_<?=$arParams["RAND"]?> = <?=CUtil::PhpToJSObject($arResult["ITEMS_BONUS"], false, true)?>;
            for(id in arBonus_<?=$arParams["RAND"]?>) {
                var item = arBonus_<?=$arParams["RAND"]?>[id];
                //console.log(item);
                if(BX('lb_ajax_'+id) && item.VIEW_BONUS > 0)
                    BX.adjust(BX('lb_ajax_'+id), {text: '+'+item.VIEW_BONUS+' '+'<?=COption::GetOptionString("logictim.balls", "TEXT_BONUS_FOR_ITEM", '')?>'});
            }
        
        });
    </script>

