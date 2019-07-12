<?php
class Tag extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Tag_model", "tag");
        $this->load->model("Article_model", "article");
        $this->load->model("Cloud_model","cloud");
        $this->load->model("Comment_model","comment");
        $this->config->load('website');
    }

    /*标签搜索*/
    function tag_search($tag_id){
        $this->tag->hits_add($tag_id);
        set_cookie("tag_id",$tag_id,7200);
        redirect("Home/Tag/tag_list");
    }

    /*标签搜索列表*/
    function tag_list($limit=0){
        $tag_id=get_cookie("tag_id");
        $url=site_url("Home/Tag/tag_list");
        $config=$this->config_page();
        $count=$this->cloud->tagForSearch_count($tag_id);
        $data=$this->public_info();
        $data['header']=$this->config->item('header');
        /*侧边栏标签云*/
        $data['tag_cloud']=$this->tag->tag_all();
        $data['article']=$this->cloud->articleInTags($tag_id,$limit);
        $data['page']=$this->get_page($url,$count,$config);
        foreach($data['article'] as &$v){
            $v['year']=date("Y",$v['article_add_time']);
            $v['month']=date("M",$v['article_add_time']);
            $v['day']=intval(date("d",$v['article_add_time']));
            $v['article_content']=htmlspecialchars_decode(str_replace("|","'",$v['article_content']));

            if(strlen($v['article_content'])>200){
                $v['article_content']=mb_substr($v['article_content'],0,200).'...';
            }
            if(strpos($v['article_content'],'<code')!==FALSE &&strpos($v['article_content'],'</code></pre>')===FALSE){
                $v['article_content']=$v['article_content'].'</code></pre>';
            }
            if(strrpos($v['article_content'],'</pre>')!==FALSE){
                $v['article_content']=substr($v['article_content'],0,strrpos($v['article_content'],'</pre>')+6);
            }

            $v['comment_num']=$this->comment->commentInArticle($v['article_id']);
        }

        $data['article_hot']=$this->article->article_hot();
        foreach($data['article_hot'] as &$v){
            if(empty($v['article_image'])){
                $v['src']=base_url().'public/home/default50.png';
            }else{
                $ext=$this->substr_ext($v['article_image']);
                $v['src']=base_url()."public/home/images/thumbs/articleThumb_{$v['article_id']}$ext";
            }
            $v['hot_comment_num']=$this->comment->commentInArticle($v['article_id']);
            if(mb_strlen($v['article_title'])>=18){
                $v['article_title']=mb_substr($v['article_title'],0,18).'...';
            }
        }
        $this->load->view("Home/article_list.html",$data);
    }
}