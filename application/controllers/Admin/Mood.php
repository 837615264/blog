<?php
class Mood extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Mood_model", "mood");
        $this->load->model("Trash_model", "trash");
    }

    /*随笔列表*/
    function mood_list(){
        $config=$this->config_page();
        $count=$this->mood->get_count(array("mood_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Mood/mood_list_ajax"),$count,$config);
        $data['mood_info']=$this->mood->select_mood();
        foreach($data['mood_info'] as &$v){
            $v['mood_add_time']=date("Y-m-d H:i:s",$v['mood_add_time']);
        }
        $data['page_now']=1;
        $this->load->view("Admin/mood_list.html",$data);
    }

    /*分页*/
    function mood_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->mood->get_count(array("mood_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Mood/mood_list_ajax"),$count,$config);
        $data['mood_info']=$this->mood->select_mood($limit);
        foreach($data['mood_info'] as &$v){
            $v['mood_add_time']=date("Y-m-d H:i:s",$v['mood_add_time']);
        }
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*添加随笔*/
    function mood_add(){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/mood/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            if($res['status']){
                $data=$this->input->post();
                $data['mood_image']=$res['file_name'];
                $data['mood_add_time']=time();
                $data['mood_content']=str_replace("'","|",$data['mood_content']);
                $data['mood_content']=htmlspecialchars($data['mood_content']);
                $re=$this->mood->add($data);
                if($re){
                    $this->process_image("./public/home/images/mood/{$res['file_name']}",640,250);
                    $result['error']=1;
                    $result['message']="添加成功";
                }else{
                    $result['error']=0;
                    $result['message']="添加失败";
                }
            }else{
                if($res['error']=="<p>You did not select a file to upload.</p>"){
                    $data=$this->input->post();
                    $data['mood_add_time']=time();
                    $data['mood_content']=str_replace("'","|",$data['mood_content']);
                    $data['mood_content']=htmlspecialchars($data['mood_content']);
                    $re=$this->mood->add($data);
                    if($re){
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
            }
            echo json_encode($result);
        }else{
            $this->load->view("Admin/mood_add.html");
        }
    }

    /*随笔删除*/
    function mood_del(){
        $mood_id=$this->input->post("mood_id");
        $res=$this->mood->put_trash($mood_id);
        $re=$this->trash->trash_add($mood_id,"mood");
        if($res&&$re){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*随笔修改*/
    function mood_update($mood_id=0){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/mood/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            $data=$this->input->post();
            if($res['status']){
                $old_image_name=$this->mood->select_image($data['mood_id']);
                unlink("./public/home/images/mood/{$old_image_name['mood_image']}");

                $data['mood_image']=$res['file_name'];
                $data['mood_add_time']=time();
                $data['mood_content']=str_replace("'","|",$data['mood_content']);
                $data['mood_content']=htmlspecialchars($data['mood_content']);
                $re=$this->mood->save($data);
                if($re){
                    $this->process_image("./public/home/images/mood/{$res['file_name']}",640,250);
                    $result['error']=1;
                    $result['message']="修改成功";
                }else{
                    $result['error']=0;
                    $result['message']="修改失败";
                }
            }else{
                if($res['error']=="<p>You did not select a file to upload.</p>"){
                    $data['mood_add_time']=time();
                    $data['mood_content']=str_replace("'","|",$data['mood_content']);
                    $data['mood_content']=htmlspecialchars($data['mood_content']);
                    $re=$this->mood->save($data);
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
            $data['mood_info']=$this->mood->select_where_one(array("mood_id"=>$mood_id));
            $data['mood_info']['mood_content']=str_replace("|","'",$data['mood_info']['mood_content']);
            $data['mood_info']['mood_content']=htmlspecialchars_decode($data['mood_info']['mood_content']);
            $this->load->view("Admin/mood_update.html",$data);
        }
    }
}