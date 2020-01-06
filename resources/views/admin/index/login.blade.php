<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <link href="{{asset('login/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
    <style>
        body{
            background: url({{asset('login/img/bg.png')}}) no-repeat center center fixed;
            background-size: 100% 100%;
        }
        .input-layout{
            min-height: 300px;
            margin-top: 20%;    width: 35%;
        }
        .modal-header,.modal-body{
            border-bottom: 0;
        }
        .modal-body{
            padding-top: 30px;
        }
        .modal-footer{
            border-top: 0;
            padding-top: 20px;
            padding-bottom: 30px;
        }
        .login-btn{
            border-radius: 15px;
            height: 40px;
            line-height: 40px;
            padding-top: 0;
            padding-bottom: 0;
            background-color: #4cacff !important;
            color: #fff;
            font-size: 20px !important;
        }
        .login-btn:active{
            border: 0 !important;
            color: #fff;
        }
        .login-btn:hover{
            color: #fff;
        }
        .title{
            padding: 20px 0 0;
            font-size: 22px;
        }
        .form-group{
            width: 90% !important;
            margin: auto;
            border: 0;
            margin-bottom: 20px;
        }
        input{
            border-radius: 15px !important;
            height: 70px;
            line-height: 70px;
        }
        .modal-content{
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            min-height: 420px;
            border-radius: 15px;
        }
        .form-control{
            height: 50px;
            border-radius: 100px !important;
            background-color: #f2f2f2;
            border: 0;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            font-size: 16px;
        }
        .form-control:focus{
            border: none !important;
        }
    </style>
        <link href="{{asset('assets/libs/layui/css/layui.css')}}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-offset-8 col-md-4 input-layout">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center title" id="myModalLabel">欢迎来到线上客客户管理平台</h4>
                </div>
                <div class="modal-body" id="model-body">
                    <form action="admin/login" method="post" οnsubmit="return toVaild()" id="form">
                        <div class="form-group" style="padding-bottom: 20px;">
                            <input type="text" class="form-control" placeholder="请输入您的用户名" autocomplete="off" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="请输入您的密码" autocomplete="off" name="password" id="password">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button onclick="login();" class="btn  form-control login-btn" style="height: 55px;line-height: 55px;" id="submit">登录</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('login/jquery/jquery.js')}}"></script>
<script src="{{asset('login/bootstrap/js/bootstrap.js')}}"></script>

<script src="{{asset('assets/libs/layui/layui.all.js')}}"></script>
<script src="{{asset('assets/libs/layui/layui.js')}}"></script>
<script>
    function login(){
        var formObject = {};
        var formArray =$("#form").serializeArray();
        $.each(formArray,function(i,item){
            formObject[item.name] = item.value;
        });
        
        if(formObject.password==""||formObject.username==""){
                layer.msg('请填写完整',{icon:5});return false;
        }
        var form=JSON.stringify(formObject);

        $.ajax({
            data:formObject,
            type:"post",
            datatype:"json",
            url:"{{URL('admin/login')}}",
            success:function(res){
                console.log(res);
                if(res.status!=2){
                    layer.msg(res.msg,{icon:5});return false;
                }else{
                    layer.msg(res.msg,{icon:6});
                    
                    setTimeout(function(){
                        window.location.href = "/index/index"; 
                    }, 1000);
                    
                }
            }
        });
    }
    //验证
    function toVaild(){
        return true;
    }
</script>
</body>
</html>

 