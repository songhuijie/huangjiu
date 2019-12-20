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
         <form class="layui-form" >


             <div class="layui-form-item">
                 <label class="layui-form-label">标题</label>
                 <div class="layui-input-block">
                     <input type="text" name="title" required  lay-verify="required" value="@if(!empty($label)){{$label->title}} @endif" placeholder="请输入标题" autocomplete="off" class="layui-input">
                 </div>
             </div>

             <div class="layui-form-item">
                 <label class="layui-form-label">作者</label>
                 <div class="layui-input-block">
                     <input type="text" name="author" required  lay-verify="required" value="@if(!empty($label)){{$label->author}} @endif" placeholder="请输入作者" autocomplete="off" class="layui-input">
                 </div>
             </div>



             <div class="layui-form-item layui-form-text">
                 <label class="layui-form-label">详情:</label>
                 <div class="layui-input-block">
                     <textarea name="content" id="qaContent" lay-verify="content">@if(!empty($label)){{$label->content}} @endif</textarea>
                 </div>
             </div>

                      
                      @if(!empty($label))
                        <input type="text" id="mold" hidden  value="edit" >
                        <input type="text" id="id" hidden value="{{$label->id}}" >
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

    layui.use(['util','form','laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element', 'slider','layedit'], function(){
        var laydate = layui.laydate //日期
        ,laypage = layui.laypage //分页
        ,layer = layui.layer //弹层
        ,table = layui.table //表格
        ,carousel = layui.carousel //轮播
        ,upload = layui.upload //上传
        ,element = layui.element //元素操作
        ,slider = layui.slider //滑块
        var form = layui.form;
        var layedit = layui.layedit;
        layedit.set({
            uploadImage: {
                url: '/layer/upload' //接口url
                ,type: 'post' //默认post
            }
        });
        var editIndex = layedit.build('qaContent'); // 建立编辑器

        form.verify({
            content:function () {
                layedit.sync(editIndex);
            }
        });
    //监听提交
    form.on('submit(formDemo)', function(data){
        
        var date=data.field;
        
        if(date.types==""||date.label==""){
            layer.msg("不能为空,请填写完整",{icon:5});return false;
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
            url:"{{url('recommend/detail')}}",
            success:function(res){
                console.log(res);
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