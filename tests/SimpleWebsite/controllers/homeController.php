<?php
class homeController extends GearController
{
    use Authentication;
    use RichPattern;

    public function index()
    {
        $this->viewData->Name = 'hello';
        return $this->View($this->getAll());
    }
}