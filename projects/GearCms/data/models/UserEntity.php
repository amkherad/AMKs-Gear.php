<?php

namespace GearCms\data\models;

class UserEntity extends BaseEntity
{
    static $entityName = 'userentity';

    public

        $username,
        $passwordHash,
        $passwordSalt,
        $passwordMiss,

        $firstname,
        $lastname,
        $mobile,
        $tell,
        $email,
        $address,
        $smsnumber,

        $avatar


    ;
}