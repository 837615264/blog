<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends Common_model
{
    protected $table_name = "comment";
    protected $table_id = "comment_id";

    /*删除对应文章下的所有评论*/
    function delete_comment_all($article_id){
        $this->db
            ->delete('comment',array('article_id'=>$article_id,'comment_type'=>'文章'));
        return $this->db->affected_rows();
    }

    /*根据文章id查询评论*/
    function select_comment($article_id,$type){
        return $this->db
            ->get_where("comment",array("article_id"=>$article_id,'comment_type'=>$type))
            ->result_array();
    }

    /*查询文章下的评论数量*/
    function commentInArticle($article_id){
        return $this->db
            ->where(array("article_id"=>$article_id,'comment_type'=>'文章'))
            ->count_all_results("comment");
    }

    /*查询收藏下的评论数量*/
    function commentInCollect($collect_id){
        return $this->db
            ->where(array("article_id"=>$collect_id,'comment_type'=>'收藏'))
            ->count_all_results("comment");
    }

    /*查询首页最新评论*/
    function commentForIndex(){
        $comments=$this->db
            ->select('comment_add_time,comment_content,comment_user,comment_type,comment_id')
            ->limit(3)
            ->order_by("comment_add_time","desc")
            ->get("comment")
            ->result_array();
        foreach($comments as &$v){
            switch($v['comment_type']){
                case '文章':
                    $data=$this->db
                        ->select('article_title,article.article_id,article_image')
                        ->join('article','article.article_id=comment.article_id')
                        ->get_where('comment',array('comment_id'=>$v['comment_id']))
                        ->row_array();
                        $v=array_merge($v,$data);
                    ;break;
                case '收藏':
                    $data=$this->db
                        ->select('collect_title,collect.collect_id,collect_image')
                        ->join('collect','collect.collect_id=comment.article_id')
                        ->get_where('comment',array('comment_id'=>$v['comment_id']))
                        ->row_array();
                    $v=array_merge($v,$data);
                    ;break;
            }
        }
        return $comments;
    }
}