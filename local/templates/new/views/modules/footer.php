<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

use Bitrix\Main\IO\File,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (file_exists($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/include/phone.php")) {
    $phonePath = File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/include/phone.php");
    $phonePath = preg_replace('/[^0-9]/', '', $phonePath);
}
?>

        <div class="card-block  show_mobile">
            <div class="wrapper">
            <div class="card-body">
                <div class="data">
                    <div class="pluses mobile">
                        <div class="pluses-block">
                            <div class="item">
                                <img src="/local/front/files/img/quality-icon.svg" alt="">
                                <div class="text">
                                    Ткани <br>высокого качества
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/delivery-icon.svg" alt="">
                                <div class="text">
                                    Оперативная отправка <br>в течение 1 - 3 дней
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/pay-icon.svg" alt="">
                                <div class="text">
                                    Оплата на <br>сайте
                                </div>
                            </div>
                            <div class="item">
                                <img src="/local/front/files/img/showroom-icon.svg" alt="">
                                <div class="text">
                                    Шоурум в <br>Москве
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>




<footer id="footer">
    <div class="wrapper">
        <div class="footer-block">
            <div class="item">
                <div class="title"><?=Loc::getMessage('MENU_TITLE_COMPANY')?></div>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "ROOT_MENU_TYPE" => "bottom_company",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "N",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ),
                    false
                );?>
            </div>
            <div class="item">
                <div class="title"><?=Loc::getMessage('MENU_TITLE_INFO')?></div>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "ROOT_MENU_TYPE" => "bottom_info",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "N",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ),
                    false
                );?>
            </div>
            <div class="item">
                <div class="title"><?=Loc::getMessage('MENU_TITLE_CONTACT')?></div>
                <a href="tel:<?= $phonePath ?: ''?>"
                   class="phone"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/phone.php", [], ["MODE" => "text"]);?></a>
                <a href="mailto:<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/email.php", [], ["SHOW_BORDER" => false,"MODE" => "text"]);?>"
                   class="email"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/email.php", [], ["MODE" => "text"]);?></a>
                <a href="https://instagram.com/<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/instagram.php", [], ["SHOW_BORDER" => false,"MODE" => "text"]);?>"
                   class="instagram"
                   target="_blank"><?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/instagram.php", [], ["MODE" => "text"]);?></a>
            </div>
        </div>
        <div class="center studio">
            <?=Loc::getMessage('DEVELOPER_HINT')?>: <a href="https://eto-yasno.ru" target="_blank"><?=Loc::getMessage('DEVELOPER_NAME')?></a>
        </div>
    </div>
</footer>
