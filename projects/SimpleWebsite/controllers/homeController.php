<?php
class homeController extends \GearController
{
    public function index()
    {
        $this->dataBag->Name = 'hello';
        
        return $this->view();
    }
    
    public function google()
    {
        return $this->redirectToUrl('http://google.com');
    }
}