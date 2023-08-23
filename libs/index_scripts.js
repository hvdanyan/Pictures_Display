//获取get方式中link的内容
function get_link(){

    //jquery中有一个函数可以实现相同的功能$.urlParam(name)
    var get = window.location.href.match(/link.+/);
    
    if(!get){
        return false;
    }
    else{
        get = get[0];
    }

    get = get.slice(5);
    //排除后面如果还有其他的get内容
    var nextPar = get.indexOf("&");
    if (nextPar != -1) {
        get = get.slice(0, nextPar);
    }
    return get;
}

function request_files(){
    $.post("./Get_Files.php",
    {
        agelevel:$("#agelevel").val(),
        token:$("#token").val(),
        link:$("#link").val()
    },
    function(data,status){
        if(data.code >= 713){
            $("#token_verify").show();
            $("#display_stage").hide();
            if(data.code == 714){
                alert("输入的令牌错误，请重新输入。");

            }
        }

        if(data.code == 700){
            $("#token_verify").hide();
            $("#display_stage").show();

            $("#display_stage").css("background-image",`url(${data.picture})`);
            $("#enlargepic_href").attr("href",window.location.origin+window.location.pathname+data.picture);
            $("#shared_url").text(window.location.origin+window.location.pathname+"?link="+data.picture_name);
            $("#totalnum_text").text(data.total);
            if(data.is_classified == "True"){
                $("#piclevel_text").text(data.piclevel);
            }
            else{
                $("#currentagelevel_text").hide();
                $("#agelevel_text").hide();
                $("#currentpiclevel_text").hide();
                $("#piclevel_text").hide();
            }
            if(data.is_pixiv == "True"){
                $("#pixivurl_text").text(data.picture_root); 
                $("#pixivurl_text").attr("href",data.origin_link); 

                if(data.find_same == "True"){
                    $("#find_similar_pic").show();
                    $("#similar_pic").show();
                }
                else{
                    $("#find_similar_pic").hide();
                    $("#similar_pic").hide();
                }
                if(data.is_gif == "True"){
                    $("#is_gif").show();
                }
                else{
                    $("#is_gif").hide();
                }
                if(data.compress == "True"){
                    $("#origin_button").show();
                }
                else{
                    $("#origin_button").hide();
                }

            }
            else{
                $("#pixivID_text").hide();
                $("#pixivurl_text").hide();
            }
            
        }

    }
    )
}

function copyurl(){//复制分享链接
    if (navigator.clipboard && window.isSecureContext) {//安全域下
        var shared_url=document.getElementById("shared_url");
        navigator.clipboard.writeText(shared_url);
    }
    else{//非安全域下上面的方法被禁用
        var input=document.getElementById("shared_url");
        input.select(); 
        document.execCommand("Copy"); 
    }
    alert("链接复制成功！");
    
}

function showorigin(){
    //$('.theframe').css('background-image', 'url(%s)');
    $("#origin_button").hide();
}

function search_file(){
    $("#link").val($("#target_pic").val());//先将link修改为和target_pic一致
    request_files();
    $("#link").val("");//清空link
    scrollTo(0,0);//回到顶部
}

function set_agelevel(){
    $("#agelevel").val($('input[name="agelevel"]:checked').val());
    request_files();
    $("#agelevel_text").text($('input[name="agelevel"]:checked').val()+"+");
    scrollTo(0,0);//回到顶部
}

function set_token(){
    $("#token").val($('input[name="token"]').val());
    request_files();
    scrollTo(0,0);//回到顶部
}

function getting_token(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            alert(xmlhttp.responseText);
        }
    }

    xmlhttp.open("post","./advanced/Get_Token.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=utf-8");
    xmlhttp.send($("#get_token_form").serialize());
}

function invokeSettime(obj){
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

