<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trash_model extends Common_model
{
    protected $table_name = "trash";
    protected $table_id = "trash_id";

    /*放入回收站*/
    function trash_add($id,$type){
        $trash=$this->db
            ->select("{$type}_title,{$type}_content,{$type}_image")
            ->where(array("{$type}_id"=>$id))
            ->get("$type")
            ->row_array();
        switch($type){
            case "article":$trash['trash_type']="文章";break;
            case "mood":$trash['trash_type']="随笔";break;
            case "collect":$trash['trash_type']="收藏";break;
            case "project":$trash['trash_type']="项目";break;
            case "game":$trash['trash_type']="游戏";break;
        }
        $trash['trash_title']=$trash["{$type}_title"];
        $trash['trash_content']=$trash["{$type}_content"];
        $trash['trash_image']=$trash["{$type}_image"];
        $trash['trash_del_time']=time();
        $trash['trash_old_id']=$id;
        return $this->add($trash);
    }
}