<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<?if ($arResult) {?>
    <ul class="personal-menu select">
        <?foreach ($arResult as $arItem) {?>
            <li <?=$arItem['SELECTED'] ? 'class="active"' : ''?>>
                <a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
            </li>
        <?}?>
    </ul>
<?}?>