/*数据加载*/
$(document).ready(function(){
    arr=new Array()
    var name=['乔炳晨','俞露','刘佳慧','刘成','刘申瑞','刘理一','刘舒雅','吴中天','吴恩凯','吴杰','周涛',
        '姜琦','孙奎元','孙宇轩','张宏','张悦影','张璐','施佳','曹奇','曹旭','朱丽娜','朱睿','李华伟','李婷','杜雨辰',
        '桑雯雯','殷璐','汤久鹏','潘丽雯','王书凯','王杰','王雪婷','秦金梅','程世秋','曲静文','蔡晟','袁晨卉','赵鹏燕','金玉',
        '晨曦园','陈鑫','陶乙苇','魏彤','黄理政']
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
/*随机选取照片*/
function change(){
    var random=Math.random()
    var change=arr[parseInt(random*len)][1]
    name=arr[parseInt(random*len)][0]
    $("#show").html("<img src='"+change+"' style='width: 100%;height: 100%;border-radius: 50%'>")
}
/*停止&加载动画*/
$("#start").toggle(function(){
    $(this).attr("src","./Image/static.gif")
    $("#bgm").html("<audio src='./Audio/start.mp3' autoplay='autoplay' loop='loop'></audio>")
    flag=setInterval('change()',1)
},function(){
    clearInterval(flag)
    $(this).hide()
    $("#bgm").html("<audio src='./Audio/pika.mp3' autoplay='autoplay' loop='loop'></audio>")
    $("#Pikachu").show()
    setTimeout(function(){$("#bgm").html("<audio src='./Audio/come.mp3' autoplay='autoplay' loop='loop'></audio>")},1200)
    setTimeout(function(){
        $("#show").html("<img src='./Image/throw.gif' style='width: 100%;height: 100%;border-radius: 50%'/>")
        $("#Pikachu").hide()
    },1800)
    setTimeout(function(){$("#show").html(name)},3060)
    setTimeout(function(){$("#show").html("决定就是你了！")},5400)
    setTimeout(function(){
        $("#show").html("<img src='./Image/strong.gif' style='width: 100%;height: 100%;border-radius: 50%'/>")
        setTimeout(function(){$("#bgm").html("<audio src='./Audio/roar.mp3' autoplay='autoplay' loop='loop'></audio>")},700)
        setTimeout(function(){$("#bgm").html("")},1500)
    },8500)
    setTimeout(function(){location.reload(true)},12300)
})