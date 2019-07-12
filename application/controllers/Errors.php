<?php
class Errors extends CI_Controller
{
    function not_found(){
        $this->load->view('404.html');
    }
}