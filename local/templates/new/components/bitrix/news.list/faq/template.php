<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="question-block">
    <?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"faq", 
	array(
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SHOW_TITLE" => "N",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "?send=ok",
		"USE_EXTENDED_ERRORS" => "Y",
		"WEB_FORM_ID" => "2",
		"COMPONENT_TEMPLATE" => "faq",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);?>
</div>
<h3>Прочие вопросы</h3>
<div class="faq-block">
    <?foreach ($arResult['ITEMS'] as $arItem) {?>
        <div class="faq-item">
            <div class="faq-item-header">
                <div class="title"><?=$arItem['PREVIEW_TEXT']?></div>
            </div>
            <div class="faq-item-body">
                <?=$arItem['DETAIL_TEXT']?>
            </div>
        </div>
    <?}?>
</div>
