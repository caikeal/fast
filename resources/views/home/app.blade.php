<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    @yield('head')
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <!-- UC -->
    <meta name="full-screen" content="yes">
    <meta name="browsermode" content="application">
    <!-- QQ: -->
    <meta name="x5-orientation" content="portrait">
    <meta name="x5-fullscreen" content="true">
    <meta name="x5-page-mode" content="app">

    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="apple-touch-icon-precomposed" href="{{env('APP_URL')}}/images/114.png" sizes="114x114" />
    <link rel="shortcut icon" href="{{env('APP_URL')}}/images/32.ico" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="alternate icon" type="image/png" href="../../assets/i/favicon.png"> -->
    <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.5.2/css/amazeui.min.css"/>
    <link rel="stylesheet" href="{{env('APP_URL')}}/css/home/main.css">
    @yield('moreCss')
</head>
<body>
<!-- 页头 -->
<header data-am-widget="header" class="am-header am-header-default">
    @yield('back')
    <h1 class="am-header-title">
        FAST系统
    </h1>
</header>
<!-- 内容 -->
@yield('content')
<!-- 导航栏start -->
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default  am-no-layout" id="">
    <ul class="am-navbar-nav am-cf am-avg-sm-4">
        <li>
            <a href="{{url('index')}}">
                <span class="am-icon-home"></span>
                <span class="am-navbar-label">首页</span>
            </a>
        </li>
        {{--<li class="am-dropdown am-dropdown-up" data-am-dropdown>--}}
            {{--<a class="am-dropdown-toggle" data-am-dropdown-toggle>--}}
                {{--<span class="am-icon-bars"></span>--}}
                {{--<span class="am-navbar-label">答疑解惑</span>--}}
            {{--</a>--}}
            {{--<ul class="am-dropdown-content" style="text-align: center;padding: 0;">--}}
                {{--<div>--}}
                    {{--<a href="#" style="line-height: 49px;color: #000;">我的问题</a>--}}
                {{--</div>--}}
                {{--<div class="am-divider"></div>--}}
                {{--<div>--}}
                    {{--<a href="#" style="line-height: 49px;color: #000;">最新问题</a>--}}
                {{--</div>--}}
                {{--<div class="am-divider"></div>--}}
                {{--<div>--}}
                    {{--<a href="#" style="line-height: 49px;color: #000;">编辑问题</a>--}}
                {{--</div>--}}
                {{--<div class="am-divider"></div>--}}
                {{--<div>--}}
                    {{--<a href="#" style="line-height: 49px;color: #000;">查找问题</a>--}}
                {{--</div>--}}
            {{--</ul>--}}
        {{--</li>--}}
        <li>
            <a href="#">
                <span class="am-icon-commenting"></span>
                <span class="am-navbar-label">联系我们</span>
            </a>
        </li>
        <li>
            <a href="{{url('my')}}">
                <span class="am-icon-user"></span>
                <span class="am-navbar-label">我的</span>
            </a>
        </li>
        {{--<li class="am-dropdown am-dropdown-up" data-am-dropdown>--}}
            {{--<a class="am-dropdown-toggle" data-am-dropdown-toggle>--}}
                {{--<span class="am-icon-bars"></span>--}}
                {{--<span class="am-navbar-label ">我的</span>--}}
            {{--</a>--}}
            {{--<ul class="am-dropdown-content" style="text-align: center;padding: 0;">--}}
                {{--<div>--}}
                    {{--<a href="{{ url('my') }}" style="line-height: 49px;color: #000;">基本信息</a>--}}
                {{--</div>--}}
                {{--<div class="am-divider"></div>--}}
                {{--<div>--}}
                    {{--<a href="{{ url('rebinding') }}" style="line-height: 49px;color: #000;">重新绑定手机</a>--}}
                {{--</div>--}}
                {{--<div class="am-divider"></div>--}}
                {{--<div>--}}
                    {{--<a href="{{ url('reset') }}" style="line-height: 49px;color: #000;">修改密码</a>--}}
                {{--</div>--}}
            {{--</ul>--}}
        {{--</li>--}}
    </ul>
</div>
<!-- 导航栏end -->
<!--[if lt IE 9]>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<!--<![endif]-->
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.min.js"></script>
<!-- 修改bottom-bar -->
<script type="text/javascript">
    var buttomBar = function () {
        var dropUpWidth = $(".am-dropdown.am-dropdown-up").width();
        var middleWidth = dropUpWidth/2 - 8;

        style = "<style type='text/css' id='bottom-bar'>.am-dropdown-content:after, .am-dropdown-content:before{left: "+ middleWidth +"px;}.am-dropdown-flip .am-dropdown-content:after, .am-dropdown-flip .am-dropdown-content:before{right: "+ middleWidth +"px;}</style>";
        var style=$("head").append(style);
    };

    $(document).ready(function () {
        $("#bottom-bar").remove();
        buttomBar();
    });

    $(window).resize(function () {
        $("#bottom-bar").remove();
        buttomBar();
    });
</script>
@yield('moreScript')
</body>
</html>