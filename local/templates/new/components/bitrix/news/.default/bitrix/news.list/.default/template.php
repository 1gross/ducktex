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
<section class="page news">
    <div class="wrapper">
        <h1>НОВОСТИ</h1>
        <div class="news-block">
            <?foreach($arResult["ITEMS"] as $arItem) {
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="image" style="background-image: url('<?=$arItem['PREVIEW_PICTURE']['SRC'] ?: '/local/front/files/img/news-item.jpg'?>');">
                    </div>
                    <div class="news-item-info">
                        <div class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                        <div class="title"><?=$arItem['PREVIEW_TEXT']?></div>
                        <?if ($arItem['PREVIEW_TEXT']) {?>
                            <div class="description">
                                <?=$arItem['PREVIEW_TEXT']?>
                            </div>
                        <?}?>
                    </div>
                </a>
            <?}?>

        </div>
        <?=$arResult["NAV_STRING"]?>
    </div>
</section>