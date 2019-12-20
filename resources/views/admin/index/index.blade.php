<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="./assets/images/favicon.ico" rel="icon">
    <title>后台开发框架</title>
    <link rel="stylesheet" href="{{asset('assets/libs/layui/css/layui.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/module/admin.css?v=314')}}"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style type="text/css">
    .ul_left_li li:hover {
        background: #2c3138
    }

    .this_li_nav_tyle {
        background-color: #2c3138;
        color: #fff;
        border-left: 1px solid #20a53a
    }

    .child-li {
        width: 100%;
        /*height: 54.5px;*/
        /*line-height: 54.5px;*/
        height: 55px !important;
        line-height: 55px !important;
        background: #fff
    }

    .selected-child-li {
        background: #e6e6e6;
        color: #666
    }

    #child-div {
        width: 46.5%;
        height: 85%;
        background: #fff;
        float: right;
        text-align: center;
    }

    .layui-header, .layui-tab-title {
        box-shadow: none !important;
    }

    .layui-tab-title {
        background-color: #f2f2f2 !important;
        /*height: 54.5px !important;*/
        /*line-height: 54.5px !important;*/
        height: 55px !important;
        line-height: 55px !important;
    }

    .layui-tab-title li {
        /*height: 54.5px !important;*/
        /*line-height: 54.5px !important;*/
        height: 55px !important;
        line-height: 55px !important;
    }

    .layui-tab-title .layui-icon {
        background-color: #f2f2f2 !important;
        /*height: 54.5px !important;*/
        /*line-height: 54.5px !important;*/
        height: 55px !important;
        line-height: 55px !important;
    }
    .layui-body .layui-icon {
        background-color: #f2f2f2 !important;
        height: 54.5px !important;
        line-height: 54.5px !important;
    }
    .layui-body{
        bottom: 0 !important;
    }
    .selected-child-li {
        background-color: #f2f2f2 !important;
    }

    .layui-side {
        box-shadow: none !important;
    }

    .this_li_nav_tyle {
        border-left: 3px solid #fff;
    }
    .layui-tab-title li .layui-tab-close{
        top: 2px !important;
        background-color: transparent !important;
    }
    .layui-logo img{
        border-radius: 50%;
    }
    .layui-nav *{
        font-size: 15px;
    }
    .layui-nav-item a{
        font-weight: bold;
    }
</style>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    <div class="layui-header">
        <div class="layui-logo">
            {{--<img src="{{asset('assets/images/logo.jpg')}}"/>--}}
            <img src="{{$user->headimg}}">
        </div>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item" lay-unselect>

                <a ew-event="flexible">菜单伸缩</a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="refresh" title="刷新">刷新页面</a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="message" title="消息">
                    系统消息
                </a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="note" title="便签">便签</a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="fullScreen" title="全屏">全屏</a>
            </li>
            <li class="layui-nav-item" lay-unselect lay-ignore>
                <a>
                    <img src="{{asset('assets/images/head.png')}}" class="layui-nav-img" style="width: 30px;height: 30px;">
                </a>
                <dl class="layui-nav-child">
                    <dd lay-unselect>
                        <a ew-href="page/template/user-info.html">个人中心</a>
                    </dd>
                    <dd lay-unselect>
                        <a ew-event="psw">修改密码</a>
                    </dd>
                    <hr>
                    <dd lay-unselect>
                        <a ew-event="logout" data-url="./loginLayout">退出</a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="theme" title="主题"><i class="layui-icon layui-icon-more-vertical"></i></a>
            </li>
        </ul>
    </div>

    <!-- 侧边栏 -->
    <div class="layui-side">
        <div class="layui-side-scroll" style="float:left;background: #3c444d;">


            <ul class="layui-nav layui-nav-tree arrow2 ul_left_li" lay-filter="admin-side-nav" lay-accordion="true">
                @foreach ($admin_menu as $key => $data)
                    @if(!empty($data['ceshi']))
                        <li class="layui-nav-item li_nav" onclick="menu({{$data['id']}})">
                            <a href="#"><i class="layui-icon layui-icon-{{$data['icon']}}"></i>&emsp;<cite>{{$data['menuname']}}</cite></a>
                        </li>
                    @endif

                @endforeach
            </ul>
        </div>




                <div id="child-div">
                    <ul class="child-ul">
                        @foreach ($admin_menu as $key => $data)
                            @if(!empty($data['items']) )
                                @foreach ($data['items'] as $keys => $datas)
                                    @if(!empty($datas['ceshi']))
                                        <a ew-href="{{url($datas['url'])}}" data-id="{{$data['id']}}" class="tab">
                                            <li class="child-li">{{$datas['menuname']}}</li>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>

    </div>
    <!-- 主体部分 -->
    <div class="layui-body"></div>
</div>
<!-- 加载动画 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<!-- js部分 -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="{{asset('assets/libs/layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/common.js?v=314')}}"></script>
<script>
    layui.use(['index'], function () {
        var $ = layui.jquery;
        var index = layui.index;

        // 默认加载主页
        index.loadHome({
            menuPath: "{{URL('index/default')}}",
            menuName: '<i class="layui-icon layui-icon-home"></i>'
        });

    });
</script>

<script type="text/javascript">
    /*菜单选中样式*/
    function selected(className, styleName) {
        $(className).each(function () {
            $(this).click(function () {
                $(className).removeClass(styleName);
                $(this).addClass(styleName);
            })

        })
    }
    selected('.li_nav', 'this_li_nav_tyle');
    selected('.child-li', 'selected-child-li');
    +function(){
        $('.tab').hide();
    }();
    const menu = (id) => {
        $('.tab').hide();
        var id = id;
        $('.tab').each(function(){
            if($(this).attr("data-id") == id){
                $(this).show();
            }
        })
    }

</script>
</body>
</html>