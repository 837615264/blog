<?php
class Collect extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Collect_model", "collect");
        $this->load->model("Trash_model", "trash");
    }

    /*收藏列表*/
    function collect_list(){
        $config=$this->config_page();
        $count=$this->collect->get_count(array("collect_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Collect/collect_list_ajax"),$count,$config);
        $data['collect_info']=$this->collect->select_collect();
        foreach($data['collect_info'] as &$v){
            $v['collect_add_time']=date("Y-m-d H:i:s",$v['collect_add_time']);
        }
        $data['page_now']=1;
        $this->load->view("Admin/collect_list.html",$data);
    }

    /*分页*/
    function collect_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->collect->get_count(array("collect_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Collect/collect_list_ajax"),$count,$config);
        $data['collect_info']=$this->collect->select_collect($limit);
        foreach($data['collect_info'] as &$v){
            $v['collect_add_time']=date("Y-m-d H:i:s",$v['collect_add_time']);
        }
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*收藏删除*/
    function collect_del(){
        $collect_id=$this->input->post("collect_id");
        $res=$this->collect->put_trash($collect_id);
        $re=$this->trash->trash_add($collect_id,"collect");
        if($res&&$re){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*收藏添加*/
    function collect_add(){
        if(IS_POST){
            //上传展示图片
            $config['upload_path']="./public/home/images/collect/";
            $config['allowed_types']='gif|jpg|png|jpeg';
            $config['encrypt_name']=TRUE;
            $res_file=$this->do_upload("file",$config);
            if($res_file['status']){
                    $data=$this->input->post();
                    $data['collect_image']=$res_file['file_name'];
                    $data['collect_add_time']=time();
                    $data['collect_content']=str_replace("'","|",$data['collect_content']);
                    $data['collect_content']=htmlspecialchars($data['collect_content']);
                    $re=$this->collect->add($data);
                    if($re){
                        $this->process_image("./public/home/images/collect/{$res_file['file_name']}",640,250);
                        //收藏图片缩略图(50*50)
                        $this->creat_thumb("./public/home/images/collect/{$res_file['file_name']}","./public/home/images/thumbs/collectThumb_$re{$res_file['file_ext']}",50,50);
                        //收藏图片缩略图(70*70)
                        $this->creat_thumb("./public/home/images/collect/{$res_file['file_name']}","./public/home/images/thumbs/collectHome_$re{$res_file['file_ext']}",70,70);
                        //收藏图片缩略图(850*360)详情图片
                        $this->creat_thumb("./public/home/images/collect/{$res_file['file_name']}","./public/home/images/thumbs/collectFullThumb_$re{$res_file['file_ext']}",850,360);
                        $result['error']=1;
                        $result['id']=$re;
                        $result['message']="添加成功";
                    }else{
                        $result['error']=0;
                        $result['message']="添加失败";
                    }
            }else{
                $result['error']=0;
                $result['message']=$res_file['error'];
            }
            echo json_encode($result);
        }else{
            $this->load->view("Admin/collect_add.html");
        }
    }

    /*上传附件*/
    function file_add(){
        //上传附件
        $config['upload_path']="./accessory/";
        $config['allowed_types']='gif|jpg|png|jpeg|exe|zip|xls|xlsx|doc|docx|txt|php|rar|flv';
        $res_collect=$this->do_upload("collect",$config);
        if($res_collect['status']){
            $result['error']=1;
            $result['message']="添加成功";
            $id=$this->input->post('id');
            $this->collect->save(array('collect_id'=>$id,'collect_accessory'=>$res_collect['file_name']));
        }else{
            $result['error']=0;
            $result['message']=$res_collect['error'];
        }
        echo json_encode($result);
    }

    /*上传附件（修改）*/
    function file_update(){
        //上传附件
        $config['upload_path']="./accessory/";
        $config['allowed_types']='gif|jpg|png|jpeg|exe|zip|xls|xlsx|doc|docx|txt|php|rar';
        $res_collect=$this->do_upload("collect",$config);
        if($res_collect['status']){
            $id=$this->input->post('collect_id');
            $old=$this->collect->select_one($id);
            unlink("./accessory/{$old['collect_accessory']}");
            $re=$this->collect->save(array('collect_id'=>$id,'collect_accessory'=>$res_collect['file_name']));
            if($re){
                $result['error']=1;
                $result['message']="修改成功";
            }else{
                $result['error']=0;
                $result['message']="修改失败";
            }
        }else{
            if($res_collect['error']=="<p>You did not select a file to upload.</p>"){
                $result['error']=1;
                $result['message']='修改成功';
            }else{
                $result['error']=0;
                $result['message']=$res_collect['error'];
            }
        }
        echo json_encode($result);
    }
    /*收藏修改*/
    function collect_update($collect_id=0){
        if(IS_POST){
            //上传展示图片
            $data=$this->input->post();
            $config['upload_path']="./public/home/images/collect/";
            $config['allowed_types']='gif|jpg|png|jpeg';
            $config['encrypt_name']=TRUE;
            $res_file=$this->do_upload("file",$config);
            if($res_file['status']){
                $old=$this->collect->select_one($data['collect_id']);
                unlink("./public/home/images/collect/{$old['collect_image']}");

                $data['collect_image']=$res_file['file_name'];
                $data['collect_add_time']=time();
                $data['collect_content']=str_replace("'","|",$data['collect_content']);
                $data['collect_content']=htmlspecialchars($data['collect_content']);
                $re=$this->collect->save($data);
                if($re){
                    $this->process_image("./public/home/images/collect/{$res_file['file_name']}",640,250);
                    //收藏图片缩略图(50*50)
                    $this->creat_thumb("./public/home/images/collect/{$res_file['file_name']}","./public/home/images/thumbs/collectThumb_{$data['collect_id']}{$res_file['file_ext']}",50,50);
                    //收藏图片缩略图(70*70)
                    $this->creat_thumb("./public/home/images/collect/{$res_file['file_name']}","./public/home/images/thumbs/collectHome_{$data['collect_id']}{$res_file['file_ext']}",70,70);
                    //收藏图片缩略图(850*360)详情图片
                    $this->creat_thumb("./public/home/images/collect/{$res_file['file_name']}","./public/home/images/thumbs/collectFullThumb_{$data['collect_id']}{$res_file['file_ext']}",850,360);
                    $result['error']=1;
                    $result['message']="修改成功";
                }else{
                    $result['error']=0;
                    $result['message']="修改失败";
                }
            }else{
                if($res_file['error']=="<p>You did not select a file to upload.</p>"){
                    $data=$this->input->post();
                    $data['collect_add_time']=time();
                    $data['collect_content']=str_replace("'","|",$data['collect_content']);
                    $data['collect_content']=htmlspecialchars($data['collect_content']);
                    $re=$this->collect->save($data);
                    if($re){
                        $result['error']=1;
                        $result['message']="修改成功";
                    }else{
                        $result['error']=0;
                        $result['message']="修改失败";
                    }
                }else{
                    $result['error']=0;
                    $result['message']=$res_file['error'];
                }
            }
            echo json_encode($result);
        }else{
            $data['collect_info']=$this->collect->select_where_one(array("collect_id"=>$collect_id));
            $data['collect_info']['collect_content']=str_replace("|","'",$data['collect_info']['collect_content']);
            $data['collect_info']['collect_content']=htmlspecialchars_decode($data['collect_info']['collect_content']);
            $this->load->view("Admin/collect_update.html",$data);
        }
    }
}