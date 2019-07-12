<?php
class Project extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Project_model", "project");
        $this->load->model("Trash_model", "trash");
    }

    /*项目列表*/
    function project_list(){
        $config=$this->config_page();
        $count=$this->project->get_count(array("project_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Project/project_list_ajax"),$count,$config);
        $data['project_info']=$this->project->select_project();
        foreach($data['project_info'] as &$v){
            $v['project_add_time']=date("Y-m-d H:i:s",$v['project_add_time']);
        }
        $data['page_now']=1;
        $this->load->view("Admin/project_list.html",$data);
    }

    /*分页*/
    function project_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->project->get_count(array("project_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Project/project_list_ajax"),$count,$config);
        $data['project_info']=$this->project->select_project($limit);
        foreach($data['project_info'] as &$v){
            $v['project_add_time']=date("Y-m-d H:i:s",$v['project_add_time']);
        }
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*项目删除*/
    function project_del(){
        $project_id=$this->input->post("project_id");
        $res=$this->project->put_trash($project_id);
        $re=$this->trash->trash_add($project_id,"project");
        if($res&&$re){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*项目添加*/
    function project_add(){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/project/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            if($res['status']){
                $data=$this->input->post();
                $data['project_image']=$res['file_name'];
                $data['project_add_time']=time();
                $data['project_hits']=0;
                $data['project_up']=0;
                $data['project_trash']=1;
                $data['project_content']=str_replace("'","|",$data['project_content']);
                $data['project_content']=htmlspecialchars($data['project_content']);
                $re=$this->project->add($data);
                if($re){
                    $result['error']=1;
                    $result['message']="添加成功";
                    $this->process_image("./public/home/images/project/{$res['file_name']}",460,260);
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
            $this->load->view("Admin/project_add.html");
        }
    }

    /*项目修改*/
    function project_update($project_id=0){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/project/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            $data=$this->input->post();
            if($res['status']){

                $old_image_name=$this->project->select_image($data['project_id']);
                unlink("./public/home/images/project/{$old_image_name['project_image']}");

                $data['project_image']=$res['file_name'];
                $data['project_add_time']=time();
                $data['project_content']=str_replace("'","|",$data['project_content']);
                $data['project_content']=htmlspecialchars($data['project_content']);
                $re=$this->project->save($data);
                if($re){
                    $result['error']=1;
                    $result['message']="修改成功";
                    $this->process_image("./public/home/images/project/{$res['file_name']}",460,260);
                }else{
                    $result['error']=0;
                    $result['message']="修改失败";
                }
            }else{
                if($res['error']=="<p>You did not select a file to upload.</p>"){
                    $data['project_add_time']=time();
                    $data['project_content']=str_replace("'","|",$data['project_content']);
                    $data['project_content']=htmlspecialchars($data['project_content']);
                    $re=$this->project->save($data);
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
            $data['project_info']=$this->project->select_where_one(array("project_id"=>$project_id));
            $data['project_info']['project_content']=str_replace("|","'",$data['project_info']['project_content']);
            $data['project_info']['project_content']=htmlspecialchars_decode($data['project_info']['project_content']);
            $this->load->view("Admin/project_update.html",$data);
        }
    }

    /*后台展示三个项目图片*/
    function project_limit(){
        $project_image=$this->project->project_limit(3);
        echo json_encode($project_image);
    }
}