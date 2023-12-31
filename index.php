<?php
//*Version : 0.0.1
//*Based on the Python-built version 3.2

include 'libs/public_func.php';

//!获取表单的信息

#获取?s=""数据，判断用户选择的年龄分级,如果不填默认为13岁
$selected_age_level = isset($_REQUEST['agelevel'])?$_REQUEST['agelevel']:13; 

#获取?link=""如果有确定的链接，则强制打开指定图片
$link = isset($_REQUEST['link'])?$_REQUEST['link']:NULL;

#获取令牌
$token = isset($_REQUEST['token'])?$_REQUEST['token']:NULL;

#用于判定网站的运行模块。包括：初始化中（initializing）、反馈（activatedfeedback）、发送令牌（gettingtoken)、审核上架模式（examine)、默认模式（display）
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:"display";

//!判断是否需要跳转到其他页面
if($mode == "examine"){
    header("location:advanced/examine.php");
    exit(0);
}

#捕捉到错误，即表明配置文件出现异常，将会自动移交init.php处理
header("location:advanced/init.php");

//!开始输出内容
echo <<<EOF
<html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width,user-scalable=yes" name="viewport">
        <!--<meta http-equiv="refresh" content="0;url=index.php"> -->
        <title>$title </title>
        <link rel="icon" href="./logo.png">
        <script src="libs/index_scripts.js"></script>
        <link rel="stylesheet" type="text/css" href="libs/style.css">
        <script src="libs/jquery-3.6.0.min.js">

        </script>
    </head>

    <body>
        <div id="main_table">
            <div id="first_row">
                <button id="origin_button"  onclick="showorigin()">查看原图</button>
                <button id="enlargepic_button" href="" target=_blank  onclick="showorigin()">点击打开大图</button>
                <a id="currentagelevel_text">当前年龄模式：</a>
                <a id="agelevel_text">13+</a>
                <a>单击图片打开下一张。</a>
                <a id="pixivID_text">p站ID：</a>
                <a id="pixivurl_text" href="">00000000</a>
                <button id="sharelink_button" title="点击以复制分享链接">一键分享图片</button>
            </div>
            <div id="display_stage">

            </div>
            <div id="second_row">
                <p id="find_similar_pic">找到了相同ID的图片</p>
                <p id="is_gif">该图片是动图，若要查看请点击点击“查看原图”。</p>
            </div>
            <div id="third_row">
                <form action="." method="post">打开指定图片：
                    <input type='hidden' name='s' value='%s'/>
                    <input type='hidden' name='t' value='%s'/>
                    <input type="text" id="Target_url" class="txtbox" name="link" onkeyup="this.size=(this.value.length>25?this.value.length:25);" size="25" value="">
                    <input type="submit" value="确认">
                </form>
            </div>
            <div id="fourth_row">
                <form action="." method="post">更改年龄等级：
                    <label>
                    <input type='hidden' name='t' value='%s'/>
                    <input type="radio" name="s" value="13" id="RadioGroup1_0">13+
                    <input type="radio" name="s" value="15" id="RadioGroup1_1">15+
                    <input type="radio" name="s" value="17" id="RadioGroup1_2">17+
                    <input type="radio" name="s" value="18" id="RadioGroup1_3">18+
                    <input type="radio" name="s" value="AO" id="RadioGroup1_4">Adult Only
                    </label>
                    <input type="submit" value="确认">
                    </form>
            </div>
            <div id="fifth_row">
                <button id="admin_button">管理员</button>
                <button id="feedback_button">我要反馈</button>
            </div>
        </div>

        <div id="feedback_window">
            <p>我要反馈</p>
            <form id="feedbock_form">
                图片信息<input type="text" name="picdirectory">
                <input type="reset" value="重置"><br>
                <label><input type="checkbox" name="ICCF1" value="True">我认为分级有误</label><br>
                <label><input type="checkbox" name="DLTI2" value="True">我不喜欢这张图片</label><br>
                <input type="button" value="提交" onclick="activating_feedback">
            </form>
        </div>
        
    </body>
</html>
EOF;

?>