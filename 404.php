<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
$APPLICATION->SetPageProperty('PAGE_LAYOUT', 'column1');
?>
<div class="not-found">
    <div class="wrapper">
        <div class="not-found-block">
            <div class="number">404</div>
            <div class="title">Страница не найдена</div>
            <div class="description">Неправильно набран адрес или такой страницы не существует</div>
        </div>
        <div class="buttons-group">
            <a href="<?=SITE_DIR?>" class="btn blue">на главную</a>
            <a href="<?=$_SERVER['HTTP_REFERER']?>" onclick="history.back()" class="btn outline big">вернуться назад</a>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

