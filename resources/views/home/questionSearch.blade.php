@extends('home.app')
@section('head')
    <title>答疑解惑</title>
@endsection
@section('moreCss')
    <style>
        body{
            background-color: #e9ecf5;
        }
        .am-navbar .am-navbar-nav{
            overflow: visible;
        }
        .gap{
            padding-bottom: 1.5rem;
        }
        .am-form-field{
            border-left-style:none;
        }
        .search-combine{
            background-color: #fff;
            -webkit-appearance: none;
            -webkit-transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
            transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        }
        #input{
            border-right: 1px solid #fff;
        }
        .input-focus{
            background-color: #fefffe;
            border-color: #3bb4f2;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 5px rgba(59,180,242,.3);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 5px rgba(59,180,242,.3);
        }
        .am-form-field:focus{
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        }

        .history .am-list>li{
            border: none;
            background-color: #e9ecf5;
        }

        .search-content{
            margin-top: 1.5rem;
        }
        .search-content h3.main-title{
            width: 80%;
            overflow: hidden;
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            word-break: break-all;
            text-overflow: ellipsis;
            margin-bottom: 1rem;
        }
        .search-content .status {
            color: #b6b6b6;
        }
        .search-content p.detail {
            width: 80%;
            overflow: hidden;
            color: #b6b6b6;
            white-space: nowrap;
            word-break: break-all;
            text-overflow: ellipsis;
            margin-bottom: 0;
        }
        .search-content .go-right{
            color: #b6b6b6;
        }
        .search-content .am-list{
            border-top: 1px solid rgba(162,162,162,0.2);
        }
        .search-content .am-list>li{
            border:none;
            border-bottom: 1px dashed #a2a2a2;
            background-color: transparent;
            margin-bottom: 0;
        }
        .search-content .am-list>li a {
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            color: #625F5F;
            padding: 0;
        }
        .search-content .am-list>li a:hover{
            background-color: transparent;
        }
        .remind{
            text-align: center;
            margin-top: 1rem;
        }
        .history .search-history{
            margin-top: .5rem;
            padding:0;
            text-align: center;
            color: #b5b5b5;
            font-size: 18px;
        }
        .history .search-history-list{
            text-align: center;
            color: gray;
            margin-top:1rem;
        }
        .history .clear-history{
            text-align: center;
            color: gray;
            margin-top:1rem;
        }
        .history .clear-history-btn{
            text-decoration:underline;
            font-size: 18px;
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
    <div id="search-period" v-cloak>
        <div class="gap"></div>
        <!-- search -->
        <form class="am-g" action="#" method="get" @submit.prevent="searchList" @input="searchList">
            <div class="am-u-md-12">
                <div class="am-input-group">
                    <span class="am-input-group-label am-round search-combine">
                        <span class="am-icon-search"></span>
                    </span>
                    <input type="text" id="input" class="am-form-field am-round" placeholder="输入标题内容" v-model="search">

                    <span class="am-input-group-label am-round search-combine search-clear"><span class="am-icon-times-circle" v-show="search" @click="clearInput"></span></span>
                </div>
            </div>
            <div class="am-cf"></div>
        </form>
        <!-- search end -->

        <!-- remind -->
        <section class="remind" v-show="remind">@{{ remind }}</section>
        <!-- remind end -->

        <!-- search-content -->
        <section class="search-content" v-show="questionList.length">
            <ul class="am-list am-list-static am-list-border">
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
        </section>
        <!-- search-content end -->

        <!-- history -->
        <section class="history" v-show="!questionList.length">
            <div class="am-g" style="margin-top: 1rem">
                <div class="am-u-md-4 am-u-sm-4">
                    <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed" />
                </div>
                <div class="am-u-md-4 am-u-sm-4 search-history">
                    <div>搜 索 历 史</div>
                </div>
                <div class="am-u-md-4 am-u-sm-4">
                    <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed" />
                </div>
            </div>
            <div class="am-g search-history-list">
                <ul class="am-list am-list-static" v-for="cacheItem in cache | limitBy 5">
                    <li @click="giveSearch(cacheItem)">@{{ cacheItem }}</li>
                </ul>
            </div>

            <div class="am-g clear-history">
                <div><a href="#" class="clear-history-btn" @click.prevent="clearCache">清除搜索历史</a></div>
            </div>
        </section>
        <!-- history end -->
    </div>
@endsection
@section('moreScript')
    <script>
        $("#input").on("focus",function(){
            $(this).siblings().addClass("input-focus");
        });
        $("#input").on("blur",function(){
            $(this).siblings().removeClass("input-focus");
        });
    </script>
    <script>
        new Vue({
            el:"#search-period",
            data:{
                questionList:[],
                pageInfo: [],
                search: "",
                pull: 0,
                remind: "",
                cache: [],
            },
            ready: function(){
                var _this = this;
                var pull = 0;
                this.cache = localStorage.getItem("searchCache")?localStorage.getItem("searchCache").split("||"):[];
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
            methods: {
                clearInput: function(){
                    this.search="";
                },
                searchList: function () {
                    var _this = this;
                    var url = "{{ url('question') }}";
                    //获取接口数据
                    if (_this.pull === 0 && _this.search){
                        _this.pull = 1;
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            data:{
                                _token: $("meta[name=csrf-token]").attr("content"),
                                wd: _this.search
                            },
                            method: 'GET'
                        }).done(function (msg) {
                            if (msg.data.length){
                                _this.remind = "";
                                _this.questionList = msg.data;
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
                                _this.remind="暂无相关内容，请去直接提问";
                                _this.questionList=[];
                            }
                            _this.pull = 0;
                            if (_this.cache.indexOf(_this.search)==-1){
                                _this.cache.unshift(_this.search);
                            }
                            _this.setCache();
                            return false;
                        }).fail(function (error) {
                            alert("网络错误！");
                            _this.pull = 0;
                            return false;
                        });
                    }

                },
                clearCache: function () {
                    localStorage.removeItem("searchCache");
                    this.cache=[];
                },
                setCache: function () {
                    var _this = this;
                    var cache = localStorage.getItem("searchCache");
                    var caches = [];
                    if (cache && _this.search){
                        caches = cache.split("||");
                        //剔除重复搜索
                        if (caches.indexOf(_this.search)!=-1){
                            return false;
                        }
                        //加入本地缓存中
                        if (caches.length>=5){
                            caches.pop();
                            caches.unshift(_this.search);
                        }else{
                            caches.unshift(_this.search);
                        }
                        cache = caches.join("||");
                    }else if(!cache){
                        cache = _this.search;
                    }
                    localStorage.setItem("searchCache", cache);
                },
                giveSearch: function (wd) {
                    this.search = wd;
                    this.searchList();
                }
            }
        });
    </script>
@endsection