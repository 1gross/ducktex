<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
use Bitrix\Main\PhoneNumber\Format,
    Bitrix\Main\PhoneNumber\Parser;

AddEventHandler("main", "OnBeforeUserAdd", 'checkUserPhone');
AddEventHandler("main", "OnBeforeUserUpdate", 'checkUserPhone');
function checkUserPhone(&$arFields)
{


}

AddEventHandler("main", "OnLayoutRender", function () {
    global $APPLICATION;
    $pageCanonicalUrl = $APPLICATION->GetPageProperty("canonical", false);
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    if ($pageCanonicalUrl === false) {
        $canonical = [
            ($request->isHttps() ? "https://" : "http://"),
            $request->getHttpHost(),
            $APPLICATION->GetCurDir(),
        ];
        $APPLICATION->SetPageProperty("canonical", implode("", $canonical));
    }
    $robotsContent = $APPLICATION->GetPageProperty("robots", false);
    if ($robotsContent === false) {
        $robotsFile = new \Bitrix\Seo\RobotsFile(SITE_ID);
        $disallowRules = $robotsFile->getRules("Disallow");
        $isRobotsDisallow = false;
        if (!empty($disallowRules)) {
            foreach ($disallowRules as $rule) {
                $matchRule = preg_quote($rule[1], "#");
                $matchRule = str_replace('\*', '.*', $matchRule);
                if (preg_match("#^" . $matchRule . "#", $request->getRequestUri())) {
                    $isRobotsDisallow = true;
                    break;
                }
            }
        }
        if (!$isRobotsDisallow) {
            $APPLICATION->SetPageProperty("robots", "index, follow");
        }
        else {
            $APPLICATION->SetPageProperty("robots", "noindex, nofollow");
        }
    }
});

//catalog handler


class CustomCatalog
{
    function addProduct($id, $quantity)
    {

    }
}