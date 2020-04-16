<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$frame = $this->createFrame()->begin('')?>
<?
$bLeftAndRight = false;
if(is_array($arResult["QUESTIONS"])){
	foreach($arResult["QUESTIONS"] as $arQuestion){
		if($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left'){
			$bLeftAndRight = true;
			break;
		}
	}
}
?>
    <h3><?=$arResult["FORM_TITLE"]?></h3>
    <form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data" class="form-feedback">
        <?=bitrix_sessid_post();?>
        <input type="hidden" name="WEB_FORM_ID" value="<?=$arResult['arForm']['ID']?>">
        <?if($arResult["isFormErrors"] == "Y" || strlen($arResult["FORM_NOTE"])) {?>
            <div class="form_result <?=($arResult["isFormErrors"] == "Y" ? 'error' : 'success')?>">
                <?if($arResult["isFormErrors"] == "Y") {?>
                    <?=$arResult["FORM_ERRORS_TEXT"]?>
                <? } else {?>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            if(arMShopOptions['COUNTERS']['USE_FORMS_GOALS'] !== 'NONE'){
                                var eventdata = {goal: 'goal_webform_success' + (arMShopOptions['COUNTERS']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arParams['WEB_FORM_ID']?>'), params: <?=CUtil::PhpToJSObject($arParams, false)?>, result: <?=CUtil::PhpToJSObject($arResult, false)?>};
                                BX.onCustomEvent('onCounterGoals', [eventdata]);
                            }
                        });
                    </script>
                    <?$successNoteFile = SITE_DIR."include/form/success_{$arResult["arForm"]["SID"]}.php";?>
                    <?if(file_exists($_SERVER["DOCUMENT_ROOT"].$successNoteFile)) {?>
                        <?$APPLICATION->IncludeFile($successNoteFile, array(), array("MODE" => "html", "NAME" => "Form success note"));?>
                    <? } else {?>
                        <?=GetMessage("FORM_SUCCESS");?>
                    <?}?>
                <?}?>
            </div>
        <?}?>
        <?if(is_array($arResult["QUESTIONS"])) {?>
            <?if(!$bLeftAndRight) {?>
                <?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {?>
                    <div class="form-item">
                        <?drawCustomFormField($FIELD_SID, $arQuestion, $arResult['FORM_ERRORS']);?>
                    </div>
                <?}?>
            <?} else {?>
                <div class="left">
                    <?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {?>
                        <?if($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left') {?>
                            <div class="form-item">
                                <?drawCustomFormField($FIELD_SID, $arQuestion, $arResult['FORM_ERRORS']);?>
                            </div>
                        <?}?>
                    <?}?>
                    <?if($arResult["isUseCaptcha"] == "Y") {?>
                        <div class="form-item form-control captcha-row clearfix">
                            <div class="captcha_image">
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" border="0" />
                                <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
                                <div class="captcha_reload"></div>
                            </div>
                            <div class="captcha_input">
                                <input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
                            </div>
                        </div>
                    <?}?>
                    <input type="submit" class="btn blue small"  name="web_form_submit" value="Отправить">
                </div>
                <div class="right">
                    <?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {?>
                        <?if($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] != 'left') {?>
                            <div class="form-item">
                                <?drawCustomFormField($FIELD_SID, $arQuestion, $arResult['FORM_ERRORS']);?>
                            </div>
                        <?}?>
                    <?}?>
                </div>
            <?}?>
        <?}?>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').validate({
                highlight: function( element ){
                    $(element).parent().addClass('error');
                },
                unhighlight: function( element ){
                    $(element).parent().removeClass('error');
                },
                submitHandler: function( form ){
                    if( $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').valid() ){
                        /*form.submit();
                        setTimeout(function() {
                            $(form).find('button[type="submit"]').attr("disabled", "disabled");
                        }, 300);*/
                        var eventdata = {type: 'form_submit', form: form, form_name: '<?=$arResult["arForm"]["VARNAME"]?>'};
                        BX.onCustomEvent('onSubmitForm', [eventdata]);
                    }
                },
                errorPlacement: function( error, element ){
                    error.insertBefore(element);
                },
                messages:{
                    licenses_inline: {
                        required : BX.message('JS_REQUIRED_LICENSES')
                    }
                }
            });

            if(arMShopOptions['THEME']['PHONE_MASK'].length){
                var base_mask = arMShopOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
                $('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').inputmask('mask', {'mask': arMShopOptions['THEME']['PHONE_MASK'] });
                $('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').blur(function(){
                    if( $(this).val() == base_mask || $(this).val() == '' ){
                        if( $(this).hasClass('required') ){
                            $(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
                        }
                    }
                });
            }
        });
    </script>
<?$frame->end()?>
<?function drawCustomFormField($FIELD_SID, $arQuestion, $arErrors = array()){
    $placeholder = $arQuestion["CAPTION"];
    if ($arQuestion["REQUIRED"] == "Y") {
        $placeholder .= ' *';
    }
    ?>
    <?$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);?>
    <?$arQuestion["HTML_CODE"] = str_replace('left', '', $arQuestion["HTML_CODE"]);?>
    <?$arQuestion["HTML_CODE"] = str_replace('size="0"', '', $arQuestion["HTML_CODE"]);?>
    <?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
        <?=$arQuestion["HTML_CODE"];?>
    <?else:?>
        <div class="form-control">
            <?
            if(is_array($arErrors) && array_key_exists($FIELD_SID, $arErrors)){
                $arQuestion["HTML_CODE"] = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);
            }
            if($arQuestion["REQUIRED"] == "Y"){
                $arQuestion["HTML_CODE"] = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);
            }
            $arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="text" placeholder="'.$placeholder.'"', $arQuestion["HTML_CODE"]);
            if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email"){
                $arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="email"', $arQuestion["HTML_CODE"]);
            }
            ?>
            <?=$arQuestion["HTML_CODE"]?>
        </div>
    <?endif;?>
    <?
}?>
