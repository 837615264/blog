<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mood_model extends Common_model
{
    protected $table_name = "mood";
    protected $table_id = "mood_id";

    /*查询随笔*/
    function select_mood($limit=0){
        return $this->db
            ->where("mood_trash=1")
            ->limit(config_item("admin_page"),$limit)
            ->get("mood")
            ->result_array();
    }

    /*查询前台随笔*/
    function select_home_mood($limit=0){
        return $this->db
            ->where('mood_trash=1')
            ->order_by('mood_add_time','desc')
            ->limit(config_item('mood_page'),$limit)
            ->get('mood')
            ->result_array();
    }
}