<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends Common_model
{
    protected $table_name = "article";
    protected $table_id = "article_id";

    /*首页查询四条最新文章*/
    function select_new_limit(){
        return $this->db
            ->select("article_id,article_title,article_content")
            ->limit(4)
            ->order_by("article_add_time","desc")
            ->get_where("article",array("article_trash"=>1))
            ->result_array();
    }

    /*查询文章*/
    function select_article($limit=0,$where="1=1"){
        return $this->db
            ->join("type","article.type_id=type.type_id")
            ->where($where)
            ->where("article_trash=1")
            ->limit(config_item("admin_page"),$limit)
            ->get("article")
            ->result_array();
    }

    /*查询前台文章*/
    function select_article_home($limit=0){
        return $this->db
            ->join("type","article.type_id=type.type_id")
            ->where("article_trash=1")
            ->limit(config_item("article_page"),$limit)
            ->order_by('article_add_time','desc')
            ->get("article")
            ->result_array();
    }

    /*查询对应分类的文章*/
    function select_article_type($type_id,$limit=0){
        return $this->db
            ->join("type","article.type_id=type.type_id")
            ->where(array("article_trash"=>1,"type.type_id"=>$type_id))
            ->limit(config_item("article_page"),$limit)
            ->order_by('article_add_time','desc')
            ->get("article")
            ->result_array();
    }

    /*查询分类下文章记录数*/
    function count_article_type($type_id,$where="1=1"){
        return $this->db
            ->join("type","article.type_id=type.type_id")
            ->where($where)
            ->where(array("article_trash"=>1,"type.type_id"=>$type_id))
            ->count_all_results("article");
    }

    /*查询文章记录数*/
    function count_article($where="1=1"){
        return $this->db
            ->join("type","article.type_id=type.type_id")
            ->where($where)
            ->where(array("article_trash"=>1))
            ->count_all_results("article");
    }

    /*查询十篇热门文章*/
    function article_hot(){
        return $this->db
            ->select("article_id,article_image,article_title,article_hits")
            ->limit(10)
            ->order_by("article_hits","desc")
            ->where("article_hits > 0")
            ->get("article")
            ->result_array();
    }
}