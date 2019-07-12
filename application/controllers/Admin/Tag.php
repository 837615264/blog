<?php
class Tag extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Tag_model", "tag");
    }

    /*标签列表*/
    function tag_list(){
        $config=$this->config_page();
        $count=$this->tag->get_count();
        $data['page']=$this->get_page(site_url("Admin/Tag/tag_list_ajax"),$count,$config);
        $data['tag_info']=$this->tag->page_all();
        $data['page_now']=1;
        $this->load->view("Admin/tag_list.html",$data);
    }

    /*分页*/
    function tag_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->tag->get_count();
        $data['page']=$this->get_page(site_url("Admin/Tag/tag_list_ajax"),$count,$config);
        $data['tag_info']=$this->tag->page_all($limit);
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*标签添加*/
    function tag_add(){
        if(IS_POST){
            $data=$this->input->post();
            $re=$this->tag->add($data);
                if($re){
                    $result['error']=1;
                    $result['message']="添加成功";
                }else{
                    $result['error']=0;
                    $result['message']="添加失败";
                }
            echo json_encode($result);
        }else{
            $this->load->view("Admin/tag_add.html");
        }
    }

    /*标签删除*/
    function tag_del(){
        $tag_id=$this->input->post("tag_id");
        $res=$this->tag->delete($tag_id);
        if($res){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*标签修改*/
    function tag_update($tag_id=0){
        if(IS_POST){
            $data=$this->input->post();
                $res=$this->tag->save($data);
                if($res){
                    $result['error']=1;
                    $result['message']="修改成功";
                }else{
                    $result['error']=0;
                    $result['message']="修改失败";
                }
            echo json_encode($result);
        }else{
            $data['tag_info']=$this->tag->select_where_one(array("tag_id"=>$tag_id));
            $this->load->view("Admin/tag_update.html",$data);
        }
    }
}