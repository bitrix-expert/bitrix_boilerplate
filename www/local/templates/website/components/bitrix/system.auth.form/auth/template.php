<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;
?>
<div class="navbar-right b-auth_block">
    <?if ($arResult["FORM_TYPE"] == "login"):?>
        <button type="button" class="btn btn-primary btn-xs navbar-right" data-toggle="modal" data-target="#authModal">
            <?=Loc::getMessage('AUTH_LABEL');?>
        </button>
        <div class="modal fade bs-example-modal-sm" id="authModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" <?=count($arResult['ERROR_MESSAGE']['MESSAGE']) ? 'data-show="true"' : ''?>>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?=Loc::getMessage('AUTH_LABEL');?></h4>
                    </div>
                    <div class="modal-body">
                        <?if(count($arResult['ERROR_MESSAGE']['MESSAGE'])):?>
                            <div class="alert alert-danger js-auth_errors" role="alert"><?=$arResult['ERROR_MESSAGE']['MESSAGE']?></div>
                        <?endif;?>
                        <form method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
                            <?if(strlen($arResult["BACKURL"]) > 0):?>
                                <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
                            <?endif;?>
                            <?foreach($arResult["POST"] as $postKey => $postVal):?>
                                <input type="hidden" name="<?=$postKey?>" value="<?=$postVal?>"/>
                            <?endforeach;?>
                            <input type="hidden" name="AUTH_FORM" value="Y"/>
                            <input type="hidden" name="TYPE" value="AUTH"/>

                            <div class="form-group">
                                <label for="email"><?=Loc::getMessage("AUTH_LOGIN")?>:</label>
                                <input type="text" class="form-control" name="USER_LOGIN" placeholder="<?=Loc::getMessage("AUTH_LOGIN")?>" value="<?=$arResult["USER_LOGIN"]?>">
                            </div>
                            <div class="form-group">
                                <label for="pwd"><?=Loc::getMessage("AUTH_PASSWORD")?>:</label>
                                <input type="password" class="form-control" name="USER_PASSWORD" placeholder="<?=Loc::getMessage("AUTH_PASSWORD")?>">
                            </div>
                            <?if($arResult["STORE_PASSWORD"] == "Y"):?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="USER_REMEMBER" value="Y"><?=Loc::getMessage("AUTH_REMEMBER_ME")?></label>
                                </div>
                            <?endif;?>
                            <input type="submit" class="btn btn-default" name="Login" value="<?=Loc::getMessage("AUTH_LOGIN_BUTTON")?>"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?else:?>
        <form action="<?=$arResult["AUTH_URL"]?>">

            <span class="label label-primary"><?=Loc::getMessage('AUTH_HELLO', ['#user_name#' => $arResult["USER_NAME"]])?></span>

        <?foreach ($arResult["GET"] as $key => $value):?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
        <?endforeach?>
            <input type="hidden" name="logout" value="yes" />
            <input type="submit" class="btn btn-default btn-xs" value="<?=Loc::getMessage("AUTH_LOGOUT_BUTTON")?>">
        </form>
    <?endif?>
</div>