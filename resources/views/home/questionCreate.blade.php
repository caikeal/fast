@extends('home.app')
@section('head')
    <title>答疑解惑</title>
@endsection
@section('moreCss')
    <style>
        body {
            font-size:1.5rem;
            font-family: inherit;
            font-style: inherit;
            color: #625F5F;
            background-color: #e9ecf5;
        }
        .am-navbar .am-navbar-nav{
            overflow: visible;
        }
        .am-form textarea{
            margin-top: 2.3rem;
        }
        .am-btn{
            font-size: large;
            font-family: inherit;
            font-style: normal;
            margin-top: 2.0rem;
        }
        .am-text {
            margin-bottom: 2.3rem;
        }
        .question-gap {
            margin-top: 2.3rem;
        }
        #question-title {
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        #question-container {
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        .am-alert>.am-close:first-child{
            margin: auto;
        }
    </style>
@endsection
@section('back')
    <a href="{{ url('index') }}" class="am-icon-chevron-left" style="float:left; color: #fff;" id="btn-back"></a>
@endsection
@section('content')
    <div class="question-gap">
        @if(session('message'))
            <div class="am-alert am-alert-success" data-am-alert>
                <button type="button" class="am-close">&times;</button>
                {{ session('message') }}
            </div>
        @endif
    </div>
    <form action="{{ url('question') }}" class="am-form" method="post" data-am-validator>
        {!! csrf_field() !!}
        <fieldset>
            <div class="am-form-group am-input-group-lg">
                <input type="text" name="title" id="question-title" minlength="1" placeholder="问题概要(如：社保帐号查询，至少1个字)" required/>
            </div>

            <div class="am-form-group ">
                <div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-text">
                    <label class="am-radio-inline">
                        <input type="radio" value="2" name="cat" minchecked="1" maxchecked="1" data-am-ucheck required> 社保公积金类
                    </label>
                </div>
                <div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-text">
                    <label class="am-radio-inline">
                        <input type="radio" value="1" data-am-ucheck name="cat"> 工资单问题
                    </label>
                </div>
                <div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-text">
                    <label class="am-radio-inline">
                        <input type="radio" value="3" data-am-ucheck name="cat"> 理赔查询类
                    </label>
                </div>
                <div class="am-u-sm-6 am-u-md-6 am-u-lg-6 am-text">
                    <label class="am-radio-inline">
                        <input class="am-secondary" type="radio" data-am-ucheck value="4" name="cat"> 弹性福利类
                    </label>
                </div>
                <div class="am-cf"></div>
            </div>
            <div class="am-form-group">
                <textarea id="question-container" name="detail" minlength="2" maxlength="100" rows="5" placeholder="具体细节(如：我登录社保网站无法找到查看社保的入口。至少2个字)"></textarea>
            </div>
            <button class="am-btn am-btn-secondary am-btn-block am-radius" type="submit">提交问题</button>
        </fieldset>
    </form>
@endsection
@section('moreScript')
@endsection