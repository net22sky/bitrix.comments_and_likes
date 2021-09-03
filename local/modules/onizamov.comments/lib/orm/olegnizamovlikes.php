<?php

namespace Onizamov\Comments\Orm;

use Bitrix\Main\Entity;
use \Bitrix\Main\Application;

/**
 * Class OnizamovCommentsTable - ОРМ класс для таблицы комментариев
 *
 * @package Onizamov\Comments\Orm
 */
class OnizamovLikesTable extends Entity\DataManager
{
    /**
     * @inheritdoc
     */
    public static function getTableName()
    {
        return 'onizamov_likes';
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
            new Entity\IntegerField('UF_NEWS_LIKE', [
                'required' => true,
            ]),
            new Entity\DatetimeField('UF_DATE_LIKE', [
                'required' => true,
            ]),
            new Entity\IntegerField('UF_USER_LIKE', [
                'required' => true,
            ]),
        ];
    }

    public static function add(array $data): \Bitrix\Main\ORM\Data\AddResult
    {
        $data['UF_DATE_LIKE'] = new \Bitrix\Main\Type\DateTime();
        return parent::add($data);
    }

    public static function createTable()
    {
        $sql = self::getEntity()->compileDbTableStructureDump()[0];
        $sql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $sql);

        $connection = Application::getConnection();
        $connection->query($sql);
    }


}