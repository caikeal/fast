<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fast Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fast Fast And Fast">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="apple-touch-icon-precomposed" href="{{env('APP_URL')}}/images/114.png" sizes="114x114" />
    <link rel="shortcut icon" href="{{env('APP_URL')}}/images/32.ico" type="image/x-icon" />
    <meta name="author" content="Keal">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="http://apps.bdimg.com/libs/fontawesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- ionicons -->
    <link href="{{env('APP_URL')}}/css/ionicons.min.css" rel="stylesheet">

    <!-- Morris -->
    <link href="{{env('APP_URL')}}/css/morris.css" rel="stylesheet"/>

    <!-- Datepicker -->
    <link href="{{env('APP_URL')}}/css/datepicker.css" rel="stylesheet"/>

    <!-- Animate -->
    <link href="http://apps.bdimg.com/libs/animate.css/3.1.0/animate.min.css" rel="stylesheet">

    <!-- Owl Carousel -->
    <link href="{{env('APP_URL')}}/css/owl.carousel.min.css" rel="stylesheet">
    <link href="{{env('APP_URL')}}/css/owl.theme.default.min.css" rel="stylesheet">

    <!-- Simplify -->
    <link href="{{env('APP_URL')}}/css/admin/simplify.min.css" rel="stylesheet">

    @yield('moreCss')
</head>

<body class="overflow-hidden">
<div class="wrapper preload">
    <header class="top-nav">
        <div class="top-nav-inner">
            <div class="nav-header">
                <button type="button" class="navbar-toggle pull-left sidebar-toggle" id="sidebarToggleSM">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                {{--<ul class="nav-notification pull-right">--}}
                    {{--<li>--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></a>--}}
                        {{--<span class="badge badge-danger bounceIn">1</span>--}}
                        {{--<ul class="dropdown-menu dropdown-sm pull-right user-dropdown">--}}
                            {{--<li class="user-avatar">--}}
                                {{--<img src="{{Auth::guard('admin')->user()->poster}}" alt="" class="img-circle">--}}
                                {{--<div class="user-content">--}}
                                    {{--<h5 class="no-m-bottom">{{Auth::guard('admin')->user()->name}}</h5>--}}
                                    {{--<div class="m-top-xs">--}}
                                        {{--<a href="profile.html" class="m-right-sm">Profile</a>--}}
                                        {{--<a href="{{url('admin/logout')}}">Log out</a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="inbox.html">--}}
                                    {{--Inbox--}}
                                    {{--<span class="badge badge-danger bounceIn animation-delay2 pull-right">1</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--Notification--}}
                                    {{--<span class="badge badge-purple bounceIn animation-delay3 pull-right">2</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="divider"></li>--}}
                            {{--<li>--}}
                                {{--<a href="#">Setting</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--</ul>--}}

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
                            <img src="{{Auth::guard('admin')->user()->poster}}" alt="" class="img-circle inline-block user-profile-pic">
                            <div class="user-detail inline-block">
                                {{Auth::guard('admin')->user()->name}}
                                <i class="fa fa-angle-down"></i>
                            </div>
                        </a>
                        <div class="panel border dropdown-menu user-panel">
                            <div class="panel-body paddingTB-sm">
                                <ul>
                                    {{--<li>--}}
                                        {{--<a href="profile.html">--}}
                                            {{--<i class="fa fa-edit fa-lg"></i><span class="m-left-xs">My Profile</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="inbox.html">--}}
                                            {{--<i class="fa fa-inbox fa-lg"></i><span class="m-left-xs">Inboxes</span>--}}
                                            {{--<span class="badge badge-danger bounceIn animation-delay3">2</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{url('admin/logout')}}">
                                            <i class="fa fa-power-off fa-lg"></i><span class="m-left-xs">退出</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{--<ul class="nav-notification">--}}
                        {{--<li>--}}
                            {{--<a href="#" data-toggle="dropdown"><i class="fa fa-envelope fa-lg"></i></a>--}}
                            {{--<span class="badge badge-purple bounceIn animation-delay5 active">2</span>--}}
                            {{--<ul class="dropdown-menu message pull-right">--}}
                                {{--<li><a>You have 4 new unread messages</a></li>--}}
                                {{--<li>--}}
                                    {{--<a class="clearfix" href="#">--}}
                                        {{--<img src="images/profile/profile2.jpg" alt="User Avatar">--}}
                                        {{--<div class="detail">--}}
                                            {{--<strong>John Doe</strong>--}}
                                            {{--<p class="no-margin">--}}
                                                {{--Lorem ipsum dolor sit amet...--}}
                                            {{--</p>--}}
                                            {{--<small class="text-muted"><i class="fa fa-check text-success"></i> 27m ago</small>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a class="clearfix" href="#">--}}
                                        {{--<img src="images/profile/profile3.jpg" alt="User Avatar">--}}
                                        {{--<div class="detail">--}}
                                            {{--<strong>Jane Doe</strong>--}}
                                            {{--<p class="no-margin">--}}
                                                {{--Lorem ipsum dolor sit amet...--}}
                                            {{--</p>--}}
                                            {{--<small class="text-muted"><i class="fa fa-check text-success"></i> 5hr ago</small>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a class="clearfix" href="#">--}}
                                        {{--<img src="images/profile/profile4.jpg" alt="User Avatar">--}}
                                        {{--<div class="detail m-left-sm">--}}
                                            {{--<strong>Bill Doe</strong>--}}
                                            {{--<p class="no-margin">--}}
                                                {{--Lorem ipsum dolor sit amet...--}}
                                            {{--</p>--}}
                                            {{--<small class="text-muted"><i class="fa fa-reply"></i> Yesterday</small>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a class="clearfix" href="#">--}}
                                        {{--<img src="images/profile/profile5.jpg" alt="User Avatar">--}}
                                        {{--<div class="detail">--}}
                                            {{--<strong>Baby Doe</strong>--}}
                                            {{--<p class="no-margin">--}}
                                                {{--Lorem ipsum dolor sit amet...--}}
                                            {{--</p>--}}
                                            {{--<small class="text-muted"><i class="fa fa-reply"></i> 9 Feb 2013</small>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="#">View all messages</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#" data-toggle="dropdown"><i class="fa fa-bell fa-lg"></i></a>--}}
                            {{--<span class="badge badge-info bounceIn animation-delay6 active">4</span>--}}
                            {{--<ul class="dropdown-menu notification dropdown-3 pull-right">--}}
                                {{--<li><a href="#">You have 5 new notifications</a></li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
												{{--<span class="notification-icon bg-warning">--}}
													{{--<i class="fa fa-warning"></i>--}}
												{{--</span>--}}
                                        {{--<span class="m-left-xs">Server #2 not responding.</span>--}}
                                        {{--<span class="time text-muted">Just now</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
												{{--<span class="notification-icon bg-success">--}}
													{{--<i class="fa fa-plus"></i>--}}
												{{--</span>--}}
                                        {{--<span class="m-left-xs">New user registration.</span>--}}
                                        {{--<span class="time text-muted">2m ago</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
												{{--<span class="notification-icon bg-danger">--}}
													{{--<i class="fa fa-bolt"></i>--}}
												{{--</span>--}}
                                        {{--<span class="m-left-xs">Application error.</span>--}}
                                        {{--<span class="time text-muted">5m ago</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
												{{--<span class="notification-icon bg-success">--}}
													{{--<i class="fa fa-usd"></i>--}}
												{{--</span>--}}
                                        {{--<span class="m-left-xs">2 items sold.</span>--}}
                                        {{--<span class="time text-muted">1hr ago</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
												{{--<span class="notification-icon bg-success">--}}
													{{--<i class="fa fa-plus"></i>--}}
												{{--</span>--}}
                                        {{--<span class="m-left-xs">New user registration.</span>--}}
                                        {{--<span class="time text-muted">1hr ago</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="#">View all notifications</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
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
                    <li class="bg-palette1 active">
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
                    {{--<li class="bg-palette2">--}}
                        {{--<a href="landing/landing.html">--}}
									{{--<span class="menu-content block">--}}
										{{--<span class="menu-icon"><i class="block fa fa-desktop fa-lg"></i></span>--}}
										{{--<span class="text m-left-sm">Landing</span>--}}
									{{--</span>--}}
									{{--<span class="menu-content-hover block">--}}
										{{--Landing--}}
									{{--</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
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
                    <li class="bg-palette2">
                        <a href="{{url('admin/timeline')}}">
									<span class="menu-content block">
										<span class="menu-icon"><i class="block fa fa-clock-o fa-lg"></i></span>
										<span class="text m-left-sm">时间线</span>
										{{--<small class="badge badge-warning badge-square bounceIn animation-delay6 m-left-xs pull-right">7</small>--}}
									</span>
									<span class="menu-content-hover block">
										时间线
									</span>
                        </a>
                    </li>
                    <li class="menu-header">
                        Others
                    </li>
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
                <a href="{{url('admin/logout')}}" class="pull-right font-18"><i class="ion-log-out"></i></a>
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

<!-- Flot -->
<script src='http://apps.bdimg.com/libs/flot/0.8.2/jquery.flot.min.js'></script>

<!-- Slimscroll -->
<script src='{{env('APP_URL')}}/js/jquery.slimscroll.min.js'></script>

<!-- Morris -->
<script src='http://apps.bdimg.com/libs/raphael/2.1.2/raphael-min.js'></script>
<script src='{{env('APP_URL')}}/js/morris.min.js'></script>

<!-- Datepicker -->
<script src='{{env('APP_URL')}}/js/uncompressed/datepicker.js'></script>

<!-- Sparkline -->
<script src='{{env('APP_URL')}}/js/sparkline.min.js'></script>

<!-- Skycons -->
<script src='{{env('APP_URL')}}/js/uncompressed/skycons.js'></script>

<!-- Popup Overlay -->
<script src='{{env('APP_URL')}}/js/jquery.popupoverlay.min.js'></script>

<!-- Easy Pie Chart -->
<script src='{{env('APP_URL')}}/js/jquery.easypiechart.min.js'></script>

<!-- Sortable -->
<script src='{{env('APP_URL')}}/js/uncompressed/jquery.sortable.js'></script>

<!-- Owl Carousel -->
<script src='{{env('APP_URL')}}/js/owl.carousel.min.js'></script>

<!-- Modernizr -->
<script src='{{env('APP_URL')}}/js/modernizr.min.js'></script>

<!-- Simplify -->
<script src="{{env('APP_URL')}}/js/admin/simplify.js"></script>
<script src="{{env('APP_URL')}}/js/admin/simplify_dashboard.js"></script>

@yield('moreScript')
<script>
    $(function()	{
        $('.chart').easyPieChart({
            easing: 'easeOutBounce',
            size: '140',
            lineWidth: '7',
            barColor: '#7266ba',
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            }
        });

        $('.sortable-list').sortable();

        $('.todo-checkbox').click(function()	{

            var _activeCheckbox = $(this).find('input[type="checkbox"]');

            if(_activeCheckbox.is(':checked'))	{
                $(this).parent().addClass('selected');
            }
            else	{
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
