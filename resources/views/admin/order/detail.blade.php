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

                         @switch($order->order_status)
                             @case(1)
                             <div class="layui-form-item">
                                 <label class="layui-form-label">订单状态</label>
                                     <div class="layui-input-block">
                                         <select name="order_status" lay-verify="">
                                                 <option value="2">配送</option>
                                                 <option value="5">退款</option>
                                         </select>
                                     </div>
                             </div>


                             <div class="layui-form-item">
                                 <label class="layui-form-label">快递单号</label>
                                 <div class="layui-input-block">
                                     <input type="text" name="express" required  lay-verify="required" value="@if(!empty($label)){{$label->express}} @endif" placeholder="请输入快递单号" autocomplete="off" class="layui-input">
                                 </div>
                             </div>

                             <div class="layui-form-item">
                                 <label class="layui-form-label">快递类型</label>
                                 <div class="layui-input-block">
                                     <select name="express_type" lay-verify="">
                                         @foreach($express_type as $k=>$v)
                                             <option value="{{$k}}" {{$k==1?'selected':''}}>{{$v}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                                @break
                             @case(2)
                                 <div class="layui-form-item">
                                     <label class="layui-form-label">订单状态</label>
                                     <div class="layui-input-block">
                                         <select name="order_status" lay-verify="">
                                             <option value="3">发货</option>
                                             <option value="5">退款</option>
                                         </select>
                                     </div>
                                 </div>
                                @break
                             @case(3)
                                 <div class="layui-form-item">
                                     <label class="layui-form-label">订单状态</label>
                                     <div class="layui-input-block">
                                         <select name="order_status" lay-verify="">
                                             <option value="4">完成</option>
                                             <option value="5">退款</option>
                                         </select>
                                     </div>
                                 </div>
                                @break
                             @case(4)
                                 <div class="layui-form-item">
                                     <label class="layui-form-label">订单状态</label>
                                     <div class="layui-input-block">
                                         <select name="order_status" lay-verify="">
                                             <option value="6">取消</option>
                                             <option value="5">退款</option>
                                         </select>
                                     </div>
                                 </div>
                                @break
                         @endswitch


                      
                      @if(!empty($order))
                        <input type="text" id="mold" hidden  value="edit" >
                        <input type="text" id="id" hidden value="{{$order->id}}" >
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
            url:"{{url('order/detail')}}",
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