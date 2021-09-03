<?php

namespace Onizamov\Comments\Classes;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use CUser;
use Bitrix\Main\Data\Cache;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

/**
 * Класс для рендеринга комментариев
 *
 * Class CommentsRenderer
 *
 */
class CommentsRendererView
{
    const FORMAT_DATE = "d.m.y H:i";

    /**
     * Генерирует HTML комментария Для компонента onizamov.comments
     * @param array $arComment Комментарий из дерева комментариев
     *
     * @return string
     */
    private function renderComment($arComment)
    {
        global $USER;
        $sHtmlComment = '<div class=\'onizamov-comments-added\' ';
        $sHtmlComment .= 'data-comment_id=\'' . $arComment['ID'] . '\'';
        $sHtmlComment .= 'data-user_id =\'' . $arComment['USER_ID'] . '\'';
        $sHtmlComment .= '>';

        if(empty($arComment['USER_NAME']) && !empty($arComment['UF_USER'])){
            $user = CUser::GetByID($arComment['UF_USER'])->Fetch();
            $arComment['USER_NAME'] = $user['LAST_NAME'].' '.$user['NAME'];
        }

        $sHtmlComment .= '<p><a class=\'onizamov-comments-added-name\'';
        $sHtmlComment .= 'href=\'/company/personal/user/' . $arComment['UF_USER'] . '/\'';// Ссылка на комментарий
        $sHtmlComment .= '>' . $arComment['USER_NAME'] . '</a>';// Имя
        /** Дата комментария*/
        $sHtmlComment .= ' ' . $arComment['UF_DATE']->format(self::FORMAT_DATE);
        $sHtmlComment .= '</p>';
        $sHtmlComment .= '<p data-comment_text>' . htmlspecialchars_decode($arComment['UF_CONTENT']) . '</p>';
        $sHtmlComment .= '</div><hr>';
        return $sHtmlComment;
    }

    /**
     * Генерирует HTML всего дерева комменатриев и выводит его.
     * Используется в компоненте onizamov.comments
     *
     * @param $arResult
     */
    public static function renderComments($arResult)
    {
        $sHtmlAllComments = '';
        foreach ($arResult['COMMENTS_TREE'] as $arComment) {
            $sHtmlAllComments .= self::renderComment($arComment);
        }
        echo $sHtmlAllComments;
    }

}