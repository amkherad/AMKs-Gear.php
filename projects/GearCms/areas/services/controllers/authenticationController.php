<?php

use GearCms\areas\services\models\authentication\AuthenticationModel;
use GearCms\data\models\UserEntity;

class authenticationController extends GearController
{
    function login()
    {
        $user = UserEntity::create();
        $user->username = 'sexy';
        UserEntity::save($user);
    }

    function login_POST(AuthenticationModel $authenticationModel)
    {
        if ($_GET['password'] == $authenticationModel->password)
            throw new GearInvalidOperationException();

        return print_r($authenticationModel, true);
    }

    function logout_POST()
    {

    }

    function register()
    {
        return $this->view('');
    }
}