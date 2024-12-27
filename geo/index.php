<?php
/**
 *@global  CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("GeoSearch");
?>

<?php
$APPLICATION->IncludeComponent(
    'k1ll1:geosearch',
    '',
    [
        'HL_IBLOCK_NAME' => 'Geosearch'
    ]
)
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
