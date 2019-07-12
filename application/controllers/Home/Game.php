<?php
class Game extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Game_model", "game");
        $this->config->load('website');
    }

    /*游戏列表*/
    function game_list($limit=0){
        $data=$this->public_info();
        $data['header']=$this->config->item('header');
        $url=site_url("Home/Game/game_list");
        $config=$this->config_page();
        $count=$this->game->get_count(array("game_trash"=>1));
        $data['page']=$this->get_page($url,$count,$config,"game");
        $data['game']=$this->game->select_home_game($limit);
        foreach($data['game'] as &$v){
            $v['game_content']=htmlspecialchars_decode(str_replace("|","'",$v['game_content']));
            $v['game_add_time']=date("Y-m-d H:i",$v['game_add_time']);
        }
        $this->load->view("Home/games.html",$data);
    }
}