<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <div class="order-info-name">1. Ваши данные</div>
    <div class="order-info-item">
        <div class="title">Выберите тип плательщика</div>
        <?foreach($arResult["PERSON_TYPE"] as $v) {?>
            <label class="radio-box" for="PERSON_TYPE_<?=$v["ID"]?>">
                <input type="radio"
                       id="PERSON_TYPE_<?=$v["ID"]?>"
                       name="PERSON_TYPE"
                       value="<?=$v["ID"]?>" <?=$v["CHECKED"]=="Y" ? 'checked="checked"' : ''?>
                       onClick="submitForm()">
                <span class="checkmark"></span>
                <?=$v["NAME"]?>
            </label>
        <?}?>
        <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");?>
    </div>
    <input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>">