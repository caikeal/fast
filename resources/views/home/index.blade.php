@extends('home.app')
@section('head')
    <title>FAST</title>
@endsection
@section('moreCss')
    <style>
        #info-modal .am-modal-dialog {
            border-radius: 5px;
        }
        #info-modal .am-modal-hd{
            font-size: 15px;
        }
    </style>
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

{{--消息弹框--}}
<div class="am-modal am-modal-no-btn" tabindex="-1" id="info-modal">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">@{{ title }}
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <article id="remind-info" style="height:250px; overflow-y: scroll;" data-am-widget="paragraph" class="am-paragraph am-paragraph-default" data-am-paragraph="{ tableScrollable: true, pureview: true }">
                <div v-for="contentItem in content">
                    <img v-if="contentItem.img" :src="contentItem.img">
                    <p v-for="pItem in contentItem.p">@{{ pItem }}</p>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- 新四宫格 end-->
@endsection()
@section('moreScript')
    <script>
        new Vue({
            el: '#info-modal',
            data: {
                title: '',
                content: []
            },
            ready: function () {
                this.fetchData();
            },
            methods: {
                fetchData: function () {
                    var _this = this;
                    // 判断是否需要弹框显示内容提醒
                    $.ajax({
                        url: "{{ url('system/info') }}",
                        dataType: 'json',
                        data:{
                            _token: $("meta[name=csrf-token]").attr("content")
                        },
                        method: 'GET'
                    }).done(function (msg) {
                        _this.title = msg.title;
                        _this.content = [{
                            img: msg.img,
                            p: msg.p.split('\n')
                        }];
                        if (msg.is_show) {
                            $('#info-modal').modal();
                        }
                    });
                }
            }
        });
    </script>
@endsection