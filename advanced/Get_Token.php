<?php 
//header('Content-Type: application/json');
include '../libs/public_func.php';

#追踪错误,将错误以异常的形式抛出
set_error_handler("Error_trans_excpt");

$config = parse_ini_file('../config.ini',true);

$token_salt = intval(isset($config['tokensys']['tokensalt'])?$config['tokensys']['tokensalt']:"0");#获取一个初始化时生成的随机数，用于生成令牌。

echo "以下字段均为可用的令牌：";
for($i=1;$i<10;$i+=1){
    //$present_time = intval(substr(date('YmdH',time()),2).strval(mt_rand(1,9)));#获取时间，并转化成特殊格式（年+月+日+时），如21072310并在后面增加一个随机数字
    $present_time = intval(substr(date('YmdH',time()),2).strval($i));
    try{
        strtoupper(dTA(aTD(strrev(dTA(intval(strrev(strval($present_time^$token_salt))),16)),16),24));#将时间转化成令牌
    }catch(Exception $e){
        echo '{"code":714}';//输出714，令牌解析异常
        exit(0);
    }
    $valid_token = strtoupper(dTA(aTD(strrev(dTA(intval(strrev(strval($present_time^$token_salt))),16)),16),24));#将时间转化成令牌

    if($present_time == strval(intval(strrev(strval(aTD(strrev(dTA(aTD(strtolower($valid_token),24),16)),16))))^$token_salt)){
        echo $valid_token." ";
    }
}
?>