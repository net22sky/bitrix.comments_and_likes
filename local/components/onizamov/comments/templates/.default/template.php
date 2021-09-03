<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arResult
 * @var array $arParams
 */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Page\Asset;
use \Onizamov\Comments\Classes\CommentsRendererView;

global $USER;

Loc::loadMessages(__FILE__);
Asset::getInstance()->addCss("/local/js/trumbowyg/dist/ui/trumbowyg.min.css");
Asset::getInstance()->addCss("/local/js/trumbowyg/dist/plugins/emoji/ui/trumbowyg.emoji.min.css");
Asset::getInstance()->addJs("/local/components/onizamov/comments/templates/.default/js/jquery.min.js");
Asset::getInstance()->addJs("/local/js/trumbowyg/dist/trumbowyg.min.js");
Asset::getInstance()->addJs("/local/js/trumbowyg/dist/plugins/emoji/trumbowyg.emoji.min.js");
Asset::getInstance()->addJs("/local/js/trumbowyg/dist/langs/ru.min.js");
?>

<section id='onizamov-comments-section'>
    <?php
    // Выводим комментарии
    CommentsRendererView::renderComments($arResult); ?>
    <div class='onizamov-comments'></div>
    <div class="onizamov-comments-after">
        <a href='javascript:void(0);'
           class='onizamov-comments-add-btn js-add-comment'><?= Loc::getMessage('ADD_COMMENT') ?></a>
    </div>
</section>


<script type="text/javascript">
    $('.onizamov-comments').trumbowyg({
        lang: 'ru',
        svgPath: '/local/js/trumbowyg/dist/ui/icons.svg',
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['link'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['emoji'],
            ['removeformat'],
        ],
        plugins: {
            emoji: {
                emojiList: [
                    [':)', '../img/smileys/smile.png'],
                    [':D', '../img/smileys/smile-big.png'],
                    [':^^:', '../img/smileys/hehe.png'],
                    [':happy:', '../img/smileys/happy.png']
                ]
            }
        }
    });

    $(".onizamov-comments-add-btn").click(function () {
        let formData = [{UF_CONTENT: $('.onizamov-comments').trumbowyg('html')}, {
            UF_USER: <?=$USER->GetID();?>
        }, {UF_NEWS_ID: <?=$arParams['CONTENT_ID'];?>}];
        BX.ajax.runComponentAction('onizamov:comments',
            'addComment', {
                mode: 'class',
                data: {post: formData},
            })
            .then(function (response) {
                if (response.status === 'success') {
                    location.reload();
                }
            });
    });


</script>