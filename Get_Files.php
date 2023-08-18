<?php
//*Version:0.0.1
//*Response in json code.

header('Content-Type: application/json');
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
$config = parse_ini_file('config.ini',true);

$url = isset($config['webinfo']['url'])?$config['webinfo']['url']:'PicturesDisplayer';#获取网站的url
$title = isset($config['webinfo']['title'])?$config['webinfo']['title']:'PicturesDisplayer';#获取网站的标题

#本地文件是否为pixiv的图片
if (isset($config['webinfo']['url'])?$config['webinfo']['url']:"" == "True"){
    $FromPixiv = true;
}
else{
    $FromPixiv = false;
}

#是否启用年龄分级
if (isset($config['webinfo']['classify'])?$config['webinfo']['classify']:"" == "True"){
    $Classify = true;
}
else{
    $Classify = false;
}

#是否默认启用压缩图片；启用后将优先检索Compression文件夹（图片压缩后会保存在此目录）。
if (isset($config['webinfo']['compression'])?$config['webinfo']['compression']:"" == "True"){
    $Compression = true;
}
else{
    $Compression = false;
}

#是否建立图片遍历表
if(isset($config['webinfo']['picturelistcache'])?$config['webinfo']['picturelistcache']:"" == "True"){
    $PictureListCache = true;
}
else{
    $PictureListCache = false;
}

#是否启用令牌系统，分为三个等级：High、Normal、None。High等级限制所有查看，Normal等级只限制17+18+
$token_salt = intval(isset($config['tokensys']['tokensalt'])?$config['tokensys']['tokensalt']:"0");#获取一个初始化时生成的随机数，用于生成令牌。

$token_enable = isset($config['tokensys']['tokenenable'])?$config['tokensys']['tokenenable']:"";
if ($token_enable !== "High"||$token_enable !== "Normal"){
    $token_enable = "None";#当等级不是High或者Normal时，自动调整为None
}


//!生成图片列表

#图片遍历表
$FullFile = ['UploadFiles/documented/'=>'./cache/PictureFiles_13.json','UploadFiles/documented/classified/15+/'=>'./cache/PictureFiles_15.json','UploadFiles/documented/classified/17+/'=>'./cache/PictureFiles_17.json','UploadFiles/documented/classified/18+/'=>'./cache/PictureFiles_18.json'];

if(!$Classify || $selected_age_level == "18"){
    #若分级系统关闭，或者年龄分级为18+，则使用完整的图片遍历表
    $cache_list = $FullFile;
}
elseif($selected_age_level == "AO"){
    #同时加载17+和18+
    $cache_list = array_slice($FullFile,2,4);
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

$fullpiclist = array();#新建一个空数组

foreach ($cache_list as $key=>$value) {
    #如果开启遍历表的缓存,
    if($PictureListCache){
        if(file_exists($value) && time()-filemtime($value)<30){
            $fullpiclist = array_merge($fullpiclist,json_decode(file_get_contents($value),true));
            continue;
        }
    }

    #遍历获取文件
    $partpiclist = array();

    if(!is_dir($key)){
        mkdir($key,0777,true);
    }
    $data = scandir($key);
    foreach($data as $file_name){
        if(is_file($key.$file_name)){
            $partpiclist[] = $key.$file_name;
        }
    }

    $fullpiclist = array_merge($fullpiclist,$partpiclist);
    
    if($PictureListCache){
        #将数据存进json文件中
        $fp = fopen($value,'w');
        if(flock($fp,LOCK_EX)){//如果需要不堵塞直接返回失败，使用LOCK_NB|LOCK_EX
            fwrite($fp,json_encode($partpiclist));
            flock($fp,LOCK_UN);

        }
        else{
            //*应该不会启用
            //*在写入json文件时失败，停止运行并直接返回711代码。
            echo '{"code":711}'; 
            exit(0);
        }
        fclose($fp);
    }
}

//!抽取图片或者打开指定图片
if(empty($fullpiclist)){
    //*图库中没有图片时，返回712代码。
    echo '{"code":712}';
    exit(0);
}

if($link == ""){
    $pic_directory = $fullpiclist[array_rand($fullpiclist)];#没有设置就随便抽一张图片
}
else{
    foreach($fullpiclist as $picture){
        if(!preg_match("/".$link."/",$picture)){#尝试完整匹配
            $pic_directory = $fullpiclist[array_rand($fullpiclist)];#则随便抽取一张
        }
        else{
            $pic_directory = $picture;
            break;
        }
    }
}
        

$json = array();
$json["code"] = 700;
$json["picture"] = $pic_directory;
$json["total"]= sizeof($fullpiclist);

if($Classify){
    $json['is_classified'] = "True";
    if(!preg_match('/\d+\+/',$json["picture"],$json["piclevel"])){
        $json["piclevel"] = ["13+"];
    }
    $json["piclevel"]=$json["piclevel"][0];
}
else{
    $json["is_classified"] = "False";
}
if($FromPixiv){
    $json['is_pixiv'] = "True";
    preg_match("/[^\\/=:. ]+\.[^\\/=:. ]+/",$json["picture"],$json["picture_name"]);
    $json["picture_name"]=$json["picture_name"][0];
    preg_match('/\d{4,}/',$json["picture_name"],$json["picture_root"]);
    $json["picture_root"]=$json["picture_root"][0];
    $json["origin_link"] = "https://www.pixiv.net/artworks/".$json["picture_root"];

}
else{
    $json['is_pixiv'] = "False";
}



echo json_encode($json);
exit(0);






?>