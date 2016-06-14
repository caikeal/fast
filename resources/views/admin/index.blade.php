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
            <div class="col-sm-6 text-right text-left-sm p-top-sm">
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Select Project <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li><a href="#">Project1</a></li>
                        <li><a href="#">Project2</a></li>
                        <li><a href="#">Project3</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Setting</a></li>
                    </ul>
                </div>

                <a class="btn btn-default"><i class="fa fa-cog"></i></a>
            </div>
        </div>

        <div class="row m-top-md">
            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-danger m-bottom-md">
                    <div class="statistic-title">
                        Today Visitors
                    </div>

                    <div class="statistic-value">
                        96.7k
                    </div>

                    <div class="m-top-md">11% Higher than last week</div>

                    <div class="statistic-icon-background">
                        <i class="ion-eye"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-info m-bottom-md">
                    <div class="statistic-title">
                        Today Sales
                    </div>

                    <div class="statistic-value">
                        751
                    </div>

                    <div class="m-top-md">8% Higher than last week</div>

                    <div class="statistic-icon-background">
                        <i class="ion-ios7-cart-outline"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-purple m-bottom-md">
                    <div class="statistic-title">
                        Today Users
                    </div>

                    <div class="statistic-value">
                        129
                    </div>

                    <div class="m-top-md">3% Higher than last week</div>

                    <div class="statistic-icon-background">
                        <i class="ion-person-add"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-success m-bottom-md">
                    <div class="statistic-title">
                        Today Earnings
                    </div>

                    <div class="statistic-value">
                        $124.45k
                    </div>

                    <div class="m-top-md">7% Higher than last week</div>

                    <div class="statistic-icon-background">
                        <i class="ion-stats-bars"></i>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- ./padding-md -->
@endsection
@section('moreScript')

@endsection