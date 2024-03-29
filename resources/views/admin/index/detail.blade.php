<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>
    <link href="{{asset('assets/libs/layui/css/layui.css')}}" rel="stylesheet">
    <link href="{{asset('page/table/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('page/table/vendor/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
    <link href="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
    <link href="{{asset('page/table/dist/css/sb-admin-2.css')}}" rel="stylesheet">
    <link href="{{asset('page/table/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    
    <style>
        .page-header{
            border-bottom: none !important;
        }
        #page-wrapper{
            border-left: none !important;
        }
    </style>
</head>

<body>
    <div id="wrapper" style="margin-top:20px;">
      <div id="page-wrapper">
         <form class="layui-form"  >


            <div class="layui-form-item">
                <label class="layui-form-label">管理头像</label>
                <button type="button" class="layui-btn" id="test1">
                  <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
                <img @if(!empty($admin)) src="{{$admin->headimg}}" @else src="" @endif name="headimg" id="img1">
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">账户</label>
                <div class="layui-input-block">
                    <input type="text" name="username" required  lay-verify="required" @if(!empty($admin)) value="{{$admin->username}}" @endif placeholder="请输入管理账户" autocomplete="off" class="layui-input">
                    
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" required  lay-verify="required" @if(!empty($admin)) value="{{$admin->password}}" @endif placeholder="请输入密码,若不修改密码不用填写" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                  <input type="radio" name="status" value="0" title="否"   @if(!empty($admin)) @if($admin->status==0) checked @endif  @endif  >
                  <input type="radio" name="status" value="1" title="是"   @if(!empty($admin))  @if($admin->status==1) checked @endif  @else checked @endif>
                </div>
            </div>
                      
            @if(!empty($admin))
                <input type="text" id="mold" hidden  value="edit" >
                <input type="text" id="id" hidden value="{{$admin->id}}" >
            @endif

            <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>
                  <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
                
         </form>


      </div>
        

    </div>
    
    <script src="{{asset('page/table/vendor/jquery/jquery.min.js')}}"></script>
    <!-- <script src="{{asset('assets/libs/layui/lay/modules/jquery.js')}}"></script> -->
    <script src="{{asset('page/table/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/metisMenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
    <script src="{{asset('page/table/dist/js/sb-admin-2.js')}}"></script>

    <script src="{{asset('assets/libs/layui/layui.all.js')}}"></script>
    <!-- <script src="{{asset('assets/libs/layui/layui.js')}}"></script> -->

    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });

    layui.use(['util','form','laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element', 'slider'], function(){
        var laydate = layui.laydate //日期
        ,laypage = layui.laypage //分页
        ,layer = layui.layer //弹层
        ,table = layui.table //表格
        ,carousel = layui.carousel //轮播
        ,upload = layui.upload //上传
        ,element = layui.element //元素操作
        ,slider = layui.slider //滑块
        var form = layui.form;

     
        //单图上传
        upload.render({
            elem: '#test1'
            ,method: 'post'
            ,multiple: false //是否允许多文件上传。设置 true即可开启。不支持ie8/9
            ,url: '{{URL("file/img")}}' //上传接口
            ,done: function(index, upload){
            //获取当前触发上传的元素，一般用于 elem 绑定 class 的情况，注意：此乃 layui 2.1.0 新增
            if(index.code!=0){
                layer.msg("上传错误",{icon:5});
            }else{
                layer.msg("上传成功",{icon:6});
                img="../"+index.data;
                $("#img1").attr("src",img);
            }
          }
        })

    //监听提交
    form.on('submit(formDemo)', function(data){
        
        var date=data.field;
        date.headimg=$("#img1").attr("src");
       
        if(date.username==""||date.password==""||date.headimg==""){
            layer.msg("不能为空,请填写完整,或刷新重试",{icon:5});return false;
        }
        
        date.type="add";
        if($("#mold").val()!=undefined){
             date.update="update";
             date.id=$("#id").val();
             date.type="edit";
        }

        $.ajax({
            data:date,
            type:"post",
            datatype:"json",
            url:"{{url('admin/detail')}}",
            success:function(res){
                if(res.code==0){
                      parent.layer.msg(res.msg,{icon:5});
                }else{
                    parent.layer.msg(res.msg,{icon:6});
                    setTimeout(function(){
                        parent.layer.closeAll();
                        parent.location.reload();
                    },1000)
                }
                
            }

        })
        return false;
    });
     //底部信息
  // var footerTpl = lay('#footer')[0].innerHTML;
  // lay('#footer').html(layui.laytpl(footerTpl).render({}))
  // .removeClass('layui-hide');
});
    </script>

</body>

</html>