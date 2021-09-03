<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED != true) {
    die();
}
?>
<div class="social news-details__social inl-b-centered" id="div-button-<?= $arParams['NEWS_ID'] ?>">
    <button type="button"
            class="button like<?= ($arResult['LIKED']) ? ' like--liked' : '' ?><?= $arParams['TOGGLE'] ? ' toggle' : '' ?>"
            entity="<?= $arParams['NEWS_ID'] ?>">
        <i class="like__img"></i>
        <span class="like__count"><?= $arResult['COUNT'] ?></span>
    </button>
    <div class="clear"></div>
</div>

<?php
if (!empty($arResult['USERS_CLICK'])):
    ?>
    <div class="second popup-window popup-window-contentview" id="popup-div-button-<?= $arParams['NEWS_ID'] ?>"
         style="width: 230px;position: absolute;z-index: 1250 !important;text-align: left; ">
        <div class="popup-window-content">
        <span class="bx-contentview-wrap-block" style="display: block;">
            <span class="bx-contentview-popup-name-new contentview-name">Просмотры</span>
            <span class="bx-contentview-popup-outer">
                <?
                foreach ($arResult['USERS_CLICK'] as $userName){ ?>
                <span class="bx-contentview-popup">
                        <span class="bx-contentview-popup-name-new"><?= $userName; ?></span>
                </span>
                <?
                } ?>
            </span>
        </span>
        </div>
    </div>
<?php
endif; ?>
<script>
    BX.message({
        ONIZAMOV_LIKE_LIKE: '<?= GetMessage('ONIZAMOV_LIKE_LIKE') ?>',
        ONIZAMOV_LIKE_DISLIKE: '<?= GetMessage('ONIZAMOV_LIKE_DISLIKE') ?>'
    });
</script>