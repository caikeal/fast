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
            color: #000;
            background-color: #fff;
        }
        .am-navbar .am-navbar-nav{
            overflow: visible;
        }
        .gap{
            margin-top:2rem;
        }
        section .question{
            margin: 0 15px;
        }
        .question h3{
            font-size: 18px;
        }
        .title{
            border-bottom: 1px dashed #d1d3da;
            padding-bottom: 0.8rem;
            margin-bottom: 0;
        }
        .detail{
            margin-top: 0.8rem;
            border-bottom: 1px dashed #d1d3da;
            padding-bottom: 0.8rem;
            margin-bottom: 0;
            color: #b6b6b6;
        }
        .answer{
            font-size: 16px;
            margin-top: 0.8rem;
        }
    </style>
@endsection
@section('back')
    <a onclick="javascript :history.back();" class="am-icon-chevron-left" style="float:left; color: #fff;" id="btn-back"></a>
@endsection
@section('content')
    <div class="gap"></div>
    <section>
        <div class="question">
            <h3 class="title">{{ $info['title'] }}</h3>
            @if($info['creator']==\Auth::user()->id)
            <p class="detail">{{ $info['detail'] }}</p>
            @endif
            <p class="answer">{{ $info['answer']?$info['answer']:"客服暂未回应，请等待。。。" }}</p>
        </div>
    </section>
@endsection
@section('moreScript')
@endsection