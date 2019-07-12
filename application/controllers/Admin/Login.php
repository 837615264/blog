<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        //$this->load->model("Type_model","type");
    }

    /*登录页面*/
    function login(){
        if(IS_POST){
            $data=$this->input->post();
            if(strtolower($data['captcha'])!=strtolower($this->session->userdata('captcha'))){
                $result['error']=0;
                $result['message']="验证码有误";
            }else{
                if(md5($data['username'])=="21232f297a57a5a743894a0e4a801fc3"&&md5($data['password'])=="e10adc3949ba59abbe56e057f20f883e"){
                    $this->session->set_userdata("admin_id",1);
                    $this->input->set_cookie("admin_name","f65de49820c0f0f2c1a4bbee2871adad",7200);
                    $result['error']=1;
                    $result['message']="登陆成功";
                }else{
                    $result['error']=0;
                    $result['message']="账号或密码有误，请重试";
                }
            }
            echo json_encode($result);
        }else{
            $data['captcha']=$this->get_captcha();
            $this->load->view("Admin/admin_login.html",$data);
        }
    }

    /*创建验证码*/
    function get_captcha(){
        $config = array(
            'img_path'  => './captcha/',
            'img_url'   => base_url().'captcha/',
            'img_width' => 180,
            'img_height'    => 40,
            'expiration'    => 7200,
            'word_length'   => 4,
            'font_size' => 60,
            'img_id'    => 'Imageid',
            'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        );
        $cap = create_captcha($config);
        $this->session->set_userdata("captcha",$cap['word']);
        if(IS_POST){
            echo $cap['image'];
        }else{
            return $cap['image'];
        }
    }

    /*退出*/
    function logout(){
        $this->session->unset_userdata('admin_id');
        delete_cookie("admin_name");
        redirect("Admin/Login/login");
    }
}
