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
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                </script>

                <script type="text/html" id="staDemo">
                    @verbatim
                    {{#  if(d.status ==1){ }}
                    <a class="layui-btn layui-btn-danger layui-btn-xs"  onclick="status({{d.id}})" lay-event="check">禁用</a>
                    {{#  } }}

                    {{#  if(d.status ==0){ }}
                    <a class="layui-btn layui-btn-xs" onclick="status({{d.id}})" lay-event="check">启用</a>
                    {{#  } }}
                    @endverbatim
                </script>
                <script type="text/html" id="typeDemo">
                    @verbatim
                    {{#  if(d.type ==1){ }}
                    <a >会员标签</a>
                    {{#  } }}

                    {{#  if(d.type ==2){ }}
                    <a >导游标签</a>
                    {{#  } }}

                    {{#  if(d.type ==3){ }}
                    <a >评论标签</a>
                    {{#  } }}
                    @endverbatim
                </script>

                <script type="text/html" id="good_image">
                    @verbatim
                    <img src="../{{d.good_image}}">
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

        var insTb = table.render({
            elem: '#demo'
            ,height: 800
            ,url: '{{URL("freight/index")}}?type=select' //数据接口
            ,title: '标签表'
            ,page: true //开启分页
            ,toolbar: 'default' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
            ,totalRow: true //开启合计行
            ,limit : 5 //这里设置的是每页显示多少条
            ,cols: [[ //表头
                {type: 'checkbox', fixed: 'left'}

                ,{field: 'id', title: 'ID',  sort: true }
                ,{field:'regions',title: '多地区',align:'center'}
                ,{field:'price',title: '运费价格',align:'center'}
                ,{field:'sort',title: '排序', align:'center'}

                ,{fixed: 'right',title:'操作',  align:'center', toolbar: '#barDemo'}


            ]]
        });

        element.init();
        //搜索
        form.on('submit(formSubSearchRole)', function (data) {
            insTb.reload({where: data.field}, 'data');
        });

        //监听头工具栏事件
        table.on('toolbar(test)', function(obj){

            var checkStatus = table.checkStatus(obj.config.id)
                ,data = checkStatus.data; //获取选中的数据
            switch(obj.event){
                case 'add':
                    layer.open({
                        title:"添加",
                        type: 2,
                        area: ['90%', '90%'],
                        content: '{{url("freight/detail")}}' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
                    });
                    //layer.msg('添加');
                    break;
                case 'update':
                    if(data.length === 0){
                        layer.msg('请选择一行');
                    } else if(data.length > 1){
                        layer.msg('只能同时编辑一个');
                    } else {

                        layer.open({
                            title:"编辑",
                            type: 2,
                            area: ['80%', '80%'],
                            content: '{{url("freight/detail")}}?type=edit&id='+data[0].id

                        });
                    }
                    break;
                case 'delete':
                    if(data.length === 0){
                        layer.msg('请选择一行');
                    } else {
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

                    }
                    break;
            };
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
                    area: ['80%', '80%'],
                    content: '{{url("freight/detail")}}?type=edit&id='+obj.data.id //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']

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
                url:"{{url('freight/status')}}",
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
            url:"{{url('freight/index')}}",
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