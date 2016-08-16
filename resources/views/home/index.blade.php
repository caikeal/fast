@extends('home.app')
@section('head')
    <title>FAST</title>
@endsection
@section('content')
<!-- 滑框start -->
<div data-am-widget="slider" class="am-slider am-slider-b2" data-am-slider='{"directionNav":false}'>
    <ul class="am-slides">
        <li>
            <img src="{{env("APP_URL")}}/images/banner_home.jpg">
        </li>
    </ul>
</div>
<!-- 滑框end -->
<!-- 新四宫格 -->
<ul class="am-avg-sm-2 boxes">
    <li class="box box-1">
        <a href="{{url('salary')}}">
            <div class="am-g">
                <div class="am-u-sm-5 thumb">
                    <img src="{{env('APP_URL')}}/images/salary.png" alt="薪资查询图片">
                </div>

                <div class=" am-u-sm-7 content">
                    <div class="am-list-item-hd">薪资查询</div>

                    <div class="am-list-item-text">来看看你的薪资吧</div>
                </div>
            </div>
        </a>
    </li>
    <li class="box box-2">
        <a href="{{ url('compensation/index') }}">
            <div class="am-g">
                <div class="am-u-sm-5 thumb">
                    <img src="{{env('APP_URL')}}/images/settlement.png" alt="理赔查询图片">
                </div>

                <div class=" am-u-sm-7 content">
                    <div class="am-list-item-hd">理赔查询</div>

                    <div class="am-list-item-text">来看看你的理赔吧</div>
                </div>
            </div>
        </a>
    </li>
    <li class="box box-3">
        <a href="http://www.fesco-buy.com/">
            <div class="am-g">
                <div class="am-u-sm-5 thumb">
                    <img src="{{env('APP_URL')}}/images/welfware.png" alt="弹性福利图片">
                </div>

                <div class=" am-u-sm-7 content">
                    <div class="am-list-item-hd">弹性福利</div>

                    <div class="am-list-item-text">来看看你的福利吧</div>
                </div>
            </div>
        </a>
    </li>
    <li class="box box-4">
        <a href="{{url('insurance')}}">
            <div class="am-g">
                <div class="am-u-sm-5 thumb">
                    <img src="{{env('APP_URL')}}/images/insurance.png" alt="社保图片">
                </div>

                <div class=" am-u-sm-7 content">
                    <div class="am-list-item-hd">社保查询</div>

                    <div class="am-list-item-text">来看看你的社保吧</div>
                </div>
            </div>
        </a>
    </li>
</ul>
<!-- 新四宫格 end-->
@endsection()