<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult) {?>
    <div class="filter-item">
        <div class="filter-title">Каталог</div>
        <div class="filter-body">
            <?foreach($arResult as $arItem) {?>
                <div class="filter-body-item">
                    <div class="header <?=$arItem["SELECTED"] ? 'open' : ''?>">
                        <div class="title"><?=$arItem["TEXT"]?></div>
                    </div>
                    <?if($arItem["CHILD"]){?>
                    <div class="body" <?=$arItem["SELECTED"] ? 'style="display: block;"' : ''?>>
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
            <?}?>
        </div>
    </div>
<?}?>