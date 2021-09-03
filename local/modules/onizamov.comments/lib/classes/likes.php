<?php

namespace Onizamov\Comments\Classes;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use CUser;
use Bitrix\Main\Data\Cache;
use \Bitrix\Main\Entity\ExpressionField;
use \Onizamov\Comments\Orm\OnizamovLikesTable;


class Likes
{
    /**
     * Подсчитывает количество лайков для заданного фильра
     *
     * @param array $query (Filter)
     * @return int
     */
    public static function getCount(array $query): int
    {
        $result = OnizamovLikesTable::getList(
            [
                'select' => ['CNT'],
                'filter' => $query,
                'runtime' => [
                    new ExpressionField('CNT', 'COUNT(*)'),
                ],
            ]
        )->fetch();

        return intval($result['CNT']);
    }

    /**
     * Подсчитывает количество лайков для заданного фильра
     *
     * @param array $query (Filter)
     * @return array
     */
    public static function getUsers(array $query): array
    {
        $result = OnizamovLikesTable::getList(
            [
                'select' => ['UF_USER_LIKE'],
                'filter' => $query,
            ]
        )->fetchAll();

        return array_column($result,'UF_USER_LIKE');
    }

    /**
     * Проверяет установлен ли лайк для текущего пользователя
     *
     * @param array $query (Filter)
     * @return bool
     */
    public static function isLikeSet(array $query): bool
    {
        $result = self::getCount($query);
        return ($result > 0);
    }

    /**
     * Устанавливает Лайк, если для текущего пользователя лайк не существует и
     * Удаляет лайк, если он существует
     *
     * @param array $query (Filter)
     * @return bool
     */
    public static function toggleLike(array $query): bool
    {
        $isSet = self::isLikeSet($query);
        if ($isSet === true) {
            $res = OnizamovLikesTable::getList(
                [
                    'select' => ['ID'],
                    'filter' => $query,
                ]
            );

            $results = [];
            while ($like = $res->fetch()) {
                $results[] = OnizamovLikesTable::delete($like['ID'])->isSuccess();
            }

            return (!in_array(false, $results));
        }

        return OnizamovLikesTable::add($query)->isSuccess();
    }

}