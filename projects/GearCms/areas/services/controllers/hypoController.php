<?php

class hypoController extends GearController
{
    function getAll(HypoRequest $request)
    {
        return print_r($request, true);
    }
}

class HypoRequest
{
    public
        $entity,
        $id
    ;
}