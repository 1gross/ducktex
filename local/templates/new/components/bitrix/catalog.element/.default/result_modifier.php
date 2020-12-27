<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */

$arResult['IS_FAVORITES'] = B24TechSiteHelper::checkFavoritesById($arResult['ID']);


$arPhoto = array();
if ($arResult['DETAIL_PICTURE']['SRC']) {
    $arPhoto[] = array(
        'SRC' => $arResult['DETAIL_PICTURE']['SRC'],
        'ALT' => $arResult['DETAIL_PICTURE']['ALT']
    );
    $this->SetViewTarget('og:image');
    echo $arResult['DETAIL_PICTURE']['SRC'];
    $this->EndViewTarget();
} else {
    $arPhoto[] = array(
        'SRC' => '/local/front/files/img/no-image.png',
        'ALT' => 'Это новая позиция, скоро мы добавим фото'
    );
}
if (is_array($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
    foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $id) {
        $imgPath = CFile::GetPath($id);
        $img = CFile::GetByID($id)->Fetch();
        $arPhoto[] = array(
            'SRC' => $imgPath,
            'ALT' => $img['DESCRIPTION'] ?: ''
        );
    }
}
$arResult['MORE_PHOTO'] = $arPhoto;
