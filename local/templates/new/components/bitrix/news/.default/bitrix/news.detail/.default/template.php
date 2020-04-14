<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<h1><?=$arResult['NAME']?></h1>
<div class="news-item-block">
    <?if ($arResult["DETAIL_PICTURE"] || $arResult["PREVIEW_PICTURE"]["SRC"]) {?>
        <img class="detail_picture"
             border="0"
             src="<?=$arResult["DETAIL_PICTURE"]["SRC"] ?: $arResult["PREVIEW_PICTURE"]["SRC"]?>"
             width="1040" height="587"
             alt="<?=$arResult["DETAIL_PICTURE"]["ALT"] ?: $arResult["PREVIEW_PICTURE"]["ALT"]?>"
             title="<?=$arResult["DETAIL_PICTURE"]["TITLE"] ?: $arResult["PREVIEW_PICTURE"]["TITLE"]?>">
    <?}?>
    <span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
        <?=$arResult['DETAIL_TEXT']?>
    <div style="clear:both"></div>
</div>