/*随机选取照片*/
function change(){
    var random=Math.random()
    var change=arr[parseInt(random*len)][1]
    name=arr[parseInt(random*len)][0]
    $("#show").html("<img src='"+change+"' style='width: 100%;height: 100%;border-radius: 50%'>")
}
/*停止&加载动画*/
$("#start").toggle(function(){
    $(this).attr("src","./Image/Pokemon/static.gif")
    $("#bgm").html("<audio src='./Audio/Pokemon/start.mp3' autoplay='autoplay' loop='loop'></audio>")
    flag=setInterval('change()',1)
},function(){
    clearInterval(flag)
    $(this).hide()
    $("#bgm").html("<audio src='./Audio/Pokemon/pika.mp3' autoplay='autoplay' loop='loop'></audio>")
    $("#Pikachu").show()
    setTimeout(function(){$("#bgm").html("<audio src='./Audio/Pokemon/come.mp3' autoplay='autoplay' loop='loop'></audio>")},1200)
    setTimeout(function(){
        $("#show").html("<img src='./Image/Pokemon/throw.gif' style='width: 100%;height: 100%;border-radius: 50%'/>")
        $("#Pikachu").hide()
    },1800)
    setTimeout(function(){$("#show").html(name)},3060)
    setTimeout(function(){$("#show").html("决定就是你了！")},5400)
    setTimeout(function(){
        $("#show").html("<img src='./Image/Pokemon/strong.gif' style='width: 100%;height: 100%;border-radius: 50%'/>")
        setTimeout(function(){$("#bgm").html("<audio src='./Audio/Pokemon/roar.mp3' autoplay='autoplay' loop='loop'></audio>")},700)
        setTimeout(function(){$("#bgm").html("")},1500)
    },8500)
    setTimeout(function(){location.reload(true)},12300)
})