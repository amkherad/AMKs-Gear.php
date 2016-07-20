<?php
require 'models/RichRequestModel.php';

trait RichPattern
{
    protected
        $repository;

    public function authorize()
    {
        //throw new HttpStatusCodeException('test', 403, 0);
    }

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