<?php

class accountController extends GearController
{
    use Authentication;

    function index__GET()
    {
        return $this->Json([
            'name' => 'ali',
            'family' => 'mousavi kherad'
        ]);
    }
}