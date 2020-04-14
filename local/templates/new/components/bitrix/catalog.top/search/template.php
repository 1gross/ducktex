<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<div class="search-list">
    <?foreach ($arResult['ITEMS'] as $arItem) {?>
    <div class="product-card">
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="product-card-front">
            <div class="image" style="background-image: url('<?=$arItem['PREVIEW_PICTURE']['SRC'] ?: '/local/front/files/img/product-bg.jpg'?>);">

            </div>
            <div class="badge">
                -20%
            </div>
            <div class="title">
                <?=$arItem['NAME']?>
            </div>
            <div class="price">
                <div class="new">от 792 руб. / м.</div>
                <div class="last">990 руб.</div>
            </div>
        </a>
        <div class="hover">
            <div class="hover-item">
                <span class="hover-item-name">Страна:</span>
                <span class="hover-item-value">Корея</span>
            </div>
            <div class="hover-item">
                <span class="hover-item-name">Ширина:</span>
                <span class="hover-item-value">150мм.</span>
            </div>
            <div class="hover-item">
                <span class="hover-item-name">Состав:</span>
                <span class="hover-item-value">Полиэстр 60%, 40% хлопок</span>
            </div>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn outline">подробнее</a>
        </div>
        <div class="buttons-block">
            <button data-action="add_favorites" data-id="<?=$arItem['ID']?>" class="favorites"></button>
            <button data-action="add_compare" data-id="<?=$arItem['ID']?>" class="compare"></button>
        </div>
    </div>
    <?}?>
</div>
