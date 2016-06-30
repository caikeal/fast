@extends('admin.app')
@section('moreCss')
    <style>
        .base-backcolor {
            background-color: #fff;
        }

        .form-search {
            margin-top: 1rem;
        }

        .poster-icon {
            border-radius: 2.5rem;
            line-height: 2.5rem;
            text-align: center;
            color: #fff;
            width: 2.5rem;
        }

        .poster-icon.icon-search-color {
            background-color: #ACCEF1;
        }

        .poster-icon.icon-result-color {
            background-color: #F1CEAC;
        }

        .circle-check {
            margin-left: 3px;
        }

        input[type=text].search-query {
            border-radius: 2.5rem;
            width: 70%;
            margin-left: 3.5rem
        }

        .result-head {
            margin-left: 3.5rem;
            line-height: 2.5rem;
            font-weight: 600;
        }

        .page-group {
            padding-bottom: 1rem;
        }

        .line {
            margin-top: .1rem;
        }

        .input-group .none-left-border {
            border-left: 0;
            border-color: #ccc;
        }

        .col-xs-2.lable-xs-center {
            padding-top: 7px;
        }

        .new-list{
            background-color: #D7FFD0;
            color: #0006FF;
        }

        .table>tbody>tr>td{
            vertical-align: middle;
        }

        .table>thead>tr{
            background: #edf2f4;
            color: #607b96;
        }

        .upload-details{
            cursor: pointer;
            width: 80px;
            height: 27px;
            line-height: 27px;
            display: block;
            text-align: center;
            background: #0092e4;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="employ">
        {{--<div class="alert alert-danger alert-dismissible" role="alert" v-if="systemErrors">--}}
        {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
        {{--<strong>Warning!</strong> @{{systemErrors}}--}}
        {{--</div>--}}
                <!-- 人员情况总览 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">人员情况总览</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 快速搜索下属 -->
        <div class="row base-backcolor" style="padding-bottom: 10px;">
            <form class="form-search" action="{{url('admin/employ')}}" method="get">
                {{csrf_field()}}
                <div class="col-xs-9 col-sm-10 col-md-10 col-lg-10">
                    <div class="pull-left poster-icon icon-search-color">
                        <i class="fa fa-search fa-lg poster-icon-height"></i>
                    </div>
                    <input type="text" class="input-medium form-control search-query" placeholder="快速搜索客服姓名"
                           name="name" value="{{$name}}" autocomplete="off">
                </div>
                <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
                    <input type="submit" class="btn btn-primary btn-block" value="搜索">
                </div>
                <div class="clearfix"></div>
            </form>
        </div>

        <div class="seperator"></div>
        <div class="row base-backcolor">
            <div class="seperator"></div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="pull-left poster-icon icon-result-color">
                    <i class="fa fa-users fa-lg circle-check poster-icon-height"></i>
                </div>
                <p class="result-head">下属总览:</p>
            </div>
        </div>
        <div class="row base-backcolor page-group">
            <div class="pull-right">
                {!! $managers->appends(['name'=>$name])->links() !!}
            </div>
        </div>
        <!-- 历史记录表格 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <!-- table -->
                <table class="table table-striped" id="dataTable">
                    <thead>
                    <tr>
                        <th>姓名</th>
                        <th>上级</th>
                        <th>服务企业数</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    @foreach($managers as $manager)
                        <tr>
                            <td>{{ $manager->name }}</td>
                            <td>{{ $manager->leader->name }}</td>
                            <td>
                                {{ count($manager->tasks) }}
                            </td>
                            <td>{{ $manager->updated_at }}</td>
                            <td>
                                <span class="label label-success upload-details">查看详情</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- /table  -->
            </div>
        </div>

        <!-- modal createManager-->
        <div class="modal fade" id="create-manager" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">创建账号</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':is_managerName}">
                                    <label for="name" class="col-lg-2 control-label lable-xs-center">姓名:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="姓名"
                                               v-model="managerName">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_managerName?'block':'none'}">@{{ managerNameErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_managerAccount}">
                                    <label for="account" class="col-lg-2 control-label lable-xs-center">账号:</label>

                                    <div class=" col-lg-10">
                                        <input type="email" class="form-control" id="account" name="account"
                                               placeholder="账号" v-model="managerAccount">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_managerAccount?'block':'none'}">@{{ managerAccountErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_managerPassword}">
                                    <label for="password" class="col-lg-2 control-label lable-xs-center">密码:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" id="password" name="password"
                                               placeholder="密码" v-model="managerPassword">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_managerPassword?'block':'none'}">@{{ managerPasswordErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_managerRoles}">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        @foreach($memberRoles as $memberRole)
                                            <div class="checkbox inline-block">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="{{$memberRole->name}}" class="checkbox-blue" value={{$memberRole->id}}
                                                            v-model="managerRoles">
                                                    <label for="{{$memberRole->name}}"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    {{$memberRole->label}}
                                                </div>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                        @endforeach
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_managerRoles?'block':'none'}">@{{ managerRolesErrors }}</p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block m-top-md" @click="newAccount">创建账号</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal createManager-->
    </div>
    <!--/pdadding-md-->
@endsection
@section('addition')
@endsection
@section('moreScript')
    <script>
        //侧边栏位置锁定
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place5").addClass("active");
        })($);
    </script>
@endsection