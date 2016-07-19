<?php
class homeController extends Controller
{
    use RichController;
    public function index()
    {
        return $this->Json($this->getAll());
    }
}