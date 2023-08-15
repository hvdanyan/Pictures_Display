<?php
//*Version:0.0.1
//*Response in json code.

include 'libs/public_func.php';

#追踪错误,将错误以异常的形式抛出
set_error_handler("Error_trans_excpt");

//!获取表单的信息

#获取?link=""如果有确定的链接，则强制打开指定图片
$link = isset($_REQUEST['link'])?$_REQUEST['link']:NULL;

#获取?agelevel=""数据，判断用户选择的年龄分级
$selected_age_level = isset($_REQUEST['agelevel'])?$_REQUEST['agelevel']:""; 

#获取令牌
$token = isset($_REQUEST['token'])?$_REQUEST['token']:NULL;

//!读取配置文件
try{
    $config = parse_ini_file('config.ini',true);

    $url = $config['webinfo']['url'];#获取网站的url
    $title = $config['webinfo']['title'];#获取网站的标题

    #本地文件是否为pixiv的图片
    if ($config['webinfo']['url'] == "Ture"){
        $FromPixiv = True;
    }
    else{
        $FromPixiv = False;
    }

    #是否启用年龄分级
    if ($config['webinfo']['classify'] == "True"){
        $Classify = True;
    }
    else{
        $Classify = False;
    }

    #是否默认启用压缩图片；启用后将优先检索Compression文件夹（图片压缩后会保存在此目录）。
    if ($config['webinfo']['compression'] == "True"){
        $Compression = True;
    }
    else{
        $Compression = False;
    }

    #是否建立图片遍历表
    if($config['webinfo']['picturelistcache'] == "True"){
        $PictureListCache = True;
    }
    else{
        $PictureListCache = False;
    }

    #是否启用令牌系统，分为三个等级：High、Normal、None。High等级限制所有查看，Normal等级只限制17+18+
    $token_salt = intval($config['tokensys']['tokensalt']);#获取一个初始化时生成的随机数，用于生成令牌。
    
    if ($config['tokensys']['tokenenable'] == "High"){
        $token_enable = "High";
    }
    elseif($config['tokensys']['tokenenable'] == "Normal"){
        $token_enable = "Normal";
    }
    else{
        $token_enable = "None";
    }

}catch(Exception $e){
    //*在读取配置文件时失败，停止运行并直接返回710代码。
    $data = ["code"=>710];
    $json = json_encode($data);
    header('Content-Type: application/json');
    echo $json; 
    exit(0);
}finally{};

//!生成图片列表

#图片遍历表
$FullFile = ['./cache/PictureFiles_13','./cache/PictureFiles_15','./cache/PictureFiles_17','./cache/PictureFiles_18'];

if(!$Classify || $selected_age_level == "18"){
    #若分级系统关闭，或者年龄分级为18+，则使用完整的图片遍历表
    $cache_list = $FullFile;
}
elseif($selected_age_level == "AO"){
    #同时加载17+和18+
    $cache_list = array_slice($FullFile,2,2);
}
elseif($selected_age_level == "17"){
    $cache_list = array_slice($FullFile,0,3);    
}
elseif($selected_age_level == "15"){
    $cache_list = array_slice($FullFile,0,2);
}
else{
    $selected_age_level = "13";#如果不存在则自动判定年龄等级为13+
    $cache_list = array_slice($FullFile,0,1);#必须是一个列表
}

//!抽取图片或者打开指定图片




?>