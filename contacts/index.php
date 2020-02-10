<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?><div class="contacts_map">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	"map", 
	array(
		"API_KEY" => "",
		"COMPONENT_TEMPLATE" => "map",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTROLS" => array(
		),
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:55.71645095310462;s:10:\"google_lon\";d:37.4552436306085;s:12:\"google_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:10:\"DUCKTEX.RU\";s:3:\"LON\";d:37.45687441368955;s:3:\"LAT\";d:55.7159856059493;}}}",
		"MAP_HEIGHT" => "400",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"ZOOM_BLOCK" => array(
			"POSITION" => "right center",
		)
	),
	false
);?>
</div>
<div class="wrapper_inner">
	<div class="contacts_left clearfix">
		<div class="store_description">
			<div class="store_property">
				<div class="title">
					Фактический адрес
				</div>
				<div class="value">
					 <?$APPLICATION->IncludeFile(SITE_DIR."include/address.php", Array(), Array("MODE" => "html", "NAME" => "Адрес"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">
					Телефон
				</div>
				<div class="value">
					 <?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => "Телефон"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">
					Email
				</div>
				<div class="value">
					 <?$APPLICATION->IncludeFile(SITE_DIR."include/email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">
					Режим работы
				</div>
				<div class="value">
					 <?$APPLICATION->IncludeFile(SITE_DIR."include/schedule.php", Array(), Array("MODE" => "html", "NAME" => "Время работы"));?>
				</div>
			</div>
		</div>
	</div>
	<div class="contacts_right clearfix">
		<blockquote>
			<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("CONTACTS_TEXT")));?>
		</blockquote>
		 <?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-feedback-block");?> <?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new",
	"inline",
	Array(
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "?send=ok",
		"USE_EXTENDED_ERRORS" => "Y",
		"VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
		"WEB_FORM_ID" => "3"
	)
);?> <?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-feedback-block", "");?>
		<div class="store_property">
			<div class="title">
				Индивидуальный предприниматель
			</div>
			<div class="value">
				<p>
					Озорнов Антон Александрович
				</p>
			</div>
		</div>
		<div class="store_property">
			<div class="title">
				ИНН
			</div>
			<div class="value">
				<p>
					667359101254
				</p>
			</div>
		</div>
		<div class="store_property">
			<div class="title">
				ОГРНИП
			</div>
			<div class="value">
				<p>
					315665800046732
				</p>
			</div>
		</div>
		<div class="store_property">
			<div class="title">
				р/сч
			</div>
			<div class="value">
				<p>
					 40802810616540013835 в Уральский банк ПАО Сбербанк
				</p>
			</div>
		</div>
		<div class="store_property">
			<div class="title">
				к/с
			</div>
			<div class="value">
				<p>
					30101810500000000674
				</p>
			</div>
		</div>
		<div class="store_property">
			<div class="title">
				БИК
			</div>
			<div class="value">
				<p>
					046577674
				</p>
			</div>
		</div>
		<div class="store_property">
			<div class="title">
				Юридический адрес
			</div>
			<div class="value">
				<p>
					г.Москва<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>