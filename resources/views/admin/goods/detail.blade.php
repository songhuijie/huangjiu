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
                     <input type="text" name="good_title" required  lay-verify="required" value="@if(!empty($label)){{$label->good_title}} @endif" placeholder="请输入标题" autocomplete="off" class="layui-input">
                 </div>
             </div>
             <div class="layui-form-item">
                 <label class="layui-form-label">副标题</label>
                 <div class="layui-input-block">
                     <input type="text" name="good_dsc" required  lay-verify="required" value="@if(!empty($label)){{$label->good_dsc}} @endif" placeholder="请输入副标题" autocomplete="off" class="layui-input">
                 </div>
             </div>

             <div class="layui-form-item">
                <label class="layui-form-label">商品类型</label>
                <div class="layui-input-block">
                    <select name="good_type" lay-verify="">
                        @foreach($goods_type as $k=>$v)
                            <option value="{{$k}}" @if(!empty($label) && $label->good_type == $k) selected @endif>{{$v}}</option>
                        @endforeach
                    </select>
                </div>
             </div>

             <div class="layui-form-item">
                 <label class="layui-form-label">提成价格</label>
                 <div class="layui-input-block">
                     <input type="text" name="royalty_price" required  lay-verify="required" value="@if(!empty($label)){{$label->royalty_price}} @endif" placeholder="请输入提成价格" autocomplete="off" class="layui-input">
                 </div>
             </div>

             <div class="layui-form-item">
                 <label class="layui-form-label">原价格</label>
                 <div class="layui-input-block">
                     <input type="text" name="old_price" required  lay-verify="required" value="@if(!empty($label)){{$label->old_price}} @endif" placeholder="请输入原价格" autocomplete="off" class="layui-input">
                 </div>
             </div>

             <div class="layui-form-item">
                 <label class="layui-form-label">新价格</label>
                 <div class="layui-input-block">
                     <input type="text" name="new_price" required  lay-verify="required" value="@if(!empty($label)){{$label->new_price}} @endif" placeholder="请输入新价格" autocomplete="off" class="layui-input">
                 </div>
             </div>

             <div class="layui-form-item">
                 <label class="layui-form-label">库存</label>
                 <div class="layui-input-block">
                     <input type="text" name="stock" required  lay-verify="required" value="@if(!empty($label)){{$label->stock}} @endif" placeholder="请输入库存" autocomplete="off" class="layui-input">
                 </div>
             </div>

            <div class="layui-form-item">
                 <label class="layui-form-label">商品重量(g)</label>
                 <div class="layui-input-block">
                     <input type="text" name="weight" required  lay-verify="required" value="@if(!empty($label)){{$label->weight}} @endif" placeholder="请输入库存" autocomplete="off" class="layui-input">
                 </div>
             </div>


             <div class="layui-form-item">
                 <label class="layui-form-label">商品大图</label>
                 <button type="button" class="layui-btn" id="test1">
                     <i class="layui-icon">&#xe67c;</i>上传图片
                 </button>
                 <img @if(!empty($label)) src="../{{$label->good_image}}" @else src="" @endif name="headimg" id="img1" width="20%">
                 <input type="text" name="good_image" id="good_image" hidden value="@if(!empty($label)){{$label->good_image}} @endif" >
             </div>


             <div class="layui-form-item">
                 <label class="layui-form-label">轮播图json格式</label>
                 <button type="button" class="layui-btn" id="test2">
                     <i class="layui-icon">&#xe67c;</i>上传图片
                 </button>
                 <div id="img2">
                     @if(!empty($label))
                         @foreach($label->rotation as $key)
                             <input type="text" value="{{$key}}" hidden name="img[]">
                             <img  src="../{{$key}}"    width="20%" title="点击删除">
                         @endforeach
                     @else
                     <!-- <input type="text" value="" hidden name="img[]"> -->
                         <img   src=""  >
                     @endif
                 </div>
             </div>




             <div class="layui-form-item layui-form-text">
                 <label class="layui-form-label">详情:</label>
                 <div class="layui-input-block">
                     <textarea name="detail" id="qaContent" lay-verify="content">@if(!empty($label)){{$label->detail}} @endif</textarea>
                 </div>
             </div>


             <div class="layui-form-item">
                 <label class="layui-form-label">运费</label>
                 <div class="layui-input-block">
                     <input type="radio" name="freight" value="0" title="包邮" checked>
                     <input type="radio" name="freight" value="1" title="不包邮" >
                 </div>
             </div>

             <div class="layui-form-item">
                 <label class="layui-form-label">状态</label>
                 <div class="layui-input-block">
                     <input type="radio" name="goods_status" value="1" title="出售中" checked>
                     <input type="radio" name="goods_status" value="2" title="下架" >
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
    //单击图片删除图片 【注册全局函数】
    $('#img2').on('click','img',function(){
        $(this).prev().remove();
        $(this).remove();
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
                ,multiple: true
            }
        });
        var editIndex = layedit.build('qaContent'); // 建立编辑器


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
                    $('#img2').append('<input type="text" value="'+index.data+'" hidden name="img[]"> <img src="'+ img +'"  name="img[]" width="20%">')

                }
            }
        });
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
                    $('#good_image').val(index.data)
                }
            }
        });
        form.verify({
            content:function () {
                layedit.sync(editIndex);
            }
        });
    //监听提交
    form.on('submit(formDemo)', function(data){
        
        var date=data.field;
        
        if(date.good_title=="" || date.good_type=="" || date.royalty_price=="" || date.old_price=="" || date.new_price=="" || date.stock=="" || date.good_image==""  || date.detail=="" || date.freight=="" || date.goods_status==""){
            console.log(date.good_title);
            console.log(date.good_type);
            console.log(date.royalty_price);
            console.log(date.old_price);
            console.log(date.new_price);
            console.log(date.stock);
            console.log(date.good_image);
            console.log(date.rotation);
            console.log(date.detail);
            console.log(date.freight);
            console.log(date.goods_status);
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
            url:"{{url('goods/detail')}}",
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