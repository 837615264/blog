<?php
class Collect extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Collect_model', 'collect');
        $this->load->model('Comment_model', 'comment');
        $this->config->load('website');
    }

    /*收藏列表*/
    function collect_list($limit=0){
        $data=$this->public_info();
        $data['header']=$this->config->item('header');
        //热门收藏
        $data['collect_hot']=$this->collect->collect_hot();
        foreach($data['collect_hot'] as &$v){
            $ext=$this->substr_ext($v['collect_image']);
            $v['src']= base_url()."public/home/images/thumbs/collectThumb_{$v['collect_id']}$ext";
            $v['hot_comment_num']=$this->comment->commentInCollect($v['collect_id']);
            if(mb_strlen($v['collect_title'])>=18){
                $v['collect_title']=mb_substr($v['collect_title'],0,18).'...';
            }
        }

        $url=site_url("Home/Collect/collect_list");
        $config=$this->config_page();
        $count=$this->collect->get_count(array("collect_trash"=>1));
        $data['page']=$this->get_page($url,$count,$config,"collect");
        $data['collect']=$this->collect->select_home_collect($limit);
        foreach($data['collect'] as &$v){
            $v['collect_content']=htmlspecialchars_decode(str_replace("|","'",$v['collect_content']));
            $v['year']=date("Y",$v['collect_add_time']);
            $v['month']=date("M",$v['collect_add_time']);
            $v['day']=date("d",$v['collect_add_time']);
            if(strlen($v['collect_content'])>200){
                $v['collect_content']=mb_substr($v['collect_content'],0,200).'...';
            }
            if(strpos($v['collect_content'],'<code')!==FALSE &&strpos($v['collect_content'],'</code></pre>')===FALSE){
                $v['collect_content']=$v['collect_content'].'</code></pre>';
            }
            $v['comment_num']=$this->comment->commentInCollect($v['collect_id'],'收藏');
        }
        $this->load->view('Home/collect_list.html',$data);
    }

    /*收藏详情*/
    function collect($collect_id){
        $collect=$this->collect->select_where_one(array("collect_id"=>$collect_id));
        if(isset($collect)){
            $data=$this->public_info($collect['collect_title']);
            $data['header']=$this->config->item('header');
            $collect['year']=date("Y",$collect['collect_add_time']);
            $collect['month']=date("M",$collect['collect_add_time']);
            $collect['day']=intval(date("d",$collect['collect_add_time']));
            $collect['collect_content']=htmlspecialchars_decode(str_replace("|","'",$collect['collect_content']));
            $collect['ext']=$this->substr_ext($collect['collect_image']);
            //点击量+1
            $this->collect->hits_add($collect_id);
            $data['collect']=$collect;
            $data['comment']=$this->comment->select_comment($collect_id,'收藏');
            foreach($data['comment'] as &$v){
                $v['comment_add_time']=date("Y-m-d H:i",$v['comment_add_time']);
            }
            $this->load->view("Home/collect.html",$data);
        }else{
            $this->load->view('404.html');
        }
    }

    /*附件下载*/
    function download($accessory=""){
        set_time_limit(0);
        $file_name = $accessory;
        $file_dir = base_url().'accessory/';
        $file = @ fopen($file_dir . $file_name,"r");
        if (!$file) {
            $this->load->view('404.html');
        } else {
            Header("Content-type: application/octet-stream");
            Header("Content-Disposition: attachment; filename=" . $file_name);
            while (!feof ($file)) {
                echo fread($file,50000);
            }
            fclose ($file);
        }
    }
}