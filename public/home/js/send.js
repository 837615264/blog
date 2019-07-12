var check_time=0
$(document).on("click","#send",function(){
    check_time++
    if(check_time>=20){
        alert('想搞事？！！！我靠( ‵o′)凸')
        return false
    }
    var reg = /^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/g
    if($("#advice_name").val()==""||$("#advice_name").val()=="姓名 *"){
        alert("请输入您的姓名")
    }else if($("#advice_email").val()==""||$("#advice_email").val()=="邮箱 *"){
        alert("请输入您的邮箱")
    }else if($("#advice_message").val()==""||$("#advice_message").val()=="内容 *"){
        alert("请输入您要回复的内容")
    }else if(!reg.test($("#advice_email").val())){
        alert("请输入正确的邮箱以便与您联系")
    }else {
        $("#status").show()
        var time=0
        var dot=""
        var flag=setInterval(function(){
            switch(time){
                case 1:dot=".";break;
                case 2:dot="..";break;
                case 3:dot="...";break;
            }
            $("#status").html("Sending"+dot)
            time+1>3?time=1:time+=1
        },500)

        var formdata = new FormData()
        var _this = $(this)
        formdata.append('advice_name', $("#advice_name").val())
        formdata.append('advice_email', $("#advice_email").val())
        formdata.append('advice_message', $("#advice_message").val())
        $.ajax({
            url: send_url,
            type: 'POST',
            cache: false,
            data: formdata,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (msg) {
                if(msg.status==1){
                    setTimeout(function(){
                        clearInterval(flag)
                        $("#status").html('发送成功！')
                    },3000)
                }else if(msg.status==2){
                    clearInterval(flag)
                    $("#status").html('服务器君比较懒，三分钟后才能帮您发送邮件哦~')
                }else{
                    setTimeout(function(){
                        clearInterval(flag)
                        $("#status").html('系统繁忙')
                    },5000)
                }
            }
        })
    }
})
