<?php
class Home extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Article_model", "article");
        $this->load->model("Project_model", "project");
        $this->load->model("Comment_model", "comment");
        $this->config->load('website');
    }

    function myTrim($str)
    {
        $search = array(" ","　","\n","\r","\t");
        $replace = array("","","","","");
        return str_replace($search, $replace, $str);
    }

    function index(){
        $data=$this->public_info();
        $data['banner']=$this->config->item('banner');
        $data['header']=$this->config->item('header');
        //首页文章
        $data['article']=$this->article->select_new_limit();
        foreach($data['article'] as &$v){
            $v['article_content']=htmlspecialchars_decode($v['article_content']);
            $v['article_content']=strip_tags($v['article_content']);
            $v['article_content']=$this->myTrim($v['article_content']);
            if(strlen($v['article_content'])>200){
                $v['article_content']=mb_substr($v['article_content'],0,200).'...';
            }
            if(mb_strlen($v['article_title'])>=10){
                $v['article_title']=mb_substr($v['article_title'],0,10).'...';
            }
        }
        //首页项目
        $data['project']=$this->project->project_limit(4);
        //首页评论
        $data['comment']=$this->comment->commentForIndex();

        foreach($data['comment'] as &$v){
            $v['comment_add_time']=date("Y-m-d H:i",$v['comment_add_time']);
            switch($v['comment_type']){
                case '文章':
                    if(mb_strlen($v['article_title'])>=15)
                    {
                        $v['article_title']=mb_substr($v['article_title'],0,15).'...';
                    }
                    if(!empty($v['article_image'])){
                        $ext=$this->substr_ext($v['article_image']);
                        $v['href']=site_url('Home/Article/article').'/'.$v['article_id'];
                        $v['src']=base_url().'public/home/images/thumbs/homeThumb_'.$v['article_id'].$ext;
                    }else{
                        $v['href']=site_url('Home/Article/article').'/'.$v['article_id'];
                        $v['src']=base_url().'public/home/default70.png';
                    }
                    $v['title']=$v['article_title'];
                    ;break;
                case '收藏':
                    if(mb_strlen($v['collect_title'])>=15)
                    {
                        $v['collect_title']=mb_substr($v['collect_title'],0,15).'...';
                    }
                    $ext=$this->substr_ext($v['collect_image']);
                    $v['href']=site_url('Home/Collect/collect').'/'.$v['collect_id'];
                    $v['src']=base_url().'public/home/images/thumbs/collectHome_'.$v['collect_id'].$ext;
                    $v['title']=$v['collect_title'];
                    ;break;
            }

            if(mb_strlen($v['comment_content'])>=25){
                $v['comment_content']=mb_substr($v['comment_content'],0,25).'...';
            }


        }
        $this->load->view("Home/index.html",$data);
    }

    /*发送邮件*/
    function send_email(){
        $ck_time=get_cookie("eml");
        if(empty($ck_time)){
            $data=$this->input->post();
            $this -> load -> library('Email');
            $email=new Email();
            $res=$email->send("{$data['advice_message']}<{$data['advice_email']}>",$data['advice_name']);
            if($res=="yes"){
                $result['status']=1;
                set_cookie("eml",md5($data['advice_email']),180);
            }else{
                $result['status']=0;
            }
        }else{
            $result['status']=2;
        }
        echo json_encode($result);
    }

    /*关于我*/
    function aboutMe(){
        $data=$this->public_info();
        $data['resume'] = $this->config->item('resume');
        $data['header']=$this->config->item('header');
        $this->load->view('Home/about_me.html',$data);
    }
}
?>