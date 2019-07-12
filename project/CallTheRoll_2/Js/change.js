/*数据加载*/
$(document).ready(function(){
    arr=new Array()
    var name=['石超','倪志蕊','柴炎丽','赵杰','温晓鹏','顾明鑫','焦世豪','张国威',
        '王博玉','王驰','赵炜','李茜','金英明','谷增辉','王伟','原梦水',
        '班思雨','韩明辉','张金鹏','陈猛超','赵杰源','丁一凡','刘理一',
        '田宇','葛永康','孙凯','卢希','曾庆红','那超群',
        '贾浩迪','程明明','史晓楠']
    var photo=['./Photo/qbc.jpg','./Photo/yl.jpg','./Photo/ljh.jpg','./Photo/lc.jpg','./Photo/lsr.jpg','./Photo/lly.jpg','./Photo/lsy.jpg',
        './Photo/wzt.jpg','./Photo/wek.jpg','./Photo/wj.jpg','./Photo/zt.jpg','./Photo/jq.jpg','./Photo/sky.jpg',
        './Photo/syx.jpg','./Photo/zh.jpg','./Photo/zyy.jpg','./Photo/zl.jpg','./Photo/sj.jpg','./Photo/cq.jpg',
        './Photo/cx.jpg','./Photo/zln.jpg','./Photo/zr.jpg','./Photo/lhw.jpg','./Photo/lt.jpg','./Photo/dyc.jpg',
        './Photo/sww.jpg','./Photo/yinl.jpg','./Photo/tjp.jpg','./Photo/plw.jpg','./Photo/wsk.jpg','./Photo/wangj.jpg',
        './Photo/wxt.jpg','./Photo/qjm.jpg','./Photo/csq.jpg','./Photo/qjw.jpg','./Photo/cs.jpg','./Photo/ych.jpg','./Photo/zpy.jpg'
        ,'./Photo/jy.jpg','./Photo/cxy.jpg','./Photo/chenx.jpg','./Photo/tyw.jpg','./Photo/wt.jpg','./Photo/hlz.jpg']
    len=name.length
    for(var i=0;i<len;i++){
        arr[i]=new Array()
        for(var j=0;j<2;j++){
            arr[i][j]=""
        }
    }       //定义二维

    for(var i=0;i<len;i++){
        arr[i][0]=name[i]
        arr[i][1]=photo[i]
    }       //循环赋值
});


$(window).keydown(function(event){
    switch(event.keyCode) {
        case 9:showOverlay();showConfig();adjust("#config");break;
    }
});

/* 显示遮罩层 */
function showOverlay() {
    $("#overlay").css("display","block")
}

/* 隐藏覆盖层 */
function hideOverlay() {
    $("#overlay").css("display","none")
}

/* 显示配置 */
function showConfig() {
    $("#config").css("display","block")
}

/* 隐藏配置 */
function hideConfig() {
    $("#config").css("display","none")
}

/* 定位到页面中心 */
function adjust(id) {
    var w = $(id).width();
    var h = $(id).height();

    var t = scrollY() + (windowHeight()/2) - (h/2);
    if(t < 0) t = 0;

    var l = scrollX() + (windowWidth()/2) - (w/2);
    if(l < 0) l = 0;

    $(id).css({left: l+'px', top: t+'px'});
}

//浏览器视口的高度
function windowHeight() {
    var de = document.documentElement;

    return self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
}

//浏览器视口的宽度
function windowWidth() {
    var de = document.documentElement;

    return self.innerWidth || (de && de.clientWidth) || document.body.clientWidth
}

/* 浏览器垂直滚动位置 */
function scrollY() {
    var de = document.documentElement;

    return self.pageYOffset || (de && de.scrollTop) || document.body.scrollTop;
}

/* 浏览器水平滚动位置 */
function scrollX() {
    var de = document.documentElement;

    return self.pageXOffset || (de && de.scrollLeft) || document.body.scrollLeft;
}

/*选择版本*/
$("#ChangeTheme").on("change",function(){
    Theme=$(this).val()
    if(Theme=="Tarot"){
        var tr=$("<tr></tr>")
        tr.append("<td>点名人数</td><td><input type='radio' name='num' class='num' value='1' checked/>Ⅰ" +
        "<input type='radio' name='num' class='num' value='2'/>Ⅱ" +
        "<input type='radio' name='num' class='num' value='3'/>Ⅲ" +
        "<input type='radio' name='num' class='num' value='4'/>Ⅳ" +
        "<input type='radio' name='num' class='num' value='5'/>Ⅴ</td>")
        $(this).parent().parent().nextAll().empty()
        $(this).parent().parent().after(tr)
    }
    if(Theme=="Pokemon"){
        $(this).parent().parent().nextAll().empty()
    }
})

/*修改配置文件*/
$("#submit").on("click",function(){
    var choose_num=$(".num:checked").val()
    if(choose_num==undefined&&Theme=="Pokemon"){
        choose_num=0
    }
    $.ajax({
        type: "POST",
        url: "Config/set_config.php",
        data: "num="+choose_num,
        success: function(msg){
            if(msg==1){
                location.reload(true)
            }
        }
    });
})