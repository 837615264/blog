<?php
class Comment extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Comment_model", "comment");
    }

    /*添加评论*/
    function comment_add(){
        $data=$this->input->post();
        $ck_time=get_cookie("cmt");
        if(empty($ck_time)){
            set_cookie("cmt",md5($data['comment_email']),180);
            $data['comment_add_time']=time();
            $res=$this->comment->add($data);
            if($res){
                $result['status']=1;
                $data['comment_add_time']=date("Y-m-d H:i",time());
                $result['message']=$data;
            }
        }else{
            $result['status']=0;
            $result['error']="三分钟内只能评论一次，请稍后再试";
        }
        echo json_encode($result);
    }
}