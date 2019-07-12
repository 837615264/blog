<?php
class Resume extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /*个人中心*/
    function resume(){
        $this->load->view("Admin/resume.html");
    }
}