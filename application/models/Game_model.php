<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Game_model extends Common_model
{
    protected $table_name = "game";
    protected $table_id = "game_id";

    /*查询游戏列表*/
    function select_game($limit=0){
        return $this->db
            ->where("game_trash=1")
            ->limit(config_item("admin_page"),$limit)
            ->get("game")
            ->result_array();
    }

    /*查询游戏前台列表*/
    function select_home_game($limit=0){
        return $this->db
            ->where('game_trash=1')
            ->order_by('game_add_time','desc')
            ->limit(config_item('game_page'),$limit)
            ->get('game')
            ->result_array();
    }
}