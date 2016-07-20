<?php
class homeController extends Controller
{
    use RichPattern;

    public function index()
    {
        $this->viewData->Name = 'hello';
        return $this->View($this->getAll());
    }
}