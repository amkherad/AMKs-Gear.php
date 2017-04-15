<?php

class accountController extends GearController
{
    function login__GET()
    {
        return $this->Json([
            'name' => 'ali',
            'family' => 'mousavi kherad'
        ]);
    }
}