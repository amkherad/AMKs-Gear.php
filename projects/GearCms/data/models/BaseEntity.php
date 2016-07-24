<?php

namespace GearCms\data\models;
use GearCms\data\DataInterface;
use \R;

class BaseEntity extends \RedBeanPHP\SimpleModel
{
    static $entityName;

    public
        $id,
        $order,
        $createdDateTime,
        $updateDateTime,
        $owner
    ;

    public static function create()
    {
        $interface = DataInterface::open();
        return R::dispense(static::$entityName);
    }

    public static function save($entity)
    {
        $interface = DataInterface::open();
        $entity->createdDateTime = date(Cms_Sql_DateFormat);
        return R::store($entity);
    }
}