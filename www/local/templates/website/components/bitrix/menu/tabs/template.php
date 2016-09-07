<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<ul class="nav nav-tabs">
    <?foreach($arResult as $aItem):?>
        <li role="presentation" class="<?=$aItem["SELECTED"] ? 'active' : ''?>">
            <a href="<?=$aItem["LINK"]?>"><?=$aItem["TEXT"]?></a>
        </li>
    <?endforeach;?>
</ul>
<br/>
