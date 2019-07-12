<!--author:LLY-->
<?php $num=file_get_contents("Config/Tarot.txt",true);?>
<!doctype html>
<html lang="en">
<head>
    <script src="./Js/jquery-1.7.2.min.js"></script>
    <meta charset="UTF-8">
    <title>1504PhpA班点名表</title>
    <link rel="stylesheet" href="Css/Common.css"/>
    <?php if($num>0){?>
        <link rel="stylesheet" href="Css/Tarot_style.css"/>
    <?php }else{?>
        <link rel="stylesheet" href="Css/Pokemon_style.css"/>
    <?php }?>
</head>
<body>

<?php
if($num>0){
    include("Theme/Tarot.php");
}else{
    include("Theme/Pokemon.html");
}
?>

<input type="hidden" id="num" value="<?php echo $num?>"/>
<!--遮罩层start-->
<div id="overlay"></div>
<div id="config">
    <center>
        <h6>主题设置</h6>
    <table id="configTable">
    <tr>
        <td>选择版本：</td>
        <td><select id="ChangeTheme">
                <?php if(intval($num)>0){?>
            <option value="Pokemon">精灵宝可梦</option>
            <option value="Tarot" selected>花影塔罗牌</option>
                <?php }else{?>
                    <option value="Pokemon" selected>精灵宝可梦</option>
                    <option value="Tarot">花影塔罗牌</option>
                <?php }?>
        </select></td>
    </tr>
        <?php if(intval($num>0)){?>
            <tr>
                <td>点名人数：</td>
                <td>
                    <input type='radio' name='num' class='num' value='1' checked/>Ⅰ
                    <input type='radio' name='num' class='num' value='2'/>Ⅱ
                    <input type='radio' name='num' class='num' value='3'/>Ⅲ
                    <input type='radio' name='num' class='num' value='4'/>Ⅳ
                    <input type='radio' name='num' class='num' value='5'/>Ⅴ
                </td>
            </tr>
        <?php }?>
</table>
        <input type="button" value="确认" id="submit"/>
    </center></div>
<!--遮罩层end-->
</body>
</html>
<script src="./Js/change.js"></script>
<?php if($num>0){?>
    <script src="./Js/Tarot.js"></script>
<?php }else{?>
    <script src="./Js/Pokemon.js"></script>
<?php }?>


