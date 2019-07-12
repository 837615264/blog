<?php
class Game extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Game_model", "game");
        $this->load->model("Trash_model", "trash");
    }

    /*游戏列表*/
    function game_list(){
        $config=$this->config_page();
        $count=$this->game->get_count(array("game_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Game/game_list_ajax"),$count,$config);
        $data['game_info']=$this->game->select_game();
        foreach($data['game_info'] as &$v){
            $v['game_add_time']=date("Y-m-d H:i:s",$v['game_add_time']);
        }
        $data['page_now']=1;
        $this->load->view("Admin/game_list.html",$data);
    }

    /*分页*/
    function game_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->game->get_count(array("game_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Game/game_list_ajax"),$count,$config);
        $data['game_info']=$this->game->select_game($limit);
        foreach($data['game_info'] as &$v){
            $v['game_add_time']=date("Y-m-d H:i:s",$v['game_add_time']);
        }
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*游戏删除*/
    function game_del(){
        $game_id=$this->input->post("game_id");
        $res=$this->game->put_trash($game_id);
        $re=$this->trash->trash_add($game_id,"game");
        if($res&&$re){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*游戏添加*/
    function game_add(){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/game/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            if($res['status']){
                $data=$this->input->post();
                $data['game_image']=$res['file_name'];
                $data['game_add_time']=time();
                $data['game_content']=str_replace("'","|",$data['game_content']);
                $data['game_content']=htmlspecialchars($data['game_content']);
                $re=$this->game->add($data);
                if($re){
                    $this->process_image("./public/home/images/game/{$res['file_name']}",460,259);
                    $result['error']=1;
                    $result['message']="添加成功";
                }else{
                    $result['error']=0;
                    $result['message']="添加失败";
                }
            }else{
                $result['error']=0;
                $result['message']=$res['error'];
            }
            echo json_encode($result);
        }else{
            $this->load->view("Admin/game_add.html");
        }
    }

    /*游戏修改*/
    function game_update($game_id=0){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/game/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            $data=$this->input->post();
            if($res['status']){
                $old_image_name=$this->game->select_image($data['game_id']);
                unlink("./public/home/images/game/{$old_image_name['game_image']}");

                $data['game_image']=$res['file_name'];
                $data['game_add_time']=time();
                $data['game_content']=str_replace("'","|",$data['game_content']);
                $data['game_content']=htmlspecialchars($data['game_content']);
                $re=$this->game->save($data);
                if($re){
                    $this->process_image("./public/home/images/game/{$res['file_name']}",460,259);
                    $result['error']=1;
                    $result['message']="修改成功";
                }else{
                    $result['error']=0;
                    $result['message']="修改失败";
                }
            }else{
                if($res['error']=="<p>You did not select a file to upload.</p>"){
                    $data['game_add_time']=time();
                    $data['game_content']=str_replace("'","|",$data['game_content']);
                    $data['game_content']=htmlspecialchars($data['game_content']);
                    $re=$this->game->save($data);
                    if($re){
                        $result['error']=1;
                        $result['message']="修改成功";
                    }else{
                        $result['error']=0;
                        $result['message']="修改失败";
                    }
                }else{
                    $result['error']=0;
                    $result['message']=$res['error'];
                }
            }
            echo json_encode($result);
        }else{
            $data['game_info']=$this->game->select_where_one(array("game_id"=>$game_id));
            $data['game_info']['game_content']=str_replace("|","'",$data['game_info']['game_content']);
            $data['game_info']['game_content']=htmlspecialchars_decode($data['game_info']['game_content']);
            $this->load->view("Admin/game_update.html",$data);
        }
    }
}