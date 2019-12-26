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
                          <input type="text" name="secret" required  lay-verify="required" value="{{$config->secret}}" placeholder="请输入小程序密匙" autocomplete="off" class="layui-input">
                        </div>
                      </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">商户ID</label>
                        <div class="layui-input-block">
                            <input type="text" name="mch_id" required  lay-verify="required" value="{{$config->mch_id}}" placeholder="请输入商户ID" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商户密匙</label>
                        <div class="layui-input-block">
                            <input type="text" name="mch_secret" required  lay-verify="required" value="{{$config->mch_secret}}" placeholder="请输入商户密匙" autocomplete="off" class="layui-input">
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">腾讯地图API_KEY</label>
                        <div class="layui-input-block">
                            <input type="text" name="map_key" required  lay-verify="required" value="{{$config->map_key}}" placeholder="请输入腾讯地图API_KEY" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">腾讯地图API_SECRET_KEY</label>
                        <div class="layui-input-block">
                            <input type="text" name="map_secret_key" required  lay-verify="required" value="{{$config->map_secret_key}}" placeholder="请输入腾讯地图API_SECRET_KEY" autocomplete="off" class="layui-input">
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">微信退款 cert_pem</label>
                        <div class="layui-input-block">
                            <input type="text" name="cert_pem" required  lay-verify="required" value="{{$config->cert_pem}}" placeholder="请输入微信退款 cert_pem" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">微信退款 key_pem</label>
                        <div class="layui-input-block">
                            <input type="text" name="key_pem" required  lay-verify="required" value="{{$config->key_pem}}" placeholder="请输入微信退款 key_pem" autocomplete="off" class="layui-input">
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
});

  //监听提交
  form.on('submit(formDemo)', function(data){
    var date=data.field;

    if(date.appid==""||date.secret==""||date.mch_id==""||date.mch_secret==""||date.map_key==""||date.map_secret_key==""||date.cert_pem==""||date.key_pem==""){
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