<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<?if ($arResult['SECTIONS']) {?>
<div class="catalog-block">
    <?
    $i = 1;
    foreach ($arResult['SECTIONS'] as $arItem) {?>
        <div class="catalog-item">
            <div class="catalog-item-image">
                <img src="<?=$arItem['PICTURE']['SRC'] ?: '/local/front/files/img/catalog'.$i.'.jpg'?>" alt="<?=$arItem['NAME']?>">
            </div>
            <div class="catalog-item-list">
                <a href="<?=$arItem['SECTION_PAGE_URL']?>" class="catalog-item-link main">
                    <?=$arItem['NAME']?> <?if ($arItem['ELEMENT_CNT']) {?><span>(<?=$arItem['ELEMENT_CNT']?>)</span><?}?>
                </a>
                <?if ($arItem['SUBSECTIONS']) {?>
                    <?foreach ($arItem['SUBSECTIONS'] as $item) {?>
                        <a href="<?=$item['SECTION_PAGE_URL']?>" class="catalog-item-link">
                            <?=$item['NAME']?> <?if ($item['ELEMENT_CNT']) {?><span>(<?=$item['ELEMENT_CNT']?>)</span><?}?>
                        </a>
                    <?}?>
                <?}?>
            </div>
        </div>
        <?$i++?>
    <?}?>
</div>
<?}?>
