<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<?if ($arResult) {?>
<ul>
    <?foreach ($arResult as $arItem) {?>
        <li <?=$arItem['SELECTED'] ? 'class="active"' : ''?>>
            <a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
        </li>
    <?}?>
</ul>
<?}?>