<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    function __construct(){
        parent::__construct();
        $session=$this->session->userdata("admin_id");
        $cookie=get_cookie("admin_name");
        if(empty($session)||empty($cookie)){
            die("<script>alert('身份信息已过期，请重新登录');location.href='".site_url("Admin/Login/login")."'</script>");
        }
    }

    /*上传*/
    function do_upload($file_name,$config=array())
    {
        if(empty($config)){
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024*10*10;
            $config['encrypt_name']=TRUE;
        }
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload($file_name,$config))
        {
            $data['status']=FALSE;
            $data['error']=$this->upload->display_errors();
            return $data;
        }
        else
        {
            $data['status']=TRUE;
            $info=$this->upload->data();
            $data['file_ext']=$info['file_ext'];    //文件后缀
            $data['rand_name']=$info['raw_name'];   //随机文件名（无后缀）
            $data['ini_name']=$info['orig_name'];   //初始文件名
            $data['file_name']=$info['file_name'];
            return $data;
        }
    }

    /*分页*/
    function get_page($url,$count,$config=array()){
        $this->load->library('pagination');
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = config_item('admin_page');
        if(!empty($config)){
            $config['first_link'] = '首页';
            $config['prev_link'] = '上一页';
            $config['next_link'] = '下一页';
            $config['last_link'] = '尾页';
        }
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*分页配置*/
    function config_page(){
        $config['full_tag_open'] = '<div class="btn-toolbar m-b-10"><div class="btn-group">';
        $config['full_tag_close'] = '</div></div>';
        $config['first_link'] = '首页';
        $config['first_tag_open'] = '<button type="button" class="btn btn-sm">';
        $config['first_tag_close'] = '</button>';
        $config['last_link'] = '尾页';
        $config['last_tag_open'] = '<button type="button" class="btn btn-sm">';
        $config['last_tag_close'] = '</button>';
        $config['next_link'] = '下一页';
        $config['next_tag_open'] = '<button type="button" class="btn btn-sm">';
        $config['next_tag_close'] = '</button>';
        $config['prev_link'] = '上一页';
        $config['prev_tag_open'] = '<button type="button" class="btn btn-sm">';
        $config['prev_tag_close'] = '</button>';
        $config['cur_tag_open'] = '<button type="button" class="btn btn-sm">';
        $config['cur_tag_close'] = '</button>';
        $config['num_tag_open'] = '<button type="button" class="btn btn-sm">';
        $config['num_tag_close'] = '</button>';
        return $config;
    }

    /*处理图像大小*/
    function process_image($way,$width=460,$height=260){
        //$way为相对或绝对路径
        $this->load->library('image_lib');
        $config['source_image'] = $way;
        $config['new_image']=$way;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize())
        {
            return $this->image_lib->display_errors();
        }else{
            return TRUE;
        }
    }

    /**
     * @brief 生成缩略图
     * @param $way 原图路径
     * @param $save_path 保存的路径
     * @param $width 图片宽度
     * @param $height 图片高度
     * @return string/bool
     */
    function creat_thumb($way,$save_path,$width,$height){
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $way;
        $config['new_image']= $save_path;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize())
        {
            return $this->image_lib->display_errors();
        }else{
            return TRUE;
        }
    }

    /**
     * @brief 截取文件后缀
     * @param $str 文件名
     * @return string
     */
    function substr_ext($str){
        $len=strlen($str);
        $pos=strpos($str,".");
        $ext=substr($str,$pos,$len-$pos);
        return $ext;
    }
}

class Home_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Type_model","type");
    }

    /*分页*/
    function get_page($url,$count,$config=array(),$type="article"){
        $this->load->library('pagination');
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = config_item($type."_page");
        if(!empty($config)){
            $config['first_link'] = '首页';
            $config['prev_link'] = '上一页';
            $config['next_link'] = '下一页';
            $config['last_link'] = '尾页';
        }
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*分页配置*/
    function config_page(){
        $config['full_tag_open'] = '<div class="blogpages"><ul>';
        $config['full_tag_close'] = '</ul><div class="clear"></div></div>';
        $config['first_link'] = '首页';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '尾页';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '下一页';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '上一页';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a class="selected">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        return $config;
    }

    /*公共信息*/
    function public_info($title=""){
        //分类导航
        $type_all=$this->type->select_all();
        $data['type']=$this->type->nav_type(0,$type_all);
        $data['header_title']=$title;
        return $data;
    }

    /**
     * @brief 截取文件后缀
     * @param $str 文件名
     * @return string
     */
    function substr_ext($str){
        $len=strlen($str);
        $pos=strpos($str,".");
        $ext=substr($str,$pos,$len-$pos);
        return $ext;
    }
}

