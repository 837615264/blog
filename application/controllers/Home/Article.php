<?php
class Article extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Article_model", "article");
        $this->load->model("Comment_model", "comment");
        $this->load->model("Tag_model", "tag");
        $this->load->model("Cloud_model", "cloud");
        $this->config->load('website');
    }

    /*文章列表*/
    function article_type($limit=0,$type_id="ini"){
        $data=$this->public_info();
        $data['header']=$this->config->item('header');
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
        /*侧边栏标签云*/
        $data['tag_cloud']=$this->tag->tag_all();
        $url=site_url("Home/Article/article_type");
        $config=$this->config_page();
        if($type_id > 0 && $type_id != "ini"){
            set_cookie("article_type",$type_id,7200);
            $data['article']=$this->article->select_article_type($type_id,$limit);
            $count=$this->article->count_article_type($type_id);
        }
        if($type_id == 0 && $type_id != "ini"){
            set_cookie("article_type",$type_id,7200);
            $data['article']=$this->article->select_article_home($limit);
            $count=$this->article->count_article();
        }
        if($type_id=="ini"){
            $type_id=get_cookie("article_type");
            if(isset($type_id)){
                if($type_id > 0){
                    $data['article']=$this->article->select_article_type($type_id,$limit);
                    $count=$this->article->count_article_type($type_id);
                }else{
                    $data['article']=$this->article->select_article_home($limit);
                    $count=$this->article->count_article();
                }
            }else{
                $data['article']=$this->article->select_article_home($limit);
                $count=$this->article->count_article();
            }
        }
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
            if(strpos($v['article_content'],'<pre')!==FALSE &&strpos($v['article_content'],'</code></pre>')===FALSE){
                $v['article_content']=$v['article_content'].'"></code></pre>';
            }
            if(strrpos($v['article_content'],'</pre>')!==FALSE){
                $v['article_content']=substr($v['article_content'],0,strrpos($v['article_content'],'</pre>')+6);
            }

            $v['comment_num']=$this->comment->commentInArticle($v['article_id']);
        }
        $this->load->view("Home/article_list.html",$data);
    }

    /*文章详情*/
    function article($article_id){
        $article=$this->article->select_where_one(array("article_id"=>$article_id));
        if(isset($article)){
            $data=$this->public_info($article['article_title']);
            $data['header']=$this->config->item('header');
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

            $article['year']=date("Y",$article['article_add_time']);
            $article['month']=date("M",$article['article_add_time']);
            $article['day']=intval(date("d",$article['article_add_time']));
            $article['article_content']=htmlspecialchars_decode(str_replace("|","'",$article['article_content']));
            $article['article_tag']=$this->tag->tagForArticle($article['article_tag']);
            /*侧边栏标签云*/
            $data['tag_cloud']=$this->tag->tag_all();
            //点击量+1
            $this->article->hits_add($article_id);
            $data['article']=$article;
            $data['comment']=$this->comment->select_comment($article_id,'文章');
            foreach($data['comment'] as &$v){
                $v['comment_add_time']=date("Y-m-d H:i",$v['comment_add_time']);
            }
            $this->load->view("Home/article.html",$data);
        }else{
            $this->load->view('404.html');
        }
    }

    /*文章搜索*/
    function article_search($limit=0){
        $keyword=$this->input->get('keyword');
        $url=site_url("Home/Article/article_search");
        $config=$this->config_page();
        $count=$this->article->count_article("article_title like '%$keyword%'");
        $data=$this->public_info();
        $data['header']=$this->config->item('header');
        /*侧边栏标签云*/
        $data['tag_cloud']=$this->tag->tag_all();
        $data['article']=$this->article->select_article($limit,"article_title like '%$keyword%'");
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
