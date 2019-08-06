<?php
    /*
    extension=php_sockets.dll
    extension=php_openssl.dll
    */
    //pop3  邮件协议第三个版本  110
    //*smtp  简单邮件协议       25
    //imap  交互式邮件协议      143
defined('BASEPATH') OR exit('No direct script access allowed');
class Email{
    function send($code,$name){
        require './application/libraries/Phpmailer.php';
        $mail             = new Phpmailer();
        /*服务器相关信息*/
        $mail->IsSMTP();                        //设置使用SMTP服务器发送
        $mail->SMTPAuth   = true;               //开启SMTP认证
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Host       = 'smtp.163.com';   	    //设置 SMTP 服务器,自己注册邮箱服务器地址
        $mail->Username   = 'wuwuwuliuliyi';  		//发信人的邮箱名称
        $mail->Password   = 'l837615264';          //发信人的邮箱密码
        /*内容信息*/
        $mail->IsHTML(true); 			         //指定邮件格式为：html
        $mail->CharSet    ="UTF-8";			     //编码
        $mail->From       = 'wuwuwuliuliyi@163.com';	 		 //发件人完整的邮箱名称
        $mail->FromName   = $name;			 //发信人署名
        $mail->Subject    = "您的网站(51neko.com)有新的消息！！！";  			 //信的标题
        $mail->MsgHTML($code);  				 //发信主体内容
        /*发送邮件*/
        $mail->AddAddress("837615264@qq.com");  			 //收件人地址
        //使用send函数进行发送
        if($mail->Send()) {
            return "yes";
        } else {
            return $mail->ErrorInfo;//如果发送失败，则返回错误提示
        }
    }
}
