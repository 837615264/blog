<?php
class Project extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Project_model", "project");
    }

    function project(){
        $data=$this->public_info();
        $data['project']=$this->project->select_all();
        $this->load->view("Home/project.html",$data);
    }
}