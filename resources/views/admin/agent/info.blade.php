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

                @if(!$first && !$second)
                    <label>暂无下级</label>
                @else
                    <p style="text-align: center">点击取消 取消掉配送人员和代理信息</p><br>
                    @if($first)
                        <label>设置一级代理</label>
                        @foreach($first as $v)
                            <div class="layui-form-item">

                                <label class="layui-form-label">{{$v['user_name']}}</label>
                                <div class="layui-input-block">
                                    <div class="layui-input-block">
                                        @if($v)
                                            <form class="layui-form" >
                                                <input type="radio" name="status"  value="2" title="设置一级代理" {{$v['status'] ==2 ?"checked":'' }}>
                                                <input type="checkbox" name="delivery[delivery]"  title="设置配送人员" {{$v['is_delivery'] ==1 ?"checked":'' }}>
                                                <input type="radio" name="status" data-id="{{$v['user_id']}}" value="0" title="取消">
                                                <input type="hidden" name="user_id" value="{{$v['user_id']}}">
                                                <button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>
                                            </form>
                                        @else
                                            <form class="layui-form" >
                                                <input type="radio" name="status" value="2" title="设置一级代理" >
                                                <input type="checkbox" name="delivery[delivery]" title="设置配送人员">
                                                <input type="radio" name="status" value="0" title="取消">
                                                <input type="hidden" name="user_id" value="{{$v['user_id']}}">
                                                <button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>
                                            </form>
                                        @endif

                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($second)
                            <label>设置一至二级代理</label>
                        @foreach($second as $v)
                            <div class="layui-form-item">

                                <label class="layui-form-label">{{$v['user_name']}}</label>
                                <div class="layui-input-block">
                                    @if($v)
                                        <form class="layui-form" >
                                            <input type="hidden" name="user_id" value="{{$v['user_id']}}">
                                            <input type="radio" name="status"  value="2" title="设置一级代理" {{$v['status'] ==2 ?"checked":'' }}>
                                            <input type="radio" name="status"  value="3" title="设置二级代理" {{$v['status'] ==3 ?"checked":'' }}>
                                            <input type="checkbox" name="delivery[delivery]"  title="设置配送人员" {{$v['is_delivery'] ==1 ?"checked":'' }}>
                                            <input type="radio" name="status" data-id="{{$v['user_id']}}" value="0" title="取消">
                                            <button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>
                                        </form>
                                    @else
                                        <form class="layui-form" >
                                            <input type="hidden" name="user_id" value="{{$v['user_id']}}">
                                            <input type="radio" name="status" value="2" title="设置一级代理" >
                                            <input type="radio" name="status" value="3" title="设置二级代理" >
                                            <input type="checkbox" name="delivery[delivery]" title="设置配送人员">
                                            <input type="radio" name="status" value="0" title="取消">
                                            <button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif




                      <div class="layui-form-item">
                        <div class="layui-input-block">
                          {{--<button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>--}}
                          {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
                        </div>
                      </div>



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
        /**
         * 监控 jq checkbox 点击
         */
        $("input[name=status]").each(function(){
            $(this).click(function(){
                var discount = $(this).val();
                console.log(discount);
                if(discount=="0"){
                    $(".discount").css("display","none");
                }
                if(discount=="1"){
                    $(".discount").css("display","inline");
                }
            });
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
//执行实例 多图上传
        upload.render({
            elem: '#test2'
            ,method: 'post'
            ,multiple: true //是否允许多文件上传。设置 true即可开启。不支持ie8/9
            ,url: '{{URL("file/img")}}' //上传接口
            ,done: function(index, upload){
                //获取当前触发上传的元素，一般用于 elem 绑定 class 的情况，注意：此乃 layui 2.1.0 新增
                if(index.code!=0){
                    layer.msg("上传错误",{icon:5});
                }else{
                    layer.msg("上传成功",{icon:6});
                    img="../"+index.data;
                    $("#img2").append('<img src="'+img+'" name="img[]" width="20%"><input type="text" value="'+index.data+'" hidden name="rotation[]">')
                }
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
            url:"{{url('agent/info')}}",
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