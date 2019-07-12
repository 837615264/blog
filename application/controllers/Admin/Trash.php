<?php
class Trash extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Trash_model", "trash");
        $this->load->model("Article_model", "article");
        $this->load->model("Mood_model", "mood");
        $this->load->model("Collect_model", "collect");
        $this->load->model("Project_model", "project");
        $this->load->model("Game_model", "game");
        $this->load->model("Cloud_model", "cloud");
        $this->load->model("Comment_model", "comment");
    }

    /*回收站*/
    function trash_list(){
        $config=$this->config_page();
        $count=$this->trash->get_count();
        $data['page']=$this->get_page(site_url("Admin/Trash/trash_list_ajax"),$count,$config);
        $data['trash_info']=$this->trash->page_all();
        foreach($data['trash_info'] as &$v){
            switch($v['trash_type']){
                case "文章":$v['trash_image']=base_url().'public/home/images/article/'.$v['trash_image'];break;
                case "随笔":$v['trash_image']=base_url().'public/home/images/mood/'.$v['trash_image'];break;
                case "收藏":$v['trash_image']=base_url().'public/home/images/collect/'.$v['trash_image'];break;
                case "项目":$v['trash_image']=base_url().'public/home/images/project/'.$v['trash_image'];break;
                case "游戏":$v['trash_image']=base_url().'public/home/images/game/'.$v['trash_image'];break;
            }
        }
        $data['page_now']=1;
        $this->load->view("Admin/trash.html",$data);
    }

    /*分页*/
    function trash_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->trash->get_count();
        $data['page']=$this->get_page(site_url("Admin/Trash/trash_list_ajax"),$count,$config);
        $data['trash_info']=$this->trash->page_all($limit);
        foreach($data['trash_info'] as &$v){
            switch($v['trash_type']){
                case "文章":$v['trash_image']=base_url().'public/home/images/article/'.$v['trash_image'];break;
                case "随笔":$v['trash_image']=base_url().'public/home/images/mood/'.$v['trash_image'];break;
                case "收藏":$v['trash_image']=base_url().'public/home/images/collect/'.$v['trash_image'];break;
                case "项目":$v['trash_image']=base_url().'public/home/images/project/'.$v['trash_image'];break;
                case "游戏":$v['trash_image']=base_url().'public/home/images/game/'.$v['trash_image'];break;
            }
        }
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*删除*/
    function trash_del(){
        $info=$this->input->post();
        if($info['type']=="文章"){
            $img=$this->article->select_image($info['old_id']);
            if(!empty($img['article_image'])){
                unlink("./public/home/images/article/{$img['article_image']}");
                $ext=$this->substr_ext($img['article_image']);
                unlink("./public/home/images/thumbs/homeThumb_{$info['old_id']}$ext");
                unlink("./public/home/images/thumbs/articleThumb_{$info['old_id']}$ext");
            }
            $res=$this->article->delete($info['old_id']);
            $this->cloud->delete_tag_all($info['old_id']);
            $this->comment->delete_comment_all($info['old_id']);
        }
        if($info['type']=="随笔"){
            $img=$this->mood->select_image($info['old_id']);
            if(!empty($img['mood_image'])){
                unlink("./public/home/images/mood/{$img['mood_image']}");
            }
            $res=$this->mood->delete($info['old_id']);
        }
        if($info['type']=="收藏"){
            $img=$this->collect->select_image($info['old_id']);
            unlink("./public/home/images/collect/{$img['collect_image']}");
            $ext=$this->substr_ext($img['collect_image']);
            unlink("./public/home/images/thumbs/collectThumb_{$info['old_id']}$ext");
            unlink("./public/home/images/thumbs/collectHome_{$info['old_id']}$ext");
            unlink("./public/home/images/thumbs/collectFullThumb_{$info['old_id']}$ext");
            $accessory=$this->collect->select_accessory($info['old_id']);
            unlink("./accessory/{$accessory['collect_accessory']}");

            $res=$this->collect->delete($info['old_id']);
        }
        if($info['type']=="项目"){
            $img=$this->project->select_image($info['old_id']);
            unlink("./public/home/images/project/{$img['project_image']}");
            $res=$this->project->delete($info['old_id']);
        }
        if($info['type']=="游戏"){
            $img=$this->game->select_image($info['old_id']);
            unlink("./public/home/images/game/{$img['game_image']}");
            $res=$this->game->delete($info['old_id']);
        }
        $re=$this->trash->delete($info['trash_id']);
        if($res&&$re){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*恢复*/
    function trash_recover(){
        $info=$this->input->post();
        if($info['type']=="文章"){
            $res=$this->article->get_trash($info['old_id']);
        }
        if($info['type']=="随笔"){
            $res=$this->mood->get_trash($info['old_id']);
        }
        if($info['type']=="收藏"){
            $res=$this->collect->get_trash($info['old_id']);
        }
        if($info['type']=="项目"){
            $res=$this->project->get_trash($info['old_id']);
        }
        if($info['type']=="游戏"){
            $res=$this->game->get_trash($info['old_id']);
        }
        $re=$this->trash->delete($info['trash_id']);
        if($res&&$re){
            $result['error']=1;
            $result['message']="恢复成功";
        }else{
            $result['error']=0;
            $result['message']="恢复失败";
        }
        echo json_encode($result);
    }
}