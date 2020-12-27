<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\Loader;


class CatalogFavorites extends CBitrixComponent
{
    public function getFavorites()
    {
        global $USER, $APPLICATION;
        $arProducts = array();
        if ($USER->IsAuthorized()) {
            $rsUser = CUser::GetByID($USER->GetID());
            $arUser = $rsUser->Fetch();
            $arProducts = $arUser['UF_FAVORITES'];
        } else {
            $arProducts = unserialize($APPLICATION->get_cookie("favorites"));
        }
        return $arProducts;
    }
    public function executeComponent()
    {
        $this->arResult['ITEMS'] = $this->getFavorites();
        $this->includeComponentTemplate();
    }
}