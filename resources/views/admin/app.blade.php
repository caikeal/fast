<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fast Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fast Fast And Fast">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="apple-touch-icon-precomposed" href="{{env('APP_URL')}}/images/114.png" sizes="114x114"/>
    <link rel="shortcut icon" href="{{env('APP_URL')}}/images/32.ico" type="image/x-icon"/>
    <meta name="author" content="Keal">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- select 2 -->
    <link href="http://cdn.bootcss.com/select2/4.0.3/css/select2.min.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- select2 Bootstrap core CSS -->
    <link href="http://cdn.bootcss.com/select2-bootstrap-theme/0.1.0-beta.7/select2-bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- ionicons -->
    <link href="//cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    {{--<!-- Morris -->--}}
    {{--<link href="//cdn.bootcss.com/morris.js/0.5.1/morris.css" rel="stylesheet">--}}

    <!-- Animate -->
    <link href="//cdn.bootcss.com/animate.css/3.5.1/animate.min.css" rel="stylesheet">

    <!-- Owl Carousel -->
    <link href="//cdn.bootcss.com/owl-carousel/1.32/owl.carousel.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/owl-carousel/1.32/owl.theme.css" rel="stylesheet">

    <!-- Simplify -->
    <link href="{{env('APP_URL')}}/css/admin/simplify.min.css" rel="stylesheet">
    {{--<link href="http://7xqxb2.com2.z0.glb.qiniucdn.com/simplify.min.css" rel="stylesheet">--}}

    @yield('moreCss')
</head>

<body class="overflow-hidden">
<div class="wrapper preload">
    <header class="top-nav" id="top-news">
        <div class="top-nav-inner">
            <div class="nav-header">
                <button type="button" class="navbar-toggle pull-left sidebar-toggle" id="sidebarToggleSM">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <ul class="nav-notification pull-right">
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></a>
                        <span class="badge badge-danger bounceIn">10</span>
                        <ul class="dropdown-menu dropdown-sm pull-right user-dropdown">
                            <li class="user-avatar">
                                <img src="{{ env('APP_URL') }}{{ \Auth::guard('admin')->user()->poster }}" alt="图片" class="img-circle">
                                <div class="user-content">
                                    <h5 class="no-m-bottom">{{ \Auth::guard('admin')->user()->name }}</h5>
                                    <div class="m-top-xs">
                                        <a href="#" class="m-right-sm" data-toggle="modal" data-target="#completeDetail">个人信息</a>
                                        <a href="{{ url('admin/logout') }}">退出</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#resetOwnPwd">
                                    修改密码
                                    {{--<span class="badge badge-danger bounceIn animation-delay2 pull-right">1</span>--}}
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    提醒
                                    <span class="badge badge-purple bounceIn animation-delay1 pull-right" v-cloak>@{{ total }}</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);">设置</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <a href="{{url('admin/index')}}" class="brand">
                    <i class="fa fa-database"></i><span class="brand-name">Fast For FESCO</span>
                </a>
            </div>
            <div class="nav-container">
                <button type="button" class="navbar-toggle pull-left sidebar-toggle" id="sidebarToggleLG">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="pull-right m-right-sm">
                    <div class="user-block hidden-xs">
                        <a href="#" id="userToggle" data-toggle="dropdown">
                            <img src="{{env('APP_URL')}}{{ \Auth::guard('admin')->user()->poster }}" alt=""
                                 class="img-circle inline-block user-profile-pic">

                            <div class="user-detail inline-block">
                                {{ \Auth::guard('admin')->user()->name }}
                                <i class="fa fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="panel border dropdown-menu user-panel">
                            <div class="panel-body paddingTB-sm">
                                <ul>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#completeDetail">
                                            <i class="fa fa-edit fa-lg"></i><span class="m-left-xs">个人信息</span>
                                        </a>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#resetOwnPwd">
                                            <i class="fa fa-inbox fa-lg"></i><span class="m-left-xs">修改密码</span>
                                        </a>
                                    <li>
                                        <a href="{{url('admin/logout')}}">
                                            <i class="fa fa-power-off fa-lg"></i><span class="m-left-xs">退出</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <ul class="nav-notification">
                        <li v-cloak>
                            <a href="#" data-toggle="dropdown"><i class="fa fa-bell fa-lg"></i></a>
                            <span class="badge badge-info bounceIn animation-delay5 active">@{{ total }}</span>
                            <ul class="dropdown-menu notification dropdown-3 pull-right">
                                <li><a href="#">您有 @{{ total }} 个新消息</a></li>
                                <li v-for="newItem in news">
                                    <a href="#">
                                        <span class="notification-icon bg-warning">
                                            <i class="fa fa-warning"></i>
                                        </span>
                                        <span class="m-left-xs small-news-info" title="@{{ newItem.content }}">@{{ newItem.content }}</span>
                                        <span class="time text-muted small-news-time" title="@{{ newItem.from_now }}">@{{ newItem.from_now }}</span>
                                    </a>
                                </li>
                                <li><a href="#">查看所有新消息</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div><!-- ./top-nav-inner -->
    </header>
    <aside class="sidebar-menu fixed">
        <div class="sidebar-inner scrollable-sidebar">
            <div class="main-menu">
                <ul class="accordion">
                    <li class="menu-header">
                        Main Menu
                    </li>
                    <li class="bg-palette1 lock-place1 active">
                        <a href="{{url('admin/index')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-home fa-lg"></i></span>
                                <span class="text m-left-sm">首&nbsp;&nbsp;页</span>
                            </span>
                            <span class="menu-content-hover block">
                                首&nbsp;&nbsp;页
                            </span>
                        </a>
                    </li>
                    {{--<li class="openable bg-palette3">--}}
                    {{--<a href="#">--}}
                    {{--<span class="menu-content block">--}}
                    {{--<span class="menu-icon"><i class="block fa fa-list fa-lg"></i></span>--}}
                    {{--<span class="text m-left-sm">Form Elements</span>--}}
                    {{--<span class="submenu-icon"></span>--}}
                    {{--</span>--}}
                    {{--<span class="menu-content-hover block">--}}
                    {{--Form--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu bg-palette4">--}}
                    {{--<li><a href="form_element.html"><span class="submenu-label">Form Element</span></a></li>--}}
                    {{--<li><a href="form_validation.html"><span class="submenu-label">Form Validation</span></a></li>--}}
                    {{--<li><a href="form_wizard.html"><span class="submenu-label">Form Wizard</span></a></li>--}}
                    {{--<li><a href="dropzone.html"><span class="submenu-label">Dropzone</span></a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="openable bg-palette4">--}}
                    {{--<a href="#">--}}
                    {{--<span class="menu-content block">--}}
                    {{--<span class="menu-icon"><i class="block fa fa-tags fa-lg"></i></span>--}}
                    {{--<span class="text m-left-sm">UI Elements</span>--}}
                    {{--<span class="submenu-icon"></span>--}}
                    {{--</span>--}}
                    {{--<span class="menu-content-hover block">--}}
                    {{--UI Kits--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu">--}}
                    {{--<li><a href="ui_element.html"><span class="submenu-label">Basic Elements</span></a></li>--}}
                    {{--<li><a href="button.html"><span class="submenu-label">Button & Icons</span></a></li>--}}
                    {{--<li class="openable">--}}
                    {{--<a href="#">--}}
                    {{--<small class="badge badge-success badge-square bounceIn animation-delay2 m-left-xs pull-right">2</small>--}}
                    {{--<span class="submenu-label">Tables</span>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu third-level">--}}
                    {{--<li><a href="static_table.html"><span class="submenu-label">Static Table</span></a></li>--}}
                    {{--<li><a href="datatable.html"><span class="submenu-label">DataTables</span></a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li><a href="widget.html"><span class="submenu-label">Widget</span></a></li>--}}
                    {{--<li><a href="tab.html"><span class="submenu-label">Tab</span></a></li>--}}
                    {{--<li><a href="calendar.html"><span class="submenu-label">Calendar</span></a></li>--}}
                    {{--<li><a href="treeview.html"><span class="submenu-label">Treeview</span></a></li>--}}
                    {{--<li><a href="nestable_list.html"><span class="submenu-label">Nestable Lists</span></a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="bg-palette1">--}}
                    {{--<a href="inbox.html">--}}
                    {{--<span class="menu-content block">--}}
                    {{--<span class="menu-icon"><i class="block fa fa-envelope fa-lg"></i></span>--}}
                    {{--<span class="text m-left-sm">Inboxs</span>--}}
                    {{--<small class="badge badge-danger badge-square bounceIn animation-delay5 m-left-xs">5</small>--}}
                    {{--</span>--}}
                    {{--<span class="menu-content-hover block">--}}
                    {{--Inboxs--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    @if(\Auth::guard('admin')->user()->can('salary'))
                    <li class="bg-palette2 lock-place2">
                        <a href="{{url('admin/timeline')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-jpy fa-lg"></i></span>
                                <span class="text m-left-sm">工资发放</span>
                                {{--<small class="badge badge-warning badge-square bounceIn animation-delay6 m-left-xs pull-right">7</small>--}}
                            </span>
                            <span class="menu-content-hover block">
                                工资发放
                            </span>
                        </a>
                    </li>
                    <li class="bg-palette3 lock-place3">
                        <a href="{{url('admin/insurance')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-medkit fa-lg"></i></span>
                                <span class="text m-left-sm">社保发放</span>
                            </span>
                        <span class="menu-content-hover block">
                        社保发放
                        </span>
                        </a>
                    </li>
                    @endif
                    <li class="bg-palette4 lock-place4">
                        <a href="{{url('admin/history')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-clock-o fa-lg"></i></span>
                                <span class="text m-left-sm">历史查询</span>
                            </span>
                            <span class="menu-content-hover block">
                                历史查询
                            </span>
                        </a>
                    </li>
                    <li class="bg-palette1 lock-place5">
                        <a href="#">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-bar-chart fa-lg"></i></span>
                                <span class="text m-left-sm">数据统计</span>
                            </span>
                            <span class="menu-content-hover block">
                                数据统计
                            </span>
                        </a>
                    </li>
                    <li class="bg-palette2 lock-place6">
                        <a href="#">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-question-circle fa-lg"></i></span>
                                <span class="text m-left-sm">解惑答疑</span>
                            </span>
                            <span class="menu-content-hover block">
                                解惑答疑
                            </span>
                        </a>
                    </li>
                    @if(\Auth::guard('admin')->user()->can('task'))
                    <li class="bg-palette3 lock-place7">
                        <a href="{{url('admin/task')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-link fa-lg"></i></span>
                                <span class="text m-left-sm">企业管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                企业管理
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(\Auth::guard('admin')->user()->can('employ'))
                    <li class="bg-palette4 lock-place8">
                        <a href="{{url('admin/employ')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-user fa-lg"></i></span>
                                <span class="text m-left-sm">人员管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                人员管理
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(\Auth::guard('admin')->user()->can('super'))
                    <li class="bg-palette1 lock-place9">
                        <a href="{{url('admin/super')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-user-md fa-lg"></i></span>
                                <span class="text m-left-sm">超级管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                超级管理
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(\Auth::guard('admin')->user()->can('compensation'))
                    <li class="bg-palette3 lock-place10">
                        <a href="{{url('admin/compensation')}}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-lock fa-lg"></i></span>
                                <span class="text m-left-sm">理赔上传</span>
                                {{--<small class="badge badge-warning badge-square bounceIn animation-delay6 m-left-xs pull-right">7</small>--}}
                            </span>
                            <span class="menu-content-hover block">
                                理赔上传
                            </span>
                        </a>
                    </li>
                    @endif
                    {{--<li class="menu-header">--}}
                        {{--Others--}}
                    {{--</li>--}}
                    {{--<li class="openable bg-palette3">--}}
                    {{--<a href="#">--}}
                    {{--<span class="menu-content block">--}}
                    {{--<span class="menu-icon"><i class="block fa fa-gift fa-lg"></i></span>--}}
                    {{--<span class="text m-left-sm">Extra Pages</span>--}}
                    {{--<span class="submenu-icon"></span>--}}
                    {{--</span>--}}
                    {{--<span class="menu-content-hover block">--}}
                    {{--Pages--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu">--}}
                    {{--<li><a href="signin.html"><span class="submenu-label">Sign in</span></a></li>--}}
                    {{--<li><a href="signup.html"><span class="submenu-label">Sign Up</span></a></li>--}}
                    {{--<li><a href="lockscreen.html"><span class="submenu-label">Lock Screen</span></a></li>--}}
                    {{--<li><a href="profile.html"><span class="submenu-label">Profile</span></a></li>--}}
                    {{--<li><a href="gallery.html"><span class="submenu-label">Gallery</span></a></li>--}}
                    {{--<li><a href="blog.html"><span class="submenu-label">Blog</span></a></li>--}}
                    {{--<li><a href="single_post.html"><span class="submenu-label">Single Post</span></a></li>--}}
                    {{--<li><a href="pricing.html"><span class="submenu-label">Pricing</span></a></li>--}}
                    {{--<li><a href="invoice.html"><span class="submenu-label">Invoice</span></a></li>--}}
                    {{--<li><a href="error404.html"><span class="submenu-label">Error404</span></a></li>--}}
                    {{--<li><a href="blank.html"><span class="submenu-label">Blank</span></a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="openable bg-palette4">--}}
                    {{--<a href="#">--}}
                    {{--<span class="menu-content block">--}}
                    {{--<span class="menu-icon"><i class="block fa fa-list fa-lg"></i></span>--}}
                    {{--<span class="text m-left-sm">Menu Level</span>--}}
                    {{--<span class="submenu-icon"></span>--}}
                    {{--</span>--}}
                    {{--<span class="menu-content-hover block">--}}
                    {{--Menu--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu">--}}
                    {{--<li class="openable">--}}
                    {{--<a href="signin.html">--}}
                    {{--<span class="submenu-label">menu 2.1</span>--}}
                    {{--<small class="badge badge-success badge-square bounceIn animation-delay2 m-left-xs pull-right">3</small>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu third-level">--}}
                    {{--<li><a href="#"><span class="submenu-label">menu 3.1</span></a></li>--}}
                    {{--<li><a href="#"><span class="submenu-label">menu 3.2</span></a></li>--}}
                    {{--<li class="openable">--}}
                    {{--<a href="#">--}}
                    {{--<span class="submenu-label">menu 3.3</span>--}}
                    {{--<small class="badge badge-danger badge-square bounceIn animation-delay2 m-left-xs pull-right">2</small>--}}
                    {{--</a>--}}
                    {{--<ul class="submenu fourth-level">--}}
                    {{--<li><a href="#"><span class="submenu-label">menu 4.1</span></a></li>--}}
                    {{--<li><a href="#"><span class="submenu-label">menu 4.2</span></a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li><a href="#"><span class="submenu-label">menu 2.2</span></a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                </ul>
            </div>
            <div class="sidebar-fix-bottom clearfix">
                <a href="{{url('admin/logout')}}" class="pull-right font-18"><i class="ion-log-out" title="退出"></i></a>
            </div>
        </div><!-- sidebar-inner -->
    </aside>

    <div class="main-container">
        @yield('content')
    </div><!-- /main-container -->

    <footer class="footer">
        <span class="footer-brand">
            <strong class="text-danger">Fast</strong> For FESCO
        </span>

        <p class="no-margin">
            &copy; 2016-2017 <strong>FESCO</strong>. ALL Rights Reserved.
        </p>
    </footer>
</div><!-- /wrapper -->

@yield('addition')
<a href="#" class="scroll-to-top hidden-print"><i class="fa fa-chevron-up fa-lg"></i></a>

<div class="nav-ctrl-cover" id="managers">
    {{--complete-detail--}}
    <div class="modal fade" id="completeDetail" tabindex="-1" role="dialog" aria-labelledby="completeDetail" v-cloak>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="completeDetailLabel">个人资料</h4>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <h3 class="inline-block">欢迎您！</h3>
                                <span class="user-name">@{{ managerInfo['name'] }}</span>,
                                <span class="role-name">@{{ managerInfo['role'] }}</span>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error':managerErrors.phone.isInvalid}">
                                        <label for="phone" class="col-sm-3 control-label">电话:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="phone" v-model="managerInfo['phone']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" :style="{'display':managerErrors.phone.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.phone.validInfo }}</p>
                                    </div>

                                    <div class="form-group" :class="{'has-error':managerErrors.email.isInvalid}">
                                        <label for="email" class="col-sm-3 control-label">邮箱:</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" id="email" v-model="managerInfo['email']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" style="{padding-left: 15px;}" :style="{'display':managerErrors.email.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.email.validInfo }}</p>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <p class="text-danger">请完善个人信息，以便让用户能及时与您联系</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="text-align: center;">
                    <button type="button" class="btn btn-primary inline-block" v-disabled="managerInfoCompleted" style="width: 50%;" @click="updateInfo">确认修改</button>
                </div>
            </div>
        </div>
    </div>

    {{--reset-own-pwd--}}
    <div class="modal fade" id="resetOwnPwd" tabindex="-1" role="dialog" aria-labelledby="resetOwnPwd" v-cloak>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="completeDetailLabel">修改密码</h4>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error':managerErrors.oldPwd.isInvalid}">
                                        <label for="old_pwd" class="col-sm-3 control-label">原密码:</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="old_pwd" v-model="pwdInfo['oldPwd']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" :style="{'display':managerErrors.oldPwd.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.oldPwd.validInfo }}</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-2 text-center">
                                            <p class="text-danger">密码由6-16位字母，数字组成，区分大小写</p>
                                        </div>
                                    </div>

                                    <div class="form-group" :class="{'has-error':managerErrors.pwd.isInvalid}">
                                        <label for="pwd" class="col-sm-3 control-label">新密码:</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="pwd" v-model="pwdInfo['pwd']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" style="{padding-left: 15px;}" :style="{'display':managerErrors.pwd.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.pwd.validInfo }}</p>
                                    </div>

                                    <div class="form-group" :class="{'has-error':managerErrors.pwdConfirmation.isInvalid}">
                                        <label for="pwd_confirmation" class="col-sm-3 control-label">确认密码:</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="pwd_confirmation" v-model="pwdInfo['pwdConfirmation']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" style="{padding-left: 15px;}" :style="{'display':managerErrors.pwdConfirmation.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.pwdConfirmation.validInfo }}</p>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer" style="text-align: center;">
                    <button type="button" class="btn btn-primary inline-block" v-disabled="pwdInfoCompleted" style="width: 50%;" @click="resetOwnPwd">确认修改</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Widget Confirmation -->
{{--<div class="custom-popup delete-widget-popup delete-confirmation-popup" id="deleteWidgetConfirm">--}}
{{--<div class="popup-header text-center">--}}
{{--<span class="fa-stack fa-4x">--}}
{{--<i class="fa fa-circle fa-stack-2x"></i>--}}
{{--<i class="fa fa-lock fa-stack-1x fa-inverse"></i>--}}
{{--</span>--}}
{{--</div>--}}
{{--<div class="popup-body text-center">--}}
{{--<h5>Are you sure to delete this widget?</h5>--}}
{{--<strong class="block m-top-xs"><i class="fa fa-exclamation-circle m-right-xs text-danger"></i>This action cannot be undone</strong>--}}

{{--<div class="text-center m-top-lg">--}}
{{--<a class="btn btn-success m-right-sm remove-widget-btn">Delete</a>--}}
{{--<a class="btn btn-default deleteWidgetConfirm_close">Cancel</a>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}


<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- Jquery -->
<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script src="http://cdn.bootcss.com/select2/4.0.3/js/select2.min.js"></script>
<script src="http://cdn.bootcss.com/select2/4.0.3/js/i18n/en.js"></script>
<script src="http://cdn.bootcss.com/select2/4.0.3/js/i18n/zh-CN.js"></script>

<!-- Flot -->
{{--<script src="//cdn.bootcss.com/flot/0.8.3/jquery.flot.min.js"></script>--}}

<!-- Slimscroll -->
<script src="//cdn.bootcss.com/jQuery-slimScroll/1.3.7/jquery.slimscroll.min.js"></script>

<!-- Morris -->
{{--<script src="//cdn.bootcss.com/raphael/2.1.4/raphael-min.js"></script>--}}
{{--<script src="//cdn.bootcss.com/morris.js/0.5.1/morris.min.js"></script>--}}

<!-- Datepicker -->
{{--<script src='{{env('APP_URL')}}/js/uncompressed/datepicker.js'></script>--}}

<!-- Sparkline -->
<script src="//cdn.bootcss.com/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>

<!-- Skycons -->
<script src="//cdn.bootcss.com/skycons/1396634940/skycons.min.js"></script>

<!-- Popup Overlay -->
<script src='{{env('APP_URL')}}/js/jquery.popupoverlay.min.js'></script>

<!-- Easy Pie Chart -->
{{--<script src="//cdn.bootcss.com/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>--}}

<!-- Sortable -->
<script src="//cdn.bootcss.com/Sortable/1.4.2/Sortable.min.js"></script>

<!-- Owl Carousel -->
<script src="//cdn.bootcss.com/owl-carousel/1.32/owl.carousel.min.js"></script>

<!-- Modernizr -->
<script src="//cdn.bootcss.com/modernizr/2010.07.06dev/modernizr.min.js"></script>

<!-- Simplify -->
<script src="{{env('APP_URL')}}/js/admin/simplify.js"></script>
<script src="{{env('APP_URL')}}/js/admin/simplify_dashboard.js"></script>

<!-- vue -->
<script src="//cdn.bootcss.com/vue/1.0.24/vue.js"></script>
@yield('moreScript')

{{-- 修改个人信息 --}}
<script>
    //绑定按钮锁定
    Vue.directive('disabled', {
        update: function (newValue, oldValue) {
            // 值更新时的工作
            // 也会以初始值为参数调用一次
            if (!newValue){
                this.el.setAttribute('disabled', 'disabled');
            }else{
                this.el.removeAttribute('disabled');
            }
        }
    });
    new Vue({
        el: '#managers',
        data: {
            managerInfo: {
                id: '{{ \Auth::guard('admin')->user()->id }}',
                name: '{{ \Auth::guard('admin')->user()->name }}',
                email: '{{ \Auth::guard('admin')->user()->email }}',
                phone: '{{ \Auth::guard('admin')->user()->phone }}',
                role: '{{ \Auth::guard('admin')->user()->roles()->first()->label }}',
                is_first: '{{ \Auth::guard('admin')->user()->is_first }}',
            },
            pwdInfo: {
                oldPwd: '',
                pwd: '',
                pwdConfirmation: ''
            },
            managerErrors: {
                phone: {
                    isInvalid: 0,
                    validInfo: ''
                },
                email: {
                    isInvalid: 0,
                    validInfo: ''
                },
                oldPwd: {
                    isInvalid: 0,
                    validInfo: ''
                },
                pwd: {
                    isInvalid: 0,
                    validInfo: ''
                },
                pwdConfirmation: {
                    isInvalid: 0,
                    validInfo: ''
                }
            },
            managerInfoCompleted: 0,
            pwdInfoCompleted: 0
        },
        computed: {
            managerInfoCompleted: function () {
                if (this.managerInfo.phone && this.managerInfo.email) {
                    return 1;
                }else {
                    return 0;
                }
            },
            pwdInfoCompleted: function () {
                if (this.pwdInfo.oldPwd && this.pwdInfo.pwd && this.pwdInfo.pwdConfirmation && this.pwdInfo.pwd==this.pwdInfo.pwdConfirmation) {
                    return 1;
                }else {
                    return 0;
                }
            }
        },
        ready: function () {
            if (this.managerInfo.is_first === '0'){
                $("#completeDetail").modal("show");
            }else{
                $("#completeDetail").modal("hide");
            }
        },
        methods: {
            updateInfo: function () {
                var _this=this;
                _this.managerErrors.phone.isInvalid = 0;
                _this.managerErrors.email.isInvalid = 0;
                _this.managerErrors.phone.validInfo = '';
                _this.managerErrors.email.validInfo = '';
                if(!_this.managerInfo.phone || _this.managerInfo.phone==''){
                    _this.managerErrors.phone.validInfo = '联系电话必填！';
                    _this.managerErrors.phone.isInvalid = 1;
                    return false;
                }

                if(_this.managerInfo.phone.length<6){
                    _this.managerErrors.phone.validInfo = '电话位数错误！';
                    _this.managerErrors.phone.isInvalid = 1;
                    return false;
                }

                if (isNaN(_this.managerInfo.phone)){
                    _this.managerErrors.phone.validInfo = '电话格式错误！';
                    _this.managerErrors.phone.isInvalid = 1;
                    return false;
                }

                if(!_this.managerInfo.email.match(/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/)){
                    _this.managerErrors.email.validInfo = '登录账户必为邮箱！';
                    _this.managerErrors.email.isInvalid = 1;
                    return false;
                }
                var url = "{{ url('admin/manager') }}"+"/"+_this.managerInfo.id+"?user_id="+_this.managerInfo.id;
                $.ajax({
                    url:url,
                    dataType:'json',
                    headers:{
                        'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                    },
                    timeout:60000,
                    data: {
                        email: _this.managerInfo.email,
                        phone: _this.managerInfo.phone,
                        _method:'PUT'
                    },
                    type:'POST'
                }).done(function (data) {
                    if(data.ret_num === 0){
                        $("#completeDetail").modal("hide");
                        if (data.reLogin == 1){
                            alert("保存成功！请重新登录！");
                            window.location.href = data.reUrl;
                        }else{
                            alert(data.ret_msg);
                        }
                    }else{
                        _this.managerErrors.phone.validInfo = data.ret_msg;
                        _this.managerErrors.phone.isInvalid = 1;
                    }
                }).fail(function (data) {
                    var errs=JSON.parse(data.responseText);
                    if(errs.email){
                        _this.managerErrors.email.isInvalid = 1;
                        _this.managerErrors.email.validInfo = errs.email[0];
                    }
                    if(errs.phone){
                        _this.managerErrors.phone.isInvalid = 1;
                        _this.managerErrors.phone.validInfo = errs.phone[0];
                    }
                });
            },

            resetOwnPwd: function () {
                var _this=this;
                _this.managerErrors.oldPwd.isInvalid = 0;
                _this.managerErrors.pwd.isInvalid = 0;
                _this.managerErrors.pwdConfirmation.isInvalid = 0;
                _this.managerErrors.oldPwd.validInfo = '';
                _this.managerErrors.pwd.validInfo = '';
                _this.managerErrors.pwdConfirmation.validInfo = '';
                if(!_this.pwdInfo.oldPwd){
                    _this.managerErrors.oldPwd.validInfo = '旧密码必填！';
                    _this.managerErrors.oldPwd.isInvalid = 1;
                    return false;
                }

                if (!_this.pwdInfo.pwd){
                    _this.managerErrors.pwd.validInfo = '新密码必填！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if(_this.pwdInfo.oldPwd == _this.pwdInfo.pwd){
                    _this.managerErrors.pwd.validInfo = '新密码与旧密码一致！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if(_this.pwdInfo.pwd.length<6){
                    _this.managerErrors.pwd.validInfo = '新密码至少6位！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if(!_this.pwdInfo.pwd.match(/=|\+|-|@|_|\*|[a-zA-Z]/g)){
                    _this.managerErrors.pwd.validInfo = '"A-Z" "a-z" "+" "_" "*" "=" "-" "@"至少存在1项！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if (_this.pwdInfo.pwd != _this.pwdInfo.pwdConfirmation){
                    _this.managerErrors.pwdConfirmation.validInfo = '两次密码不一致！';
                    _this.managerErrors.pwdConfirmation.isInvalid = 1;
                    return false;
                }

                var url = "{{ url('admin/account') }}"+"/"+_this.managerInfo.id+"?user_id="+_this.managerInfo.id;
                $.ajax({
                    url:url,
                    dataType:'json',
                    headers:{
                        'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                    },
                    timeout:60000,
                    data: {
                        old_pwd: _this.pwdInfo.oldPwd,
                        pwd: _this.pwdInfo.pwd,
                        pwd_confirmation: _this.pwdInfo.pwdConfirmation,
                        _method:'PUT'
                    },
                    type:'POST'
                }).done(function (data) {
                    if(data.ret_num === 0){
                        $("#completeDetail").modal("hide");
                        if (data.reLogin == 1){
                            alert("保存成功！请重新登录！");
                            window.location.href = data.reUrl;
                        }else{
                            alert(data.ret_msg);
                        }
                    }else{
                        _this.managerErrors.oldPwd.validInfo = data.ret_msg;
                        _this.managerErrors.oldPwd.isInvalid = 1;
                    }
                }).fail(function (data) {
                    var errs = JSON.parse(data.responseText);
                    if (errs.old_pwd){
                        _this.managerErrors.oldPwd.isInvalid = 1;
                        _this.managerErrors.oldPwd.validInfo = errs.old_pwd[0];
                    }
                    if (errs.pwd){
                        _this.managerErrors.pwd.isInvalid = 1;
                        _this.managerErrors.pwd.validInfo = errs.pwd[0];
                    }
                    if (errs.pwd_confirmation){
                        _this.managerErrors.pwdConfirmation.isInvalid = 1;
                        _this.managerErrors.pwdConfirmation.validInfo = errs.pwd_confirmation[0];
                    }
                });
            }

        }

    });
</script>

<script>
    new Vue({
        el: '#top-news',
        data: {
            total: 0,
            news: []
        },
        ready: function () {
            var url = "{{ url('admin/notify') }}";
            var _this = this;
            $.ajax({
                url:url,
                dataType:'json',
                headers:{
                    'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                },
                timeout:60000,
                data: {},
                type:'GET'
            }).done(function (data) {
                _this.total = data.total;
                _this.news = data.data;
            }).fail(function (data) {
                alert("网络错误！");
                return false;
            });
        },
        methods: {

        }
    });
</script>

<script>
    $(function () {

        $('.todo-checkbox').click(function () {

            var _activeCheckbox = $(this).find('input[type="checkbox"]');

            if (_activeCheckbox.is(':checked')) {
                $(this).parent().addClass('selected');
            }
            else {
                $(this).parent().removeClass('selected');
            }

        });

        //Delete Widget Confirmation
        $('#deleteWidgetConfirm').popup({
            vertical: 'top',
            pagecontainer: '.container',
            transition: 'all 0.3s'
        });
    });

</script>

</body>
</html>
