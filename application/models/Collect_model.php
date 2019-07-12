<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect_model extends Common_model
{
    protected $table_name = "collect";
    protected $table_id = "collect_id";

    /*查询文章*/
    function select_collect($limit=0){
        return $this->db
            ->where("collect_trash=1")
            ->limit(config_item("admin_page"),$limit)
            ->get("collect")
            ->result_array();
    }

    /*查询附件*/
    function select_accessory($id){
        return $this->db
            ->select("collect_accessory")
            ->get_where("collect",array("collect_id"=>$id))
            ->row_array();
    }

    /*查询前台收藏*/
    function select_home_collect($limit=0){
        return $this->db
            ->where('collect_trash=1')
            ->order_by('collect_add_time','desc')
            ->limit(config_item('collect_page'),$limit)
            ->get('collect')
            ->result_array();
    }

    /*查询十篇热门收藏*/
    function collect_hot(){
        return $this->db
            ->select("collect_id,collect_image,collect_title,collect_hits")
            ->limit(10)
            ->order_by("collect_hits","desc")
            ->where("collect_hits > 0")
            ->get("collect")
            ->result_array();
    }
}