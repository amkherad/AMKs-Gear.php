<?php
require 'models/RichRequestModel.php';

trait RichPattern
{
    protected
        $repository;

    public function getAll(RichRequestModel $request = null)
    {
        return [
            'name' => 'john',
            'family' => 'ali',
            'jj' => 'test'
        ];
    }


    public function endExecute()
    {
        $repo = $this->repository;
        if ($repo != null)
            $repo->close();
    }
}