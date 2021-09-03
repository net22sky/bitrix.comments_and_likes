<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use Onizamov\Comments\Classes\Likes;
use \Onizamov\Comments\Orm\OnizamovCommentsTable;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;

/**
 * Class OnizamovLikesComponent Класс компонента комментариев
 */
class OnizamovLikesComponent extends CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable
{

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        global $USER;
        Loader::includeModule('onizamov.comments');

        $usersId = Likes::getUsers(
            [
                'UF_NEWS_LIKE' => $this->arParams['NEWS_ID'],
            ]
        );
        $userNames = [];
        foreach ($usersId as $user) {
            $userObj = UserTable::getById($user)->fetchObject();
            if ($userObj) {
                $userNames[] = $userObj->get('LAST_NAME') .' '.$userObj->get('NAME');
            }
        }

        $this->arResult = [
            'COUNT' => Likes::getCount(
                [
                    'UF_NEWS_LIKE' => $this->arParams['NEWS_ID'],
                ]
            ),
            'LIKED' => Likes::isLikeSet(
                [
                    'UF_NEWS_LIKE' => $this->arParams['NEWS_ID'],
                    'UF_USER_LIKE' => $USER->GetID(),
                ]
            ),
            'USERS_CLICK' => $userNames,

        ];
        CJSCore::Init(['jquery']);
        $this->IncludeComponentTemplate();
    }

    /**
     * Метод определения AJAX запросов
     */
    public function configureActions()
    {
        return [
            'tooglelike' => [
                'prefilters' => [
                    new Authentication,
                    new HttpMethod(
                        [
                            HttpMethod::METHOD_POST,
                        ]
                    ),
                ],
            ],
        ];
    }

    /**
     * Метод ajax запроса
     */
    public function tooglelikeAction($post)
    {
        global $USER;
        $query = [
            'UF_NEWS_LIKE' => $post,
            'UF_USER_LIKE' => $USER->GetID(),
        ];

        Loader::includeModule('onizamov.comments');
        if (Likes::toggleLike($query)) {
            return ['status' => 'success'];
        }

        return ['status' => 'not'];
    }

}