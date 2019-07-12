<?php
class Set extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->config->load('website');
    }

    function set()
    {
        $data['resume'] = $this->config->item('resume');
        $data['banner'] = $this->config->item('banner');
        $data['header'] = $this->config->item('header');
        foreach($data as $key=>&$val){
            foreach($val as $k=>&$v){
                $v=str_replace('"','&quot;',$v);
            }
        }
        $this->load->view("Admin/set.html", $data);
    }

    /*个人设置*/
    function resume_set()
    {
        $data = $this->input->post();


        $resume_config=$this->config->item('resume');
        $website_config = file_get_contents("./application/config/website.php");


        $config['upload_path'] = "./public/home/images/photo/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['overwrite'] = TRUE;
        //第一张图
        if(isset($_FILES['photo1'])){
            $config['file_name'] = 'photo_1';
            $res=$this->do_upload("photo1", $config);
            if($resume_config['photo_1']!=$res['file_name']){
                unlink("./public/home/images/photo/{$resume_config['photo_1']}");
            }
            $this->process_image("./public/home/images/photo/{$res['file_name']}",450,636);
            $website_config=str_replace("'photo_1'=>'{$resume_config['photo_1']}'","'photo_1'=>'{$res['file_name']}'",$website_config);
        }
        //第二张图
        if(isset($_FILES['photo2'])){
            $config['file_name'] = 'photo_2';
            $res=$this->do_upload("photo2", $config);
            if($resume_config['photo_2']!=$res['file_name']){
                unlink("./public/home/images/photo/{$resume_config['photo_2']}");
            }
            $this->process_image("./public/home/images/photo/{$res['file_name']}",450,636);
            $website_config=str_replace("'photo_2'=>'{$resume_config['photo_2']}'","'photo_2'=>'{$res['file_name']}'",$website_config);
        }
        //第三张图
        if(isset($_FILES['photo3'])){
            $config['file_name'] = 'photo_3';
            $res=$this->do_upload("photo3", $config);
            if($resume_config['photo_3']!=$res['file_name']){
                unlink("./public/home/images/photo/{$resume_config['photo_3']}");
            }
            $this->process_image("./public/home/images/photo/{$res['file_name']}",450,636);
            $website_config=str_replace("'photo_3'=>'{$resume_config['photo_3']}'","'photo_3'=>'{$res['file_name']}'",$website_config);
        }

        $config['upload_path'] = "./public/admin/img/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['file_name'] = 'portrait';
        $config['overwrite'] = TRUE;
        $res = $this->do_upload("portrait", $config);
        if ($res['status']) {
            if($resume_config['portrait']!=$res['file_name']){
                unlink("./public/admin/img/{$resume_config['portrait']}");
            }
            $data['portrait'] = $res['file_name'];
            foreach($data as $k=>$v){
                $website_config=str_replace("'{$k}'=>'{$resume_config[$k]}'","'{$k}'=>'{$v}'",$website_config);
            }
            $this->process_image("./public/admin/img/{$res['file_name']}", 236, 236);

        } else {
            if ($res['error'] == "<p>You did not select a file to upload.</p>") {
                unset($data['portrait']);
                foreach($data as $k=>$v){
                    $website_config=str_replace("'{$k}'=>'{$resume_config[$k]}'","'{$k}'=>'{$v}'",$website_config);
                }
            }
        }
        file_put_contents("./application/config/website.php",$website_config);
    }

    /*首页设置*/
    function home_set(){
        $data=$this->input->post();
        $config['upload_path'] = "./public/home/images/banner/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['overwrite'] = TRUE;

        $banner_config=$this->config->item('banner');
        $website_config = file_get_contents("./application/config/website.php");
        //第一张图
        if(isset($_FILES['one'])){
            $config['file_name'] = 'banner_1';
            $res=$this->do_upload("one", $config);
            if($banner_config['banner_1']!=$res['file_name']){
                unlink("./public/home/images/banner/{$banner_config['banner_1']}");
            }
            $this->process_image("./public/home/images/banner/{$res['file_name']}",940,400);
            $website_config=str_replace("'banner_1'=>'{$banner_config['banner_1']}'","'banner_1'=>'{$res['file_name']}'",$website_config);
        }
        //第二张图
        if(isset($_FILES['two'])){
            $config['file_name'] = 'banner_2';
            $res=$this->do_upload("two", $config);
            if($banner_config['banner_2']!=$res['file_name']){
                unlink("./public/home/images/banner/{$banner_config['banner_2']}");
            }
            $this->process_image("./public/home/images/banner/{$res['file_name']}",940,400);
            $website_config=str_replace("'banner_2'=>'{$banner_config['banner_2']}'","'banner_2'=>'{$res['file_name']}'",$website_config);
        }
        //第三张图
        if(isset($_FILES['three'])){
            $config['file_name'] = 'banner_3';
            $res=$this->do_upload("three", $config);
            if($banner_config['banner_3']!=$res['file_name']){
                unlink("./public/home/images/banner/{$banner_config['banner_3']}");
            }
            $this->process_image("./public/home/images/banner/{$res['file_name']}",940,400);
            $website_config=str_replace("'banner_3'=>'{$banner_config['banner_3']}'","'banner_3'=>'{$res['file_name']}'",$website_config);
        }
        foreach($data as $k=>$v){
            $website_config=str_replace("'{$k}'=>'{$banner_config[$k]}'","'{$k}'=>'{$v}'",$website_config);
        }
        file_put_contents("./application/config/website.php",$website_config);
    }

    /*标题设置*/
    function title_set(){
        $data=$this->input->post();
        $header_config=$this->config->item('header');
        $website_config = file_get_contents("./application/config/website.php");
        foreach($data as $k=>$v){
            $website_config=str_replace("'{$k}'=>'{$header_config[$k]}'","'{$k}'=>'{$v}'",$website_config);
        }
        file_put_contents("./application/config/website.php",$website_config);
    }
}