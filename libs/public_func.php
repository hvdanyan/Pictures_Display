<?php

function Error_trans_excpt($type,$message,$file,$line){
    throw new \Exception($message);
}

#十进制转任意进制（小于24进制），num是原数字，n是目标进制
function dTA($num,$n){
    while($num != 0){
        $baseStr = [10=>'a',11=>'b',12=>'c',13=>'d',14=>'e',15=>'f',16=>'g',17=>'f',18=>'i',19=>'j',20=>'k',21=>'l',22=>'m',23=>'n'];
        $new_num_str = "";
        $remainder = $num % $n;
        if($remainder > 9){
            $remainder_string = $baseStr[$remainder];
        }
        else{
            $remainder_string = strval($remainder);
        }
        $new_num_str = $remainder_string.$new_num_str;
        $num = intval($num/$n);
    }
    return $new_num_str;
}

#任意进制（小于24进制）转10进制，num是原数字，n是原进制
function aTD($num,$n){
    $baseStr = ["0"=>0,"1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"a"=>10,"b"=>11,"c"=>12,"d"=>13,"e"=>14,"f"=>15,"g"=>16,"h"=>17,"i"=>18,"j"=>19,"k"=>20,"l"=>21,"m"=>22,"n"=>23];
    $new_num = 0;
    $nNum = $len = strlen($num) - 1;
    for($i=0;$i<=$len;$i++){
        $new_num += $baseStr[$num[$i]] * pow($n,$nNum);
        $nNum = $nNum - 1;
    }
    return $new_num;
}

function Image_list($PictureListCache)
?>