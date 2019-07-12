<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends Common_model
{
    protected $table_name = "project";
    protected $table_id = "project_id";

    /*查询项目列表*/
    function select_project($limit=0){
        return $this->db
            ->where("project_trash=1")
            ->limit(config_item("admin_page"),$limit)
            ->get("project")
            ->result_array();
    }

    /*查询指定数量的项目图片*/
    function project_limit($num=0){
        return $this->db
            ->order_by('project_add_time','desc')
            ->limit($num)
            ->select("project_image,project_title,project_url")
            ->get("project")
            ->result_array();
    }
}