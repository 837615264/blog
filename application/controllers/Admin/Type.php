<?php
class Type extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Type_model","type");
    }

    /*分类添加*/
    function type_add(){
        if(IS_POST){
            $data=$this->input->post();
            if($data['parent']=='0'){
                $data['type_path']=0;
                $data['type_parent']=0;
            }else{
                $parent=explode("|",$data['parent']);
                $data['type_path']=$parent[0].'-'.$parent[1];
                $data['type_parent']=$parent[1];
            }
            $data['type_count']=0;
            $res=$this->type->add($data);
            if($res){
                $result['error']=1;
                $result['message']="添加成功";
            }else{
                $result['error']=0;
                $result['message']="添加失败";
            }
            echo json_encode($result);
        }else{
            $data['type']=$this->type->get_type();
            $this->load->view("Admin/type_add.html",$data);
        }
    }

    /*分类列表*/
    function type_list(){
        $data['type']=$this->type->get_type();
        $this->load->view("Admin/type_list.html",$data);
    }

    /*分类修改*/
    function type_update($type_id=0){
        if(IS_POST){
            $data=$this->input->post();
            if($data['parent']=='0'){
                $data['type_path']=0;
                $data['type_parent']=0;
            }else{
                $parent=explode("|",$data['parent']);
                $data['type_path']=$parent[0].'-'.$parent[1];
                $data['type_parent']=$parent[1];
            }

            $res=$this->type->save($data);
            if($res){
                $result['error']=1;
                $result['message']="修改成功";
            }else{
                $result['error']=0;
                $result['message']="修改失败";
            }
            echo json_encode($result);
        }else{
            $data['type_info']=$this->type->select_where_one(array("type_id"=>$type_id));
            $data['type']=$this->type->get_type();
            $this->load->view("Admin/type_update.html",$data);
        }
    }

    /*分类删除*/
    function type_del(){
        $id=$this->input->post("type_id");
        $res=$this->type->delete($id);
        if($res){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }
}