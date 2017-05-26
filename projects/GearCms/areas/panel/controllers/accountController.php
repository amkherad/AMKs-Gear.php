<?php

use GearCms\areas\services\models\authentication\AuthenticationModel;

class accountController extends GearController
{
    function login()
    {
        return $this->view();
    }

    function login_POST(AuthenticationModel $authenticationModel)
    {
        if ($this->getRequest()->getValue('password') == $authenticationModel->password)
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

    function forgotpassword()
    {

    }
    function changepassword_POST()
    {

    }
}