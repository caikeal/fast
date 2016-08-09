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

    </div><!-- ./padding-md -->
@endsection
@section('moreScript')

@endsection