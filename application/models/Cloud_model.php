<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cloud_model extends Common_model
{
    protected $table_name = "cloud";

    /*查询文章对应的标签id*/
    function select_tag($article_id){
        $tags=$this->db
            ->select("tag_id")
            ->get_where("cloud",array("article_id"=>$article_id))
            ->result_array();
        $tag_id=array();
        foreach($tags as $v){
            $tag_id[]=$v['tag_id'];
        }
        return $tag_id;
    }

    /*删除文章对应的标签id*/
    function delete_tag($article_id,$tag_id){
        $this->db->delete("cloud",array("article_id"=>$article_id,"tag_id"=>$tag_id));
        return $this->db->affected_rows();
    }

    /*清空文章对应的标签id*/
    function delete_tag_all($article_id){
        $this->db->delete("cloud",array("article_id"=>$article_id));
        return $this->db->affected_rows();
    }

    /*查询标签内对应的文章信息*/
    function articleInTags($tag_id,$limit=0){
        return $this->db
            ->join("article","cloud.article_id=article.article_id")
            ->limit(config_item("article_page"),$limit)
            ->order_by("article.article_id","asc")
            ->get_where("cloud",array("tag_id"=>$tag_id))
            ->result_array();
    }

    /*标签搜索的文章记录数*/
    function tagForSearch_count($tag_id){
        return $this->db
            ->join("article","cloud.article_id=article.article_id")
            ->where(array("tag_id"=>$tag_id))
            ->count_all_results("cloud");
    }
}