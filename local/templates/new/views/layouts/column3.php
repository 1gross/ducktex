<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>

<section id="additional">
    <div class="wrapper">
        <div class="additional-block">
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "simple_page",
                array(
                    "COMPONENT_TEMPLATE" => "simple_page",
                    "ROOT_MENU_TYPE" => "left",
                    "MENU_CACHE_TYPE" => "N",
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
            <div class="additional-item">
                <div class="additional-wrap additional-faq">
                    <h2><?=$APPLICATION->GetTitle(false)?></h2>
                    <div>
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetCurPage() . '/description.php',
                            array(),
                            array(
                                "SHOW_BORDER" => true,
                                "MODE" => "html"
                            )
                        );?>
                    </div>
                    <?=$arParams['CONTENT']?>
                </div>
            </div>
        </div>
    </div>
</section>
