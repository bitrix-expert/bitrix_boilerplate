<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?$APPLICATION->ShowHead()?>
<title><?$APPLICATION->ShowTitle()?></title>
    <?\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/bootstrap/css/bootstrap.min.css');?>
    <?\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/bootstrap/css/bootstrap-theme.min.css');?>
    <?\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jquery.min.js');?>
    <?\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/bootstrap/js/bootstrap.min.js');?>
</head>

<body>
<?$APPLICATION->ShowPanel();?>
<div class="body <?=($isIndex ? 'index' : '')?>">
    <div class="body_media"></div>
    <div role="main" class="main">
        <div class="container">
            <div class="row">
                <div class="page-header">
                    <h3>
                        <?=$APPLICATION->ShowTitle()?>
                    </h3>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:system.auth.form",
                        "auth",
                        Array(
                            "COMPONENT_TEMPLATE" => "auth",
                            "FORGOT_PASSWORD_URL" => "",
                            "PROFILE_URL" => "",
                            "REGISTER_URL" => "",
                            "SHOW_ERRORS" => "N",
                        )
                    );?>
                </div>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "tabs",
                    Array(
                        "ROOT_MENU_TYPE"	=>	"top",
                        "MAX_LEVEL"	=>	"1",
                        "USE_EXT"	=>	"N",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "N",
                        "MENU_CACHE_GET_VARS" => Array()
                    )
                );?>
            </div>
        </div>
    <div class="container">
        <div class="row">