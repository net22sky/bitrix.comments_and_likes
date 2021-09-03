<?php

namespace Onizamov\Comments\Orm;

use Bitrix\Main\Entity;

/**
 * Class OnizamovCommentsTable - ОРМ класс для таблицы комментариев
 *
 * @package Onizamov\Comments\Orm
 */
class OnizamovCommentsTable extends Entity\DataManager
{
    /**
     * @inheritdoc
     */
    public static function getTableName()
    {
        return 'onizamov_comments';
    }

    /**
     * @inheritdoc
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
            ]),
            new Entity\IntegerField('UF_NEWS_ID', [
                'required' => true,
            ]),
            new Entity\TextField('UF_CONTENT', [
                'required' => true,
            ]),
            new Entity\DatetimeField('UF_DATE', [
                'required' => true,
            ]),
            new Entity\IntegerField('UF_USER', [
                'required' => true,
            ]),
        ];
    }

    public static function add(array $data): \Bitrix\Main\ORM\Data\AddResult
    {
        $data['UF_DATE'] = new \Bitrix\Main\Type\DateTime();
        return parent::add($data);
    }


    /**
     * Возвращает все комментарии
     *
     * @param array $arFilter Массив для фильтрации
     * @param string $sSort Индекс сортировки. Возможные значения asc | desc
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @global         $USER
     *
     */
    public static function getComments(
        array $arFilter = [],
        string $sSort = 'desc'
    ): array {
        $arForGetList = [
            'select' => [
                'ID',
                'UF_NEWS_ID',
                'UF_CONTENT',
                'UF_DATE',
                'UF_USER',
            ],
            'order'  => ['UF_DATE' => 'asc'],
        ];
        if ($arFilter) {
            $arForGetList['filter'] = $arFilter;
        };

        $oAllComments = parent::getList($arForGetList);
        if ($oAllComments->getSelectedRowsCount() > 0) {
            $arComments = [];
            while ($arComment = $oAllComments->fetch()) {
                $arComments[] = $arComment;
            };

            usort($arComments, function ($arFirst, $arSecond) use ($sSort) {
                if ($sSort == 'asc') {
                    return $arFirst['UF_DATE'] <=> $arSecond['UF_DATE'];
                }

                return -($arFirst['UF_DATE'] <=> $arSecond['UF_DATE']);
            });

            return $arComments;
        } else {
            return [];
        }
    }


}