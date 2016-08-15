@extends('home.app')
@section('head')
    <title>联系我们</title>
@endsection
@section('moreCss')
    <style>
        body{
            background-color: #e9ecf5;
        }
        .am-navbar .am-navbar-nav{
            overflow: visible;
        }
        .contact-list{
            background-color: #fff;padding-top:.8rem;padding-bottom: .8rem;margin-top: .8rem;
        }
        .email-list-gap{
            margin-top: 10px;
        }
    </style>
@endsection
@section('back')
    <div class="am-header-left am-header-nav">
        <a onClick="javascript :history.back(-1);">
            <img class="am-header-icon-custom"
                 src="data:image/svg+xml;charset=utf-8,&lt;svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 12 20&quot;&gt;&lt;path d=&quot;M10,0l2,2l-8,8l8,8l-2,2L0,10L10,0z&quot; fill=&quot;%23fff&quot;/&gt;&lt;/svg&gt;"
                 alt=""/> 返回
        </a>
    </div>
@endsection
@section('content')
        <!-- 内容 -->
    <!-- 三个联系人 -->
    @if($salaryFromer['name'])
    <div class="am-u-sm-12 contact-list">
        <h2 style="font-size: larger;margin-bottom: 0;">薪资查询联系人</h2>
        <hr style="margin: .5rem"></hr>
        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
            <a href="javascript:void(0);" class="am-badge am-badge-danger am-round" style="line-height: 1.6"><i class="am-icon-user am-round"></i></a>
            <span class="name">{{ $salaryFromer['name'] }}</span>
        </div>
        <div class="am-u-sm-8 am-u-md-8 am-u-lg-8">
            <a href="javascript:void(0);" class="call am-badge am-badge-primary am-round" style="line-height: 1.6"><i class="am-icon-phone am-round"></i></a>
            <a href="tel://{{ $salaryFromer['phone'] }}">
                <span class="telephone">{{ $salaryFromer['phone'] }}</span>
            </a>
        </div>
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 email-list-gap">
            <a href="javascript:void(0);" class="send am-badge am-badge-warning  am-round" style="line-height: 1.6"><i class="am-icon-envelope am-round"></i></a>
            <a href="mailto:{{ $salaryFromer['email'] }}">
                <span class ="email">{{ $salaryFromer['email'] }}</span>
            </a>
        </div>
    </div>
    @endif
    @if($insuranceFromer['name'])
    <div class="am-u-sm-12 contact-list">
        <h2 style="font-size: larger;margin-bottom: 0;">社保查询联系人</h2>
        <hr style="margin: .5rem"></hr>
        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
            <a href="javascript:void(0);" class="am-badge am-badge-danger am-round" style="line-height: 1.6"><i class="am-icon-user am-round"></i></a>
            <span class="name">{{ $insuranceFromer['name'] }}</span>
        </div>
        <div class="am-u-sm-8 am-u-md-8 am-u-lg-8">
            <a href="javascript:void(0);" class="call am-badge am-badge-primary am-round" style="line-height: 1.6"><i class="am-icon-phone am-round"></i></a>
            <a href="tel://{{ $insuranceFromer['phone'] }}">
                <span class="telephone">{{ $insuranceFromer['phone'] }}</span>
            </a>
        </div>
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 email-list-gap">
            <a href="javascript:void(0);" class="send am-badge am-badge-warning am-round" style="line-height: 1.6"><i class="am-icon-envelope am-round"></i></a>
            <a href="mailto:{{ $insuranceFromer['email'] }}">
                <span class ="email">{{ $insuranceFromer['email'] }}</span>
            </a>
        </div>
    </div>
    @endif
    @if($compensationFromer['name'])
    <div class="am-u-sm-12 contact-list">
        <h2 style="font-size: larger;margin-bottom: 0;">理赔查询联系人</h2>
        <hr style="margin: .5rem"></hr>
        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
            <a class="am-badge am-badge-danger am-round" style="line-height: 1.6"><i class="am-icon-user am-round"></i></a>
            <span class="name">{{ $compensationFromer['name'] }}</span>
        </div>
        <div class="am-u-sm-8 am-u-md-8 am-u-lg-8">
            <a href="javascript:void(0);" class="call am-badge am-badge-primary am-round" style="line-height: 1.6"><i class="am-icon-phone am-round"></i></a>
            <a href="tel://{{ $compensationFromer['phone'] }}">
                <span class="telephone">{{ $compensationFromer['phone'] }}</span>
            </a>
        </div>
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 email-list-gap">
            <a href="javascript:void(0);" class="send am-badge am-badge-warning am-round" style="line-height: 1.6"><i class="am-icon-envelope am-round"></i></a>
            <a href="mailto:{{ $compensationFromer['email'] }}">
                <span class ="email">{{ $compensationFromer['email'] }}</span>
            </a>
        </div>
    </div>
    @endif
    <!-- 三个联系人 end-->
    <div class="am-u-sm-12" style="margin-top: .3rem;">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 ">
            <span style="font-size: larger;font-family: -webkit-body">投诉电话:</span>
            <a href="tel://0512-62858772" id="complaint" class="complaint" style="font-weight: 600;text-decoration:underline;">0512-62858772</a>
            <a href="tel://0512-62521528" id="complaint" class="complaint" style="font-weight: 600;text-decoration:underline;">0512-62521528</a>
        </div>
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-cf">
            <div class="am-cf am-fl">
                <span style="font-size: larger;font-family: -webkit-body">地&nbsp;&nbsp;址:</span>
                <span id="address" style="font-weight: 600">苏州市工业园区国际科技园1期201B</span>
            </div>
        </div>
    </div>
@endsection
@section('moreScript')
@endsection