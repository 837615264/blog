<?php
class Mood extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mood_model', 'mood');
        $this->config->load('website');
    }

    /*心情列表*/
    function mood_list($limit=0){
        $data=$this->public_info();
        $data['header']=$this->config->item('header');
        $url=site_url("Home/Mood/mood_list");
        $config=$this->config_page();
        $count=$this->mood->get_count(array("mood_trash"=>1));
        $data['page']=$this->get_page($url,$count,$config,"mood");
        $data['mood']=$this->mood->select_home_mood($limit);
        foreach($data['mood'] as &$v){
            $v['mood_content']=htmlspecialchars_decode(str_replace("|","'",$v['mood_content']));
            $v['year']=date("Y",$v['mood_add_time']);
            $v['month']=date("M",$v['mood_add_time']);
            $v['day']=date("d",$v['mood_add_time']);
        }
        $this->load->view('Home/moods.html',$data);
    }
}