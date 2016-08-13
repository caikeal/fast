@extends('admin.app')
@section('content')
    <div class="padding-md" id="app">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title">
                    主页
                </div>
                <div class="page-sub-header">
                    Welcome Back, {{ $manager['name'] }}
                </div>
            </div>
        </div>

        <!--数据块-->
        <div class="row m-top-md">
            @if ($type->contains(1))
            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-danger m-bottom-md">
                    <div class="statistic-title">
                        当月薪资任务
                    </div>

                    <div class="statistic-value">
                        {{ $salaryTask }}
                    </div>

                    <div class="m-top-md">{{ $salaryRate*100 }}% Higher than last month</div>

                    <div class="statistic-icon-background">
                        <i class="ion-eye"></i>
                    </div>
                </div>
            </div>
            @endif

            @if ($type->contains(2))
            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-info m-bottom-md">
                    <div class="statistic-title">
                        当月社保任务
                    </div>

                    <div class="statistic-value">
                        {{ $insuranceTask }}
                    </div>

                    <div class="m-top-md">{{ $insuranceRate*100 }}% Higher than last month</div>

                    <div class="statistic-icon-background">
                        <i class="ion-ios7-cart-outline"></i>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-purple m-bottom-md">
                    <div class="statistic-title">
                        当前答疑解惑数
                    </div>

                    <div class="statistic-value">
                        {{ $questionData }}
                    </div>

                    <div class="m-top-md">&nbsp;</div>

                    <div class="statistic-icon-background">
                        <i class="ion-person-add"></i>
                    </div>
                </div>
            </div>

            {{--<div class="col-lg-3 col-sm-6">--}}
                {{--<div class="statistic-box bg-success m-bottom-md">--}}
                    {{--<div class="statistic-title">--}}
                        {{--Today Earnings--}}
                    {{--</div>--}}

                    {{--<div class="statistic-value">--}}
                        {{--$124.45k--}}
                    {{--</div>--}}

                    {{--<div class="m-top-md">7% Higher than last week</div>--}}

                    {{--<div class="statistic-icon-background">--}}
                        {{--<i class="ion-stats-bars"></i>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <!--end 数据块-->

        @if(\Auth::guard('admin')->user()->can('statistics'))
                <!--图表块-->
                <!--访问次数-->
                <div id="visit-charts" style="width: 100%;height:500px;"></div>
                <!--访问人数-->
                <div id="user-charts" style="width: 100%;height:500px;"></div>
                <!--当月次数-->
                <div id="now-visit-charts" style="width: 100%;height:500px;"></div>
                <!--当月人数-->
                <div id="now-user-charts" style="width: 100%;height:500px;"></div>
                <!--end 图表块-->
        @endif

    </div><!-- ./padding-md -->
@endsection
@section('moreScript')
    @if(\Auth::guard('admin')->user()->can('statistics'))
    <script src="http://cdn.bootcss.com/echarts/3.2.2/echarts.js"></script>
    <script>
        var queueFunc = function (func, time) {
            setTimeout(function () {
                func();
            }, time);
        };

        var charVisitFunc = function () {
            // 基于准备好的dom，初始化echarts实例
            var myVisitChart = echarts.init(document.getElementById('visit-charts'));

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '历月访问次数图表'
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    icon: 'roundRect',
                    data:[]
                },
                toolbox: {
                    feature: {
                        magicType: {
                            type: ['line', 'bar']
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : []
            };

            // 使用刚指定的配置项和数据显示图表。
            myVisitChart.setOption(option);

            $.get("{{ url('admin/data-before-times') }}").done(function (data) {
                var finalVisits = {};
                if (data.visits){
                    finalVisits = data.visits.map(function(val){
                        return finalVisits = {
                            name: val.name,
                            type:'line',
                            smooth:true,
                            data: val.data
                        };
                    });
                }
                // 填入数据
                myVisitChart.setOption({
                    xAxis: {
                        type : 'category',
                        boundaryGap : false,
                        data: data.months
                    },
                    dataZoom: [{
                        startValue: 0
                    }, {
                        type: 'inside'
                    }],
                    legend: {
                        data: data.legend
                    },
                    series: finalVisits
                });
            });
        };
        var charUserFunc = function () {
            // 基于准备好的dom，初始化echarts实例
            var myUserChart = echarts.init(document.getElementById('user-charts'));

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '历月访问人数图表'
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    icon: 'roundRect',
                    data:[]
                },
                toolbox: {
                    feature: {
                        magicType: {
                            type: ['line', 'bar']
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : []
            };

            // 使用刚指定的配置项和数据显示图表。
            myUserChart.setOption(option);

            $.get("{{ url('admin/user-before-times') }}").done(function (data) {
                var finalVisits = {};
                if (data.visits){
                    finalVisits = data.visits.map(function(val){
                        return finalVisits = {
                            name: val.name,
                            type:'line',
                            smooth:true,
                            data: val.data
                        };
                    });
                }
                // 填入数据
                myUserChart.setOption({
                    xAxis: {
                        type : 'category',
                        boundaryGap : false,
                        data: data.months
                    },
                    dataZoom: [{
                        startValue: 0
                    }, {
                        type: 'inside'
                    }],
                    legend: {
                        data: data.legend
                    },
                    series: finalVisits
                });
            });
        };
        var charNowVisitFunc = function () {
            // 基于准备好的dom，初始化echarts实例
            var myNowVisitChart = echarts.init(document.getElementById('now-visit-charts'));

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '当月访问次数图表'
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    icon: 'roundRect',
                    data:[]
                },
                toolbox: {
                    feature: {
                        magicType: {
                            type: ['line', 'bar']
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : []
            };

            // 使用刚指定的配置项和数据显示图表。
            myNowVisitChart.setOption(option);

            $.get("{{ url('admin/data-now-times') }}").done(function (data) {
                var finalVisits = {};
                if (data.visits){
                    finalVisits = data.visits.map(function(val){
                        return finalVisits = {
                            name: val.name,
                            type:'line',
                            smooth:true,
                            data: val.data
                        };
                    });
                }
                // 填入数据
                myNowVisitChart.setOption({
                    xAxis: {
                        type : 'category',
                        boundaryGap : false,
                        data: data.days
                    },
                    dataZoom: [{
                        startValue: 0
                    }, {
                        type: 'inside'
                    }],
                    legend: {
                        data: data.legend
                    },
                    series: finalVisits
                });
            });
        };
        var charNowUserFunc = function () {
            // 基于准备好的dom，初始化echarts实例
            var myNowUserChart = echarts.init(document.getElementById('now-user-charts'));

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '当月访问人数图表'
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    icon: 'roundRect',
                    data:[]
                },
                toolbox: {
                    feature: {
                        magicType: {
                            type: ['line', 'bar']
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : []
            };

            // 使用刚指定的配置项和数据显示图表。
            myNowUserChart.setOption(option);

            $.get("{{ url('admin/user-now-times') }}").done(function (data) {
                var finalVisits = {};
                if (data.visits){
                    finalVisits = data.visits.map(function(val){
                        return finalVisits = {
                            name: val.name,
                            type:'line',
                            smooth:true,
                            data: val.data
                        };
                    });
                }
                // 填入数据
                myNowUserChart.setOption({
                    xAxis: {
                        type : 'category',
                        boundaryGap : false,
                        data: data.days
                    },
                    dataZoom: [{
                        startValue: 0
                    }, {
                        type: 'inside'
                    }],
                    legend: {
                        data: data.legend
                    },
                    series: finalVisits
                });
            });
        };

        //访问次数
        queueFunc(charVisitFunc, 1000);
        //访问人数
        queueFunc(charUserFunc, 1500);
        //当月次数
        queueFunc(charNowVisitFunc, 1500);
        //当月人数
        queueFunc(charNowUserFunc, 1500);
    </script>
    @endif
@endsection