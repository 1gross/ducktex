<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<?if ($arResult['ITEMS']) {?>
<section class="block plus with-sea">
    <div class="wrapper">
        <div class="plus-block">
            <?foreach ($arResult['ITEMS'] as $arItem) {?>
                <div class="plus-item">
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                    <div class="title"><?=$arItem['NAME']?></div>
                </div>
            <?}?>
        </div>
    </div>
</section>
<?}?>