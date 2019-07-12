<?php
class Article extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Article_model", "article");
        $this->load->model("Type_model","type");
        $this->load->model("Tag_model","tag");
        $this->load->model("Cloud_model","cloud");
        $this->load->model("Trash_model","trash");
    }

    /*文章列表*/
    function article_list(){
        $config=$this->config_page();
        $count=$this->article->get_count(array("article_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Article/article_list_ajax"),$count,$config);
        $data['article_info']=$this->article->select_article();
        foreach($data['article_info'] as &$v){
            $v['article_add_time']=date("Y-m-d H:i:s",$v['article_add_time']);
        }
        $data['page_now']=1;
        $this->load->view("Admin/article_list.html",$data);
    }

    /*分页*/
    function article_list_ajax(){
        $limit=$this->uri->segment(4);
        $config=$this->config_page();
        $count=$this->article->get_count(array("article_trash"=>1));
        $data['page']=$this->get_page(site_url("Admin/Article/article_list_ajax"),$count,$config);
        $data['article_info']=$this->article->select_article($limit);
        foreach($data['article_info'] as &$v){
            $v['article_add_time']=date("Y-m-d H:i:s",$v['article_add_time']);
        }
        $data['page_now']=$limit/config_item("admin_page")+1;
        echo json_encode($data);
    }

    /*文章删除*/
    function article_del(){
        $article_id=$this->input->post("article_id");
        $res=$this->article->put_trash($article_id);
        $re=$this->trash->trash_add($article_id,"article");
        if($res&&$re){
            $result['error']=1;
            $result['message']="删除成功";
        }else{
            $result['error']=0;
            $result['message']="删除失败";
        }
        echo json_encode($result);
    }

    /*文章添加*/
    function article_add(){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/article/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            if($res['status']){
                $data=$this->input->post();
                $data['article_image']=$res['file_name'];
                $data['article_add_time']=time();
                $data['article_content']=str_replace("'","|",$data['article_content']);
                $data['article_content']=htmlspecialchars($data['article_content']);
                $re=$this->article->add($data);
                if($re){
                    $this->process_image("./public/home/images/article/{$res['file_name']}",640,250);
                    //生成首页评论处文章缩略图(70*70)
                    $this->creat_thumb("./public/home/images/article/{$res['file_name']}","./public/home/images/thumbs/homeThumb_$re{$res['file_ext']}",70,70);
                    //文章图片缩略图(50*50)
                    $this->creat_thumb("./public/home/images/article/{$res['file_name']}","./public/home/images/thumbs/articleThumb_$re{$res['file_ext']}",50,50);
                    $this->add_tags($data['article_tag'],$re);
                    $result['error']=1;
                    $result['message']="添加成功";
                }else{
                    $result['error']=0;
                    $result['message']="添加失败";
                }
            }else{
                if($res['error']=="<p>You did not select a file to upload.</p>"){
                    $data=$this->input->post();
                    $data['article_add_time']=time();
                    $data['article_content']=str_replace("'","|",$data['article_content']);
                    $data['article_content']=htmlspecialchars($data['article_content']);
                    $re=$this->article->add($data);
                    if($re){
                        $this->add_tags($data['article_tag'],$re);
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
            $data['tag']=$this->tag->select_all();
            $data['type']=$this->type->get_type();
            $this->load->view("Admin/article_add.html",$data);
        }
    }

    /*文章修改*/
    function article_update($article_id=0){
        if(IS_POST){
            $config['upload_path'] = "./public/home/images/article/";
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
            $res=$this->do_upload("file",$config);
            $data=$this->input->post();
            if($res['status']){

                $old_image_name=$this->article->select_image($data['article_id']);
                if(!empty($old_image_name['article_image'])){
                    unlink("./public/home/images/article/{$old_image_name['article_image']}");
                }

                $data['article_image']=$res['file_name'];
                $data['article_add_time']=time();
                $data['article_content']=str_replace("'","|",$data['article_content']);
                $data['article_content']=htmlspecialchars($data['article_content']);
                $re=$this->article->save($data);
                if($re){
                    $this->process_image("./public/home/images/article/{$res['file_name']}",640,250);

                    //生成首页评论处文章缩略图(70*70)
                    $this->creat_thumb("./public/home/images/article/{$res['file_name']}","./public/home/images/thumbs/homeThumb_{$data['article_id']}{$res['file_ext']}",70,70);
                    //文章图片缩略图(50*50)
                    $this->creat_thumb("./public/home/images/article/{$res['file_name']}","./public/home/images/thumbs/articleThumb_{$data['article_id']}{$res['file_ext']}",50,50);

                    $this->update_tags($data['article_tag'],$data['article_id']);

                    $result['error']=1;
                    $result['message']="修改成功";
                }else{
                    $result['error']=0;
                    $result['message']="修改失败";
                }
            }else{
                if($res['error']=="<p>You did not select a file to upload.</p>"){
                    $data['article_add_time']=time();
                    $data['article_content']=str_replace("'","|",$data['article_content']);
                    $data['article_content']=htmlspecialchars($data['article_content']);
                    $re=$this->article->save($data);
                    if($re){
                        $result['error']=1;
                        $result['message']="修改成功";
                        $this->update_tags($data['article_tag'],$data['article_id']);
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
            $data['article_info']=$this->article->select_where_one(array("article_id"=>$article_id));
            $data['article_info']['article_content']=str_replace("|","'",$data['article_info']['article_content']);
            $data['article_info']['article_content']=htmlspecialchars_decode($data['article_info']['article_content']);
            $data['article_info']['article_tag']=explode(",",$data['article_info']['article_tag']);
            $data['type']=$this->type->get_type();
            $data['tag']=$this->tag->select_all();
            $this->load->view("Admin/article_update.html",$data);
        }
    }

    /*标签修改*/
    function update_tags($update_tags,$article_id){
        $cloud="";
        if(!empty($update_tags)){
            $cloud=explode(",",$update_tags);
        }
        $article_tag=$this->cloud->select_tag($article_id);
        if(!empty($cloud)){
            foreach($cloud as $v){
                if(empty($article_tag)||!in_array($v,$article_tag)){
                    $this->cloud->add(array("article_id"=>$article_id,"tag_id"=>$v));
                }
            }
        }
        if(!empty($article_tag)&&!empty($cloud)){
            foreach($article_tag as $v){
                if(!in_array($v,$cloud)){
                    $this->cloud->delete_tag($article_id,$v);
                }
            }
        }
        if(empty($cloud)){
            $this->cloud->delete_tag_all($article_id);
        }
    }

    /*标签添加*/
    function add_tags($add_tags,$article_id){
        if(!empty($add_tags)){
            $cloud=explode(",",$add_tags);
            foreach($cloud as $v){
                $this->cloud->add(array("tag_id"=>$v,"article_id"=>$article_id));
            }
        }
    }


}