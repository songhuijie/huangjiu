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
                <form class="layui-form" action="">
                      <div class="layui-form-item">
                        <label class="layui-form-label">小程序appid</label>
                        <div class="layui-input-block">
                          <input type="text" name="appid" required  lay-verify="required" value="{{$config->appid}}" placeholder="请输入appid" autocomplete="off" class="layui-input">
                        </div>
                      </div>
                      <div class="layui-form-item">
                        <label class="layui-form-label">小程序密匙</label>
                        <div class="layui-input-block">
                          <input type="text" name="app_secret" required  lay-verify="required" value="{{$config->app_secret}}" placeholder="请输入小程序密匙" autocomplete="off" class="layui-input">
                        </div>
                      </div>
                        
                      <div class="layui-form-item">
                        <label class="layui-form-label">服务电话</label>
                        <div class="layui-input-block">
                          <input type="text" name="phone" required  lay-verify="required" value="{{$config->phone}}"  placeholder="请输入服务电话" autocomplete="off" class="layui-input">
                        </div>
                      </div>

                      <div class="layui-form-item">
                        <label class="layui-form-label">网站名称</label>
                        <div class="layui-input-block">
                          <input type="text" name="web_name" required  lay-verify="required" value="{{$config->web_name}}" placeholder="请输入网站名称" autocomplete="off" class="layui-input">
                        </div>
                      </div>

                        <!-- 头像 -->

                      <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">网站头像</label>
                        <button type="button" class="layui-btn" id="test1">
                          <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <img src="{{$config->web_headimg}}" name="web_headimg" id="img">
                      </div>

                      <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">版权信息</label>
                        <div class="layui-input-block">
                          <textarea name="web_information" placeholder="请输入内容"  class="layui-textarea">{{$config->web_information}}</textarea>
                        </div>
                      </div>

                    
                      
                      <div class="layui-form-item">
                        <label class="layui-form-label">是否开启过申页</label>
                        <div class="layui-input-block">
                          <input type="radio" name="app_examine" value="0" title="否" @if($config->app_examine==0) checked  @endif>
                          <input type="radio" name="app_examine" value="1" title="是" @if($config->app_examine==1) checked  @endif >
                        </div>
                      </div>
                      <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">过申富文本</label>
                        <div class="layui-input-block">
                          <textarea name="app_exam_text" placeholder="请输入内容"  class="layui-textarea">{{$config->app_exam_text}}</textarea>
                        </div>
                      </div>

                      <div class="layui-form-item">
                        <div class="layui-input-block">
                          <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                      </div>
                </form>
        </div>
        

    </div>
    
    <script src="{{asset('page/table/vendor/jquery/jquery.min.js')}}"></script>
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
    </script>

     <script>
layui.config({
  version: '1568076536509' //为了更新 js 缓存，可忽略
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

  var img='';   
  //执行实例
  upload.render({
  elem: '#test1'
  ,method: 'post'
  ,url: '{{URL("file/img")}}' //上传接口
  ,done: function(index, upload){
    //获取当前触发上传的元素，一般用于 elem 绑定 class 的情况，注意：此乃 layui 2.1.0 新增
    if(index.code!=0){
        layer.msg("上传错误",{icon:5});
    }else{
        layer.msg("上传成功",{icon:6});
        img="../"+index.data;
        $("img").attr("src",img);
    }
    
  }
})

  //监听提交
  form.on('submit(formDemo)', function(data){
    var date=data.field;

    if(date.appid==""||date.app_secret==""||date.phone==""||date.web_name==""||date.web_information==""||date.app_exam_text==""||$("img").attr("src")==""){
        layer.msg("不能为空,请填写完整",{icon:5});return false;
    }
    date.web_headimg=$("img").attr("src");
    date.type="edit";
    $.ajax({
        data:date,
        type:"post",
        datatype:"json",
        url:"{{url('config/index')}}",
        success:function(res){
            console.log(res);
            if(res.status==0){
                layer.msg(res.msg,{icon:5});
            }else{
                layer.msg(res.msg,{icon:6});
                 setTimeout(function(){
                       window.location.reload();
                  }, 1000);
            }
            return false;
        }

    })

    // layer.msg(JSON.stringify(data.field));
    // console.log(JSON.stringify(data.field));
    
  });
  
  //底部信息
  var footerTpl = lay('#footer')[0].innerHTML;
  lay('#footer').html(layui.laytpl(footerTpl).render({}))
  .removeClass('layui-hide');
});
</script>


</body>

</html>