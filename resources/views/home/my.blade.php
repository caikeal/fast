@extends('home.app')
@section('head')
    <title>我的信息</title>
@endsection
@section('moreCss')
    <style>
        body {
            background-color: #fff;
        }

        section .am-g a {
            border-radius: 5px;
        }
    </style>
@endsection
@section('back')
    <div class="am-header-left am-header-nav">
        <a onClick="javascript :history.back(-1);" class="">
            <img class="am-header-icon-custom"
                 src="data:image/svg+xml;charset=utf-8,&lt;svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 12 20&quot;&gt;&lt;path d=&quot;M10,0l2,2l-8,8l8,8l-2,2L0,10L10,0z&quot; fill=&quot;%23fff&quot;/&gt;&lt;/svg&gt;"
                 alt=""/> 返回
        </a>
    </div>
@endsection
@section('content')
    <section>
        <div class="am-g am-center am-u-sm-centered">
            <img class="logo am-u-sm-centered" src="{{env("APP_URL")}}/images/logo.png">
        </div>
        <div class="am-g" style="margin-top: 2.5rem;">
            <div class="am-u-sm-6">
                <a href="{{url('rebinding')}}" class="am-btn am-btn-primary am-btn-block am-btn-lg" id="change-phone">重新绑定手机</a>
            </div>
            <div class="am-u-sm-6">
                <a href="{{url('reset')}}" class="am-btn am-btn-success am-btn-block am-btn-lg" id="change-secret">修改密码</a>
            </div>
        </div>
        <div class="am-g" style="margin-top: 2.5rem;">
            <div class="am-u-sm-12 am-u-sm-centered">
                <a href="{{url('logout')}}" class="am-btn am-btn-default am-btn-block am-btn-xl" style="color:#0e90d2"
                   id="logout">退出登录
                </a>
            </div>
        </div>
    </section>
@endsection
@section('moreJs')
@endsection