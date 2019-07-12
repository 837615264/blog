$(document).ready(function(){

    /*创建图片地址数组*/
    cards_path=new Array()
    for(var i=0;i<77;i++){
        cards_path[i]="Image/Tarot/TarotCards/"+i+".jpg"
    }

    /*按键操作*/
    $(window).keydown(function(event){
        switch(event.keyCode) {
            case 32:choose_cards();break;       //空格键
            case 77:music_flag();break;         //M键
        }
    });

    flag=1                                          //开始&结束的开关
    musicflag=1                                    //音乐开关

    /*获取设置的塔罗牌数量(点名人数)*/
    if($("#num").val()){
        switch (parseInt($("#num").val())){
            case 1:OneCard();break;
            case 2:TwoCards();break;
            case 3:ThreeCards();break;
            case 4:FourCards();break;
            case 5:FiveCards();break;
        }
    }
})

/*选取一张牌*/
function OneCard(){
    $(".cards").css("position","absolute").css("left","50%").css("margin-left","-100px")
    $(".cards span").html("Ⅰ")
}
/*选取两张牌*/
function TwoCards(){
    /*根据分辨率更改不同的样式*/
    switch (screen.width){
        case 1920:$(".cards").css("margin-left","26%");break;
        case 1680:$(".cards").css("margin-left","25%");break;
        case 1600:$(".cards").css("margin-left","24.5%");break;
        case 1440:$(".cards").css("margin-left","23.8%");break;
        case 1400:$(".cards").css("margin-left","23.5%");break;
        case 1366:$(".cards").css("margin-left","23%");break;
        case 1360:$(".cards").css("margin-left","23%");break;
        case 1280:$(".cards").css("margin-left","23%");break;
        case 1152:$(".cards").css("margin-left","21.3%");break;
        case 1024:$(".cards").css("margin-left","20%");break;
        case 800:$(".cards").css("margin-left","16%");break;
    }
    $(".cards").eq(0).children("span").html("Ⅰ")
    $(".cards").eq(1).children("span").html("Ⅱ")
}
/*选取三张牌*/
function ThreeCards(){
    /*根据分辨率更改不同的样式*/
    switch (screen.width){
        case 1920:$(".cards").css("margin-left","17%");break;
        case 1680:$(".cards").css("margin-left","16%");break;
        case 1600:$(".cards").css("margin-left","15.4%");break;
        case 1440:$(".cards").css("margin-left","14.3%");break;
        case 1400:$(".cards").css("margin-left","14%");break;
        case 1366:$(".cards").css("margin-left","13.7%");break;
        case 1360:$(".cards").css("margin-left","13.7%");break;
        case 1280:$(".cards").css("margin-left","13.1%");break;
        case 1152:$(".cards").css("margin-left","11.8%");break;
        case 1024:$(".cards").css("margin-left","10%");break;
        case 800:$(".cards").css("margin-left","6%");break;
    }
    $(".cards").eq(0).children("span").html("Ⅰ")
    $(".cards").eq(1).children("span").html("Ⅱ")
    $(".cards").eq(2).children("span").html("Ⅲ")
}
/*选取四张牌*/
function FourCards(){
    /*根据分辨率更改不同的样式*/
    switch (screen.width){
        case 1920:$(".cards").css("margin-left","11.5%");break;
        case 1680:$(".cards").css("margin-left","10.5%");break;
        case 1600:$(".cards").css("margin-left","10%");break;
        case 1440:$(".cards").css("margin-left","8.6%");break;
        case 1400:$(".cards").css("margin-left","8.3%");break;
        case 1366:$(".cards").css("margin-left","8.2%");break;
        case 1360:$(".cards").css("margin-left","8.2%");break;
        case 1280:$(".cards").css("margin-left","7.2%");break;
        case 1152:$(".cards").css("margin-left","6%");break;
        case 1024:$(".cards").css("margin-left","4.2%");break;
        case 800:$(".cards").css("margin-left","5.7%");break;
    }
    $(".cards").eq(0).children("span").html("Ⅰ")
    $(".cards").eq(1).children("span").html("Ⅱ")
    $(".cards").eq(2).children("span").html("Ⅲ")
    $(".cards").eq(3).children("span").html("Ⅳ")
}
/*选取五张牌*/
function FiveCards(){
    /*根据分辨率更改不同的样式*/
    switch (screen.width){
        case 1920:$(".cards").css("margin-left","7.8%");break;
        case 1680:$(".cards").css("margin-left","6.6%");break;
        case 1600:$(".cards").css("margin-left","6.1%");break;
        case 1440:$(".cards").css("margin-left","5%");break;
        case 1400:$(".cards").css("margin-left","4.7%");break;
        case 1366:$(".cards").css("margin-left","4.4%");break;
        case 1360:$(".cards").css("margin-left","4.3%");break;
        case 1280:$(".cards").css("margin-left","3.6%");break;
        case 1152:$(".cards").css("margin-left","2.1%");break;
        case 1024:$(".cards").css("margin-left","0.4%");break;
        case 800:$(".cards").css("margin-left","5%");break;
    }
    $(".cards").eq(0).children("span").html("Ⅰ")
    $(".cards").eq(1).children("span").html("Ⅱ")
    $(".cards").eq(2).children("span").html("Ⅲ")
    $(".cards").eq(3).children("span").html("Ⅳ")
    $(".cards").eq(4).children("span").html("Ⅴ")
}

/*音乐开关*/
function music_flag(){
    if(musicflag){
        $("#music").html("")
        musicflag=0
    }else{
        $("#music").html("<audio src='./Audio/Tarot/MoldeCanticle.mp3' autoplay='autoplay' loop='loop'></audio>")
        musicflag=1
    }
}

/*开始翻牌*/
function choose_cards(){
    if(flag&&!$(".cards img").is(":animated")){                                              //判定开关打开并且页面中的动画效果都结束
        var num=$("#num").val()
        var cards_number=new Array()                                                            //实例一个存放卡牌编号的数组
        for(var i=0;i<num;i++){
            cards_number[i]=parseInt(Math.random()*77)                                          //随机出的卡牌编号赋值进数组
        }
        var name_number=new Array()                                                             //实例一个存放姓名的数组
        for(var i=0;i<num;i++){
            name_number[i]=arr[parseInt(Math.random()*len)][0]                                  //随机出的姓名赋值进数组
        }
        /*名字有重复则重新随机*/
        for(var i in name_number){
            if(name_number.indexOf(name_number[i])!=name_number.lastIndexOf(name_number[i])){
                choose_cards()
                return
            }else if(name_number[i]=="丁一凡"||name_number[i]=="刘理一"){
                choose_cards()
                return
            }
        }

        ClearTime=new Array()                                                                 //实例一个存放setTimeout返回的id的数组

        OldWidth=parseInt($(".tarot").css("width"))
        $.each($(".tarot"),function(k,v){                                                    //循环所有的塔罗牌图片对象
            ClearTime[k]=setTimeout(function(){                                              //设置延时，以0.5秒间隔轮流翻转
                $(".tarot").eq(k).animate({width:0},"slow",function(){                      //将塔罗牌背面翻转(消失，宽度0)
                    $(".tarot").eq(k).attr("src",cards_path[cards_number[k]])               //将塔罗牌图片地址由背面设置为随机后对应的塔罗牌正面
                })
            },k*500)
            ClearTime[k+5]=setTimeout(function(){
                $(".tarot").eq(k).animate({width:OldWidth},"slow",function(){             //将塔罗牌翻转成正面(显示，宽度恢复)
                    setTimeout(function(){                                                   //设置延时，0.2秒间隔
                        if(name_number[k].length==2)                                         //判断姓名长度，设置不同的样式
                        {
                            $(".cards p").eq(k).css("top",90)
                        }else{
                            $(".cards p").eq(k).css("top",70)
                        }
                        $(".cards p").eq(k).html(name_number[k])                            //将随机出的姓名往对应的p标签内赋值
                        $(".cards p").eq(k).css("display","block")                        //将所有p标签(姓名)从隐藏改为显示
                        $(".tarot").eq(k).next().css("display","block")                   //将所有存放背景牌的div从隐藏改为显示
                        $(".tarot").eq(k).fadeOut(5000)                                    //将塔罗牌的透明度由5秒内降为0
                    },k*200)
                })
            },k*500)
        })
        flag=0                                                                              //关闭开关
    }else{
        if(!$(".cards img").is(":animated")){                                             //判定页面中的动画效果都结束
            for(var i=0 in ClearTime){
                clearTimeout(ClearTime[i])
            }
                    $(".cards p").fadeOut(500)                                             //将所有的p标签(姓名)的透明度由0.5秒内降为0
                    $(".showbackcards").animate({width:0},"slow",function(){            //将背景牌图片对象进行翻转(消失，宽度0)
                        $(".tarot").fadeIn(0)                                              //将塔罗牌的透明度恢复
                        $(".tarot").attr("src","Image/Tarot/TarotCards/TarotBack.jpg")//将塔罗牌换成背面
                        $(".tarot").css("width",0)                                        //将塔罗牌的宽度设置为0,(消失)
                        $(".tarot").animate({width:OldWidth},"slow",function(){        //将塔罗牌翻转成背面(显示，宽度恢复)
                            $(".showbackcards").css("width","100%")                     //将背景牌的宽度恢复(显示,宽度恢复)
                            $(".showback").css("display","none")                        //将存放背景牌的div从显示恢复为隐藏
                            $(".cards p").fadeIn(0)                                      //将p标签(姓名)的透明度恢复
                            $(".cards p").css("display","none")                         //将p标签(姓名)从显示恢复为隐藏
                            flag=1                                                        //打开开关
                        })
                    })
        }
    }
}

