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
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-block">
                            <select name="typeid" lay-verify="">
                                @if(empty($guide))
                                    <option value="">请选择一个类型</option>
                                    @foreach ($type as $key)
                                        @if($key->type==1)
                                            <option value="{{$key->id}}">精选定制--{{$key->type_name}}</option>
                                        @else
                                            <option value="{{$key->id}}">主题定制--{{$key->type_name}}</option>
                                        @endif
                                    @endforeach
                                @else

                                    @foreach ($type as $key)
                                        @if($key->id==$guide->typeid)
                                            <option selected value="{{$key->id}}">@if($key->type==1)精选定制@else主题定制@endif--{{$key->type_name}}</option>
                                        @else
                                             <option value="{{$key->id}}">@if($key->type==1)精选定制@else主题定制@endif--{{$key->type_name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>  
                        </div>
                      </div>

                      <div class="layui-form-item">
                        <label class="layui-form-label">等级</label>
                        <div class="layui-input-block">
                            <select name="gradeid" lay-verify="">
                                @if(empty($guide))
                                    <option value="">请选择一个等级</option>
                                    @foreach ($grade as $key)
                                            <option value="{{$key->id}}">{{$key->name}}</option>
                                    @endforeach
                                @else

                                    @foreach ($grade as $key)
                                        @if($key->id==$guide->gradeid)
                                            <option selected value="{{$key->id}}">{{$key->name}}</option>
                                        @else
                                             <option value="{{$key->id}}">{{$key->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>  
                        </div>
                      </div>

                      <div class="layui-form-item">
                        <label class="layui-form-label">标签</label>
                        <div class="layui-input-block">
                            @if(!empty($guide))
                                @foreach($labelss as $key=>$val)
                                    @if(!empty($val['display']))
                                        <input type="checkbox" name="labelid[]" value="{{$val['id']}}" title="{{$val['label']}}" checked>
                                    @else
                                        <input type="checkbox" name="labelid[]" value="{{$val['id']}}" title="{{$val['label']}}">
                                    @endif
                                @endforeach
                            @else
                                @foreach($labelss as $key=>$val)
                                    <input type="checkbox" name="labelid[]" value="{{$val['id']}}" title="{{$val['label']}}">
                                @endforeach
                            @endif
                        </div>
                      </div>
                      <div class="layui-form-item">
                        <label class="layui-form-label">技师名称</label>
                        <div class="layui-input-block">
                          <input type="text" name="name" required  lay-verify="required" @if(!empty($guide)) value="{{$guide->name}}" @endif  placeholder="请输入技师名称" autocomplete="off" class="layui-input">
                        </div>
                      </div>
                        
                    <div class="layui-form-item">
                        <label class="layui-form-label">技师头像</label>
                        <button type="button" class="layui-btn" id="test1">
                          <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <img @if(!empty($guide)) src="{{$guide->headimg}}" @else src="" @endif name="headimg" id="img1">
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">技师形象</label>
                        <button type="button" class="layui-btn" id="test2">
                          <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div id="img2">
                            @if(!empty($guide))
                                @foreach($guide->img as $key)
                                 <input type="text" value="{{$key}}" hidden name="img[]">
                                  <img  src="{{$key}}"  src=""  >
                                @endforeach
                            @else
                                  <!-- <input type="text" value="" hidden name="img[]"> -->
                                  <img   src=""  >
                            @endif
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">定制价格</label>
                        <div class="layui-input-block">
                          <input type="number" name="price" required  lay-verify="required" @if(!empty($guide)) value="{{$guide->price}}" @endif placeholder="请输入定制价格" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">从业经验</label>
                        <div class="layui-input-block">
                          <input type="number" name="experience" required  lay-verify="required" @if(!empty($guide)) value="{{$guide->experience}}" @endif placeholder="请输入从业经验" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">简介</label>
                        <div class="layui-input-block">
                            <textarea name="brief" required lay-verify="required" placeholder="请输入简介" class="layui-textarea">
                                @if(!empty($guide)) {{$guide->brief}} @endif
                            </textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">详细信息</label>
                        <div class="layui-input-block">
                             <textarea name="information" required lay-verify="required" placeholder="请输入详细信息" class="layui-textarea">
                                @if(!empty($guide)) {{$guide->information}} @endif
                            </textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">语言能力</label>
                        <div class="layui-input-block">
                            <input type="text" name="language" required  lay-verify="required" @if(!empty($guide)) value="{{$guide->language}}" @endif placeholder="请输入语言能力" autocomplete="off" class="layui-input">
                            
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">国家足迹</label>
                        <div class="layui-input-block">
                            <input type="text" name="footprint" required  lay-verify="required" @if(!empty($guide)) value="{{$guide->footprint}}" @endif placeholder="请输入国家足迹" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">擅长目的地</label>
                        <div class="layui-input-block">
                            <input type="text" name="destination" required  lay-verify="required" @if(!empty($guide)) value="{{$guide->destination}}" @endif placeholder="请输入擅长目的地" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">专属咨询状态</label>
                        <div class="layui-input-block">
                          <input type="radio" name="consultation" value="0" title="不开启"   @if(!empty($guide)) @if($guide->consultation==0) checked @endif  @endif  >
                          <input type="radio" name="consultation" value="1" title="开启"   @if(!empty($guide))  @if($guide->consultation==1) checked @endif  @else checked @endif>
                        </div>
                    </div>  

                    <div class="layui-form-item">
                        <label class="layui-form-label">行程规划状态</label>
                        <div class="layui-input-block">
                          <input type="radio" name="plan" value="0" title="不开启"   @if(!empty($guide)) @if($guide->plan==0) checked @endif  @endif  >
                          <input type="radio" name="plan" value="1" title="开启"   @if(!empty($guide))  @if($guide->plan==1) checked @endif  @else checked @endif>
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">目的地讲解状态</label>
                        <div class="layui-input-block">
                          <input type="radio" name="explain" value="0" title="不开启"   @if(!empty($guide)) @if($guide->explain==0) checked @endif  @endif  >
                          <input type="radio" name="explain" value="1" title="开启"   @if(!empty($guide))  @if($guide->explain==1) checked @endif  @else checked @endif>
                        </div>
                    </div>  

                    <div class="layui-form-item">
                        <label class="layui-form-label">保驾护航状态</label>
                        <div class="layui-input-block">
                          <input type="radio" name="escort" value="0" title="不开启"   @if(!empty($guide)) @if($guide->escort==0) checked @endif  @endif  >
                          <input type="radio" name="escort" value="1" title="开启"   @if(!empty($guide))  @if($guide->escort==1) checked @endif  @else checked @endif>
                        </div>
                    </div>  


                    <div class="layui-form-item">
                        <label class="layui-form-label">预约状态</label>
                        <div class="layui-input-block">
                          <input type="radio" name="appointment" value="0" title="不可预约"   @if(!empty($guide)) @if($guide->appointment==0) checked @endif  @endif  >
                          <input type="radio" name="appointment" value="1" title="可预约"   @if(!empty($guide))  @if($guide->appointment==1) checked @endif  @else checked @endif>
                        </div>
                    </div>  
                      
                    <div class="layui-form-item">
                        <label class="layui-form-label">状态</label>
                        <div class="layui-input-block">
                          <input type="radio" name="status" value="0" title="否"   @if(!empty($guide)) @if($guide->status==0) checked @endif  @endif  >
                          <input type="radio" name="status" value="1" title="是"   @if(!empty($guide))  @if($guide->status==1) checked @endif  @else checked @endif>
                        </div>
                    </div>
                      
                      @if(!empty($guide))
                        <input type="text" id="mold" hidden  value="edit" >
                        <input type="text" id="id" hidden value="{{$guide->id}}" >
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

                $("#img2").append('<img src="'+img+'" name="img[]"><input type="text" value="'+img+'" hidden name="img[]">')
                
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
            }
          }
        });

    //监听提交
    form.on('submit(formDemo)', function(data){
        
        var date=data.field;
        date.headimg=$("#img1").attr("src");
       
        if(date.typeid==""||date.gradeid==""||date.labelid==""||date.name==""||date.headimg==""||date.price==""||date.experience==""||date.brief==""||date.information==""||date.language==""||date.footprint==""||date.destination==""){
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
            url:"{{url('guide/detail')}}",
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