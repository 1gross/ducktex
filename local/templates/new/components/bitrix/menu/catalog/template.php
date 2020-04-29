<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult) {?>
    <button class="catalog-menu-block_btn btn blue small js-init-catalog_show">Показать разделы</button>
    <div class="filter-item catalog-menu-block_block">

        <div class="filter-title">Каталог</div>
        <div class="filter-body">
            <?
            $key = 1;
            foreach($arResult as $arItem) {?>
                <div class="filter-body-item">
                    <div class="header <?=$arItem["SELECTED"] || ($key == 1 && $arParams['FIRST_ON_SELECTED'])? 'open' : ''?>">
                        <div class="title"><?=$arItem["TEXT"]?></div>
                    </div>
                    <?if($arItem["CHILD"]){?>
                    <div class="body" <?=$arItem["SELECTED"] || ($key == 1 && $arParams['FIRST_ON_SELECTED']) ? 'style="display: block;"' : ''?>>
                        <ul>
                            <?foreach($arItem["CHILD"] as $arChildItem){?>
                                <li <?=$arChildItem["SELECTED"] ? 'class="active"' : ''?>>
                                    <a href="<?=$arChildItem["LINK"];?>"><?=$arChildItem["TEXT"];?></a>
                                </li>
                            <?}?>
                        </ul>
                    </div>
                    <?}?>
                </div>
                <?$key++?>
            <?}?>
        </div>
    </div>
<?}?>