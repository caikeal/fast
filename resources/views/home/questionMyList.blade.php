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
            background-color: #fff;
        }
        .am-navbar .am-navbar-nav{
            overflow: visible;
        }
        .am-list{
            border-top: 1px solid #dedede;
        }
        .am-list>li{
            border:none;
            border-bottom: 1px dashed #d1d3da;
            background-color: transparent;
            margin-bottom: 0;
        }
        .am-list>li a {
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            color: #625F5F;
            padding: 0;
        }
        .am-list>li a:hover{
            background-color: transparent;
        }
        .gap{
            padding-top: 2rem;
            background-color: #e9ecf5;
        }
        h3.main-title{
            width: 80%;
            overflow: hidden;
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            word-break: break-all;
            text-overflow: ellipsis;
            margin-bottom: 1rem;
        }
        .status {
            color: #b6b6b6;
        }
        p.detail {
            width: 80%;
            overflow: hidden;
            color: #b6b6b6;
            white-space: nowrap;
            word-break: break-all;
            text-overflow: ellipsis;
            margin-bottom: 0;
        }
        .go-right{
            color: #b6b6b6;
        }
        [v-cloak]{
            display: none;
        }
    </style>
@endsection
@section('back')
    <a href="{{ url('index') }}" class="am-icon-chevron-left" style="float:left; color: #fff;" id="btn-back"></a>
@endsection
@section('content')
    <div class="gap"></div>
    <section id="mylist">
        <ul class="am-list am-list-static am-list-border" v-cloak  v-if="questionList.length !== 0">
            <li v-for="question in questionList">
                <a href="{{ url('question') }}/@{{ question.id }}">
                    <div>
                        <h3 class="main-title am-fl">@{{ question.title }}</h3>
                        <div class="status am-fr">@{{ question.status==1?'待回复':'已回复' }}</div>
                        <div class="am-cf"></div>
                    </div>
                    <div>
                        <p class="detail am-fl">@{{ question.answer }}</p>
                        <div class="am-icon-chevron-right am-fr go-right"></div>
                        <div class="am-cf"></div>
                    </div>
                </a>
            </li>
        </ul>

        <div v-else style="text-align: center;">已无更多内容</div>
    </section>
@endsection
@section('moreScript')
    <script>
        new Vue({
            el:"#mylist",
            data:{
                questionList:[],
                pageInfo: [],
            },
            created: function(){
                //获取问题列表接口数据
                $.ajax({
                    url: "{{ url('question') }}",
                    dataType: 'json',
                    data:{
                        _token: $("meta[name=csrf-token]").attr("content"),
                        history: 1
                    },
                    method: 'GET'
                }).done(function (msg) {
                    this.questionList = msg.data;
                    this.pageInfo = {
                        current_page: msg.current_page,
                        from: msg.from,
                        last_page: msg.last_page,
                        next_page_url: msg.next_page_url,
                        per_page: msg.per_page,
                        prev_page_url: msg.prev_page_url,
                        to: msg.to,
                        total: msg.total,
                    };
                }.bind(this)).fail(function (error) {
                    alert("网络错误！");
                });
            },
            ready: function(){
                var _this = this;
                var pull = 0;
                $(window).scroll(function(){
                    //监听显示回复框
                    var scrollTop = $(window).scrollTop();

                    //监听下拉刷新
                    var wrapperHeight = $(document).height();
                    var windowHeight = $(window).height();
                    var url = _this.pageInfo.next_page_url;

                    if(windowHeight + scrollTop + 300 >= wrapperHeight && pull === 0 && url){
                        //锁定请求
                        pull = 1;
                        //获取接口数据
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            data:{
                                _token: $("meta[name=csrf-token]").attr("content"),
                            },
                            method: 'GET'
                        }).done(function (msg) {
                            if (msg.data.length){
                                for(var i = 0; i < msg.data.length; i++){
                                    _this.questionList.push(msg.data[i]);
                                }
                                _this.pageInfo = {
                                    current_page: msg.current_page,
                                    from: msg.from,
                                    last_page: msg.last_page,
                                    next_page_url: msg.next_page_url,
                                    per_page: msg.per_page,
                                    prev_page_url: msg.prev_page_url,
                                    to: msg.to,
                                    total: msg.total
                                };
                            }else{
                                alert("已无更多内容！");
                            }
                            pull = 0;
                            return false;
                        }).fail(function (error) {
                            alert("网络错误！");
                            pull = 0;
                            return false;
                        });
                    }
                });
            },
        });
    </script>
@endsection