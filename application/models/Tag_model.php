<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends Common_model
{
    protected $table_name = "tag";
    protected $table_id = "tag_id";

    /*根据文章id查询范围内的标签*/
    function tagForArticle($tags){
        if(!empty($tags)){
            $tags=explode(",",$tags);
            return $this->db
                ->where_in("tag_id",$tags)
                ->order_by("tag_hits","desc")
                ->get("tag")
                ->result_array();
        }
    }

    /*查询标签云，按点击量排序*/
    function tag_all(){
        return $this->db
            ->order_by("tag_hits","desc")
            ->get("tag")
            ->result_array();
    }
}