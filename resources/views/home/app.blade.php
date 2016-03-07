<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>FAST</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <li>
            <a href="#">
                <span class="am-icon-question"></span>
                <span class="am-navbar-label">答疑解惑</span>
            </a>
        </li>
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
    </ul>
</div>
<!-- 导航栏end -->
<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<!--<![endif]-->
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.min.js"></script>
@yield('moreScript')
</body>
</html>