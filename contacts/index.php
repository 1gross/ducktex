<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
    <div class="page contacts">
        <div class="wrapper">
            <h1><?=$APPLICATION->GetTitle()?></h1>
        </div>
        <div id="address">
            <?/*$APPLICATION->IncludeComponent(
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
            );*/?>
        </div>
        <div class="wrapper">
            <div class="contacts-block">
                <div class="contacts-info">
                    <div class="contacts-list">
                        <div class="contacts-list-item">
                            <div class="title">Фактический адрес</div>
                            <div class="data">
                                <?$APPLICATION->IncludeFile($APPLICATION->GetTemplatePath('/include/address.php'), Array(), Array("MODE" => "html", "NAME" => "Адрес"));?>
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">Телефон</div>
                            <div class="data">
                                <?$APPLICATION->IncludeFile($APPLICATION->GetTemplatePath('/include/phone.php'), Array(), Array("MODE" => "html", "NAME" => "Телефон"));?>
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">Email</div>
                            <div class="data">
                                <?$APPLICATION->IncludeFile($APPLICATION->GetTemplatePath('/include/email.php'), Array(), Array("MODE" => "html", "NAME" => "Email"));?>
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">Instagram</div>
                            <div class="data">
                                <?$APPLICATION->IncludeFile($APPLICATION->GetTemplatePath('/include/instagram.php'), Array(), Array("MODE" => "html", "NAME" => "Instagram"));?>
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">Режим работы</div>
                            <div class="data">
                                <?$APPLICATION->IncludeFile($APPLICATION->GetTemplatePath('/include/schedule.php'), Array(), Array("MODE" => "html", "NAME" => "Режим работы"));?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feedback">
                    <div class="notif-block">
                        <div class="title">
                            <?$APPLICATION->IncludeFile(SITE_DIR."include/contacts_title.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("CONTACTS_TEXT")));?>
                        </div>
                        <div class="text">
                            <?$APPLICATION->IncludeFile(SITE_DIR."include/contacts_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("CONTACTS_TEXT")));?>
                        </div>
                    </div>
                    <?$APPLICATION->IncludeComponent(
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
                    );?>

                    <div class="contacts-list">
                        <div class="contacts-list-item">
                            <div class="title">Индивидуальный предприниматель</div>
                            <div class="data">
                                Озорнов Антон Александрович
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">ИНН</div>
                            <div class="data">
                                667359101254
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">ОГРНИП</div>
                            <div class="data">
                                315665800046732
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">р/сч</div>
                            <div class="data">
                                40802810616540013835 в Уральский банк ПАО Сбербанк
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">к/с</div>
                            <div class="data">
                                30101810500000000674
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">БИК</div>
                            <div class="data">
                                046577674
                            </div>
                        </div>
                        <div class="contacts-list-item">
                            <div class="title">Юридический адрес</div>
                            <div class="data">
                                г.Москва
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?$APPLICATION->IncludeComponent(
            "bitrix:form.result.new",
            "subscribe",
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
        );?>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>