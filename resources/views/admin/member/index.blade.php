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
    <div id="wrapper"  style="margin-top:20px;" >
        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <div class="layui-form toolbar">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label w-auto">搜索：</label>
                                <div class="layui-input-inline mr0">
                                    <input name="keyword" class="layui-input" type="text" placeholder="输入关键字"/>
                                </div>
                            </div>
                            
                             <!-- <div class="layui-inline">
                                <select name="cut" lay-verify="">
                                  <option value="">请选择一个类型</option>
                                  <option value="1">会员标签</option>
                                  <option value="2">技师标签</option>
                                  <option value="3">评论标签</option>
                                </select>  
                            </div>    -->

                            <div class="layui-inline">
                                <button class="layui-btn icon-btn" lay-filter="formSubSearchRole" lay-submit>
                                    <i class="layui-icon">&#xe615;</i>搜索
                                </button>
                            </div>

                        </div>
                    </div>
                    
                   <table class="layui-hide" id="demo" lay-filter="test"></table>
                    
                    <div id="test1"></div>
     
     
                    <script type="text/html" id="barDemo">
                      <!-- <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a> -->
                      {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
                    </script>

                    {{--<script type="text/html" id="staDemo">--}}
                         {{--@verbatim--}}
                            {{--{{#  if(d.status ==1){ }}--}}
                                {{--<a class="layui-btn layui-btn-danger layui-btn-xs"  onclick="status({{d.id}})" lay-event="check">禁用</a>--}}
                            {{--{{#  } }}--}}
                            {{----}}
                            {{--{{#  if(d.status ==0){ }}--}}
                                {{--<a class="layui-btn layui-btn-xs" onclick="status({{d.id}})" lay-event="check">启用</a>--}}
                            {{--{{#  } }}--}}
                        {{--@endverbatim--}}
                    {{--</script>--}}
                    <script type="text/html" id="headimg">
                         @verbatim
                           <img src="{{d.user_img}}">
                        @endverbatim
                    </script>

                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

         </div>

    </div>
    
    <script src="{{asset('page/table/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/metisMenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
    <script src="{{asset('page/table/dist/js/sb-admin-2.js')}}"></script>

    <!-- <script src="{{asset('assets/libs/layui/layui.all.js')}}"></script> -->
    <script src="{{asset('assets/libs/layui/layui.js')}}"></script>

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
// wx_headimg
        var insTb = table.render({
            elem: '#demo'
            ,height: 800
            ,url: '{{URL("member/index")}}?type=select' //数据接口
            ,title: '会员表'
            ,page: true //开启分页
            ,toolbar: true //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
            ,totalRow: true //开启合计行
            ,limit : 10 //这里设置的是每页显示多少条
            ,cols: [[ //表头
              {type: 'checkbox', fixed: 'left'}
              ,{field: 'id', title: 'ID', width:80, sort: true }
              ,{field:'user_nickname',title: '微信昵称', width:100,align:'center'}
              ,{ title: '微信头像', width:100,align:'center', toolbar: '#headimg'}
              ,{field:'sex',title: '性别', width:100,align:'center',templet:function(d){
                if(d.sex==1){
                    return '<a>男</a>';
                }else{
                    return '<a>女</a>';
                }
              }}
              ,{field:'country',title: '国家', width:100,align:'center'}
              ,{field:'city',title: '城市', width:100,align:'center'}
              ,{field:'user_openid',title: 'openID', width:100,align:'center'}
              ,{field: 'created_at', title: '加入时间', width:180,align:'center',
                templet: function (d) {
                  return layui.util.toDateString(d.time * 1000, "yyyy-MM-dd HH:mm:ss")
                }
              }
              // ,{fixed: 'right',title:'操作', width: 165, align:'center', toolbar: '#barDemo'}
            ]]
          });

        element.init();
        //搜索
        form.on('submit(formSubSearchRole)', function (data) {
            insTb.reload({where: data.field}, 'data');
        });

        

        //监听行工具事件
        table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
            if(layEvent === 'detail'){
              layer.msg('查看操作');
            } else if(layEvent === 'del'){
                layer.alert('确定删除？', {
                      skin: 'layui-layer-molv' //样式类名  自定义样式
                     ,closeBtn: 1    // 是否显示关闭按钮
                      ,anim: 1 //动画类型
                      ,btn: ['确定','取消'] //按钮
                      ,icon: 6    // icon
                      ,yes:function(){
                         del(data);
                      }
                     ,btn2:function(){
                         layer.msg('已取消操作')
                     }});
            } else if(layEvent === 'edit'){
               layer.open({
                  title:"编辑",
                  type: 2, 
                  area: ['500px', '400px'],
                  content: '{{url("label/detail")}}?type=edit&id='+obj.data.id //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
                 
                }); 
            }
          });
  
    function del(data){
        if(data.length==undefined){
            //单行删除
            var id=data.id;
        }else{//多行删除
            var id=[];
            for(var i=0;i<data.length;i++){
                id.push(data[i].id);
            }
        }
        
        $.ajax({
            type:"post",
            datatype:"json",
            data:{'id':id,'type':'del'},
            url:"{{url('member/status')}}",
            success:function(res){
                console.log(res);
                if(res.code==1){
                    layer.msg(res.msg);
                 setTimeout(function(){
                    window.location.reload();
                 },1000);
                }else{
                    layer.msg(res.msg);
                }
            }
            
        });
        
        
        
    };

       //底部信息
      // var footerTpl = lay('#footer')[0].innerHTML;
      // lay('#footer').html(layui.laytpl(footerTpl).render({}))
      // .removeClass('layui-hide');

    });
    function status(id){
        $.ajax({
            data:{'id':id,'type':'edit'},
            type:'post',
            datatype:"json",
            url:"{{url('member/index')}}",
            success:function(res){
                if(res.code==1){
                    layer.msg(res.msg,{icon:6});
                    setTimeout(function(){
                       window.location.reload();
                    }, 1000);
                }else{
                    layer.msg(res.msg,{icon:5});
                }
            }
        })
    }
    </script>