<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Onizamov\Comments\Orm\OnizamovCommentsTable;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;

Loc::loadMessages(__FILE__);

/**
 * Class OnizamovCommentComponent Класс компонента комментариев
 */
class OnizamovCommentComponent extends CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable
{
    /**
     * @var array
     */
    public $arErrors = [];

    /**
     * Объект запроса
     * @var object
     */
    protected $oRequest;

    /**
     * @param $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function onPrepareComponentParams($arParams)
    {
        global $USER;
        $this->oRequest = Context::getCurrent()->getRequest();
        $arParams['TEMPLATE_NAME'] = $this->getTemplateName();

        if (!Loader::includeModule('onizamov.comments')) {
            $this->arErrors[] = Loc::getMessage('ONIZAMOV_COMMENTS_MODULE_EXISTS');
        }

        if ($USER->IsAuthorized()) {
            $arParams['USER_NAME'] = $USER->GetFullName();
            $arParams['USER_EMAIL'] = $USER->GetEmail();
            $arParams['USER_ID'] = $USER->GetId();
        }

        return $arParams;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            $this->arErrors[] = Loc::getMessage('USER_IS_NOT_AUTHORISED');
        }

        if (!empty($this->arErrors)) {
            foreach ($this->arErrors as $sError) {
                ShowError($sError);
            }

            return;
        }
        $this->arResult['COMPONENT_ID'] = $this->getEditAreaId('OnizamovComments');
        $arFilter['UF_NEWS_ID'] = $this->arParams['CONTENT_ID'];

        $this->arResult['COMMENTS_TREE'] = OnizamovCommentsTable::getComments(
            $arFilter
        );

        $this->includeComponentTemplate();
    }

    /**
     * Метод определения AJAX запросов
     */
    public function configureActions()
    {
        return [
            'addComment' => [
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
    public function addCommentAction($post)
    {
        $fields=[];
        foreach($post as $elemPost){
            $fields[key($elemPost)] = current($elemPost);
        }


        if (!empty($fields['UF_CONTENT'])) {
            $fields['UF_CONTENT'] = htmlspecialchars($fields['UF_CONTENT']);
        }

        $result = OnizamovCommentsTable::add($fields);
        if (!$result->isSuccess()) {
            return ['status' => 'not'];
        }
        return ['status' => 'success'];
    }

}