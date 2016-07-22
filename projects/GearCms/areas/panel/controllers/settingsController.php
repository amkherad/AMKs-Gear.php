<?php

use \GearCms\unity\UserManager;
use \GearCms\unity\Authorize;

class settingsController extends GearController
{
    use UserManager;
    use Authorize;


    function index()
    {
        'hello';
    }
}