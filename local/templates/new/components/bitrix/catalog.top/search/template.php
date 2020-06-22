<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<?if ($arResult['ITEMS']) {?>
    <div class="search-header">
        <div class="filter">
            <?$method = $_GET["method"] == 'desc' ? 'asc' : 'desc'; ?>
            <?$methodDefault = $isDefault && !isset($_GET["method"]) ? 'desc' : ''?>
            <a href="<?=$APPLICATION->GetCurPageParam('sort=shows&method='.$method, array('sort', 'method'))?>"
               class="filter-item <?=$isDefault ? $methodDefault : ''?> <?=$_GET["sort"] == "shows" && isset($_GET["method"]) ? $_GET["method"] : ''?>"">По популярности</a>

            <a href="<?=$APPLICATION->GetCurPageParam('sort=name&method='.$method, array('sort', 'method'))?>"
               class="filter-item <?=$_GET["sort"] == "name" && isset($_GET["method"]) ? $_GET["method"] : ''?>"">По алфавиту</a>

            <a href="<?=$APPLICATION->GetCurPageParam('sort=price&method='.$method, array('sort', 'method'))?>"
               class="filter-item <?=$_GET["sort"] == "price" && isset($_GET["method"]) ? $_GET["method"] : ''?>">По цене</a>
        </div>
    </div>
    <div class="search-list">
        <?foreach ($arResult['ITEMS'] as $arItem) {?>
        <div class="product-card">
            <?$APPLICATION->IncludeComponent(
                'bitrix:catalog.item',
                '',
                array(
                    'ITEM' => $arItem,
                    'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
                    'IS_NEW' => $arParams['IS_NEW'] ?: 'N'
                )
            )?>
        </div>
        <?}?>
        <?=$arResult['NAV_STRING']?>
    </div>
<?} else {?>
    <div class="block-message"><?=$arResult['ERROR_TEXT'] ?: 'К сожалению ничего не найдено'?></div>
<?}?>
