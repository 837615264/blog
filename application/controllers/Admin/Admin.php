<?php
class Admin extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->config->load('website');
    }

    /*后台首页*/
    function index(){
        $this->load->view("Admin/index.html");
    }

    function admin_ready(){
        $data['article']=$this->db->count_all_results("article");
        $data['mood']=$this->db->count_all_results("mood");
        $data['resume']=$resume_config=$this->config->item('resume');
        echo json_encode($data);
    }
}