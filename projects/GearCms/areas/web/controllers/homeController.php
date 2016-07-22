<?php

use \GearCms\unity\UserManager;

class homeController extends GearController
{
    use UserManager;

    function index()
    {
        return $this->view();
    }
}