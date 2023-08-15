window.alert = function(name){//删除提示框显示域名
    var iframe = document.createElement("IFRAME");
    iframe.style.display="none";
    iframe.setAttribute("src", 'data:text/plain,');
    document.documentElement.appendChild(iframe);
    window.frames[0].window.alert(name);
    iframe.parentNode.removeChild(iframe);
};

function copyurl(){//复制分享链接
    var input=document.getElementById("shared_url");
    input.select(); 
    document.execCommand("Copy"); 
    alert("链接复制成功！");
}

function post(url,s,t,link) {//使用js代码实现隐藏form表单的实现
    var temp = document.createElement("form"); //创建form表单
    temp.action = url;
    temp.method = "post";
    temp.style.display = "none";//表单样式为隐藏
    var adds =document.createElement("input");  
        adds.type="hidden";  
        adds.name = "s";    
        adds.value = s;  
        temp.appendChild(adds);
    var addt =document.createElement("input");  
        addt.type="hidden"; 
        addt.name = "t";    
        addt.value = t;  
        temp.appendChild(addt);
    var addl =document.createElement("input"); 
        addl.type="hidden"; 
        addl.name = "link";   
        addl.value = link;   
        temp.appendChild(addl);
    
    document.body.appendChild(temp);
    temp.submit();
    return temp;
}

function openfeedback(){
    document.getElementById('feedback_content').style.display='block';
    document.getElementById('mainbox').style.display='block';//重置反馈模块
    document.getElementById('responsebox').style.display='none';
    document.getElementById('backoffbox').style.display='none';
}

function closefeedback(){
    document.getElementById('feedback_content').style.display='none';
}

function activatingfeedback(){
    document.getElementById('mainbox').style.display='none';
    document.getElementById("responsebox").innerHTML = "<font size='3px'>正在反馈中……</font>";
    document.getElementById('responsebox').style.display='block';
    var xmlhttp;
    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
        var code;
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            code= xmlhttp.responseText;
            
            if(code != "" && code.search(701) != -1){
                document.getElementById("responsebox").innerHTML = "<font color='red' size='3px'>错误701：SMTP服务器异常，反馈提交失败。</font>";
            }else if (code != "" && code.search(702) != -1){
                document.getElementById("responsebox").innerHTML = "<font color='red' size='3px'>错误702：请勾选相关内容后再提交！</font>";
            }else if (code != "" && code.search(703) != -1){
                document.getElementById("responsebox").innerHTML = "<font color='red' size='3px'>错误703：未能提取出所填写的图片名称。</font>";
            }else if (code != "" && code.search(704) != -1){
                document.getElementById("responsebox").innerHTML = "<font color='green' size='3px'>反馈成功！</font>";
                document.getElementById("submitbutton").innerHTML = "";
                document.getElementById("backoffbox").innerHTML = "";
            }else if (code != "" && code.search(705) != -1){
                document.getElementById("responsebox").innerHTML = "<font size='3px'>测试705：测试705</font>";
            }else{
                document.getElementById("responsebox").innerHTML = "<font size='3px'>错误706：服务器无响应。</font>";
            }
            document.getElementById('backoffbox').style.display='block';
        }
    }  
    xmlhttp.open("post",".",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=utf-8");
    xmlhttp.send($("#apply_link_form").serialize());
}

function gettingtoken(){
    var xmlhttp;
    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
        var code;
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            code= xmlhttp.responseText;
        }
    }
    xmlhttp.open("post",".",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=utf-8");
    xmlhttp.send($("#get_token_form").serialize());
}

function showorigin(){
    $('.theframe').css('background-image', 'url(%s)');
    document.getElementById('originalpic').style.display='none';
}

function invokeSettime(obj){
gettingtoken()
var countdown=30;//30秒后重新发送
settime(obj);
function settime() {
    if (countdown == 0) {
        $("#btn").attr("disabled",false);
        $("#btn").text("获取验证码");
        countdown = 60;
        return;
    } else {
        $("#btn").attr("disabled",true);
        $("#btn").text("(" + countdown + ") s 重新发送");
        countdown--;
    }
    setTimeout(function() {
                settime() }
            ,1000)
}
}