@extends('home.app')
@section('head')
    <title>社保进度查询</title>
@endsection
@section('moreCss')
    <link rel="stylesheet" href="{{env('APP_URL')}}/css/home/selectTime.css">
    <style>
        body {
            background-color: #fff;
        }
        .panel-center{
            padding: 15px 15px 0;
        }
        .am-panel-group.panel-center .am-panel{
            -webkit-border-radius:5px;
            -moz-border-radius:5px;
            border-radius:5px;
            display: block;
            color: #000;
        }
        .am-panel-group.panel-center .am-panel .am-panel-hd{
            border-bottom-right-radius:5px;
            border-bottom-left-radius:5px;
        }
        .panel-attachment{
            font-size: 13px;
            color: #979ea3;
        }
        .panel-left{
            width: 80%;
            float: left;
        }
        .panel-right{
            width: 20%;
            float: left;
            margin-top: 10px;
        }
        .panel-right .am-icon-chevron-right{
            float: right;
            color: #b5b5b5;
        }
        .am-panel-group .am-panel+.am-panel {
            margin-top: 10px;
        }
        .am-panel-group.panel-center .am-panel .am-panel-hd{
            font-size: 14px;
            color: #818181;
        }
        .remind-none{
            text-align: center;
            color: #d0caca;
        }
    </style>
@endsection
@section('content')
    <section class="am-panel-group panel-center">
        @if(count($insurance)==0)
            <div class="remind-none">暂无更多</div>
        @endif
        @foreach($insurance as $k=>$v)
            <a class="am-panel am-panel-default" href="{{ url('insurance/specific') }}/{{ $v['id'] }}">
                <div class="am-panel-bd">

                    <div class="panel-left">
                        <div class="panel-top">社保公积金办理进度</div>
                        <div class="panel-attachment">您的社保公积金办理进度有更新</div>
                    </div>
                    <div class="panel-right">
                        <span class="am-icon-chevron-right"></span>
                    </div>
                    <div class="am-cf"></div>

                </div>
                <div class="am-panel-hd">
                    {{ $v['created_at'] }}
                </div>
            </a>
        @endforeach
    </section>
@endsection
@section('moreScript')
    <script src="{{env('APP_URL')}}/js/home/iscroll.js"></script>
@endsection