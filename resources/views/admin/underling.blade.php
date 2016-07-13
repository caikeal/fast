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
        .history-modal .modal-body-header {
            height: auto;
            padding: 24px 25px 24px 15px;
        }
        .history-modal .modal-body-header .search {
            width: 72px;
            height: 30px;
            line-height: 30px;
            float: left;
            text-align: center;
            margin-left: 20px;
            margin-top: 2px;
            font-size: 18px;
            color: #fff;
            background: #0092e4;
            border: 1px solid #0092e4;
            cursor: pointer;
            border-radius: 5px;
        }
        .history-modal .modal-body-header .search:hover,
        .history-modal .modal-body-header .search:active{
            background: #07a6ff;
            border: 1px solid #888888;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease ;
            -ms-transition: all 0.3s ease ;
            -o-transition: all 0.3s ease ;
            transition: all 0.3s ease ;
        }
        .history-modal .modal-body-header .search:active{
            border: 2px solid rgba(0,0,0,.125);
            box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
        }

        .history-modal .modal-body-header input{-webkit-border-radius: 20px;-moz-border-radius: 20px;border-radius: 20px;width: 465px;float: left;display: block;color: #474755;
            font-size: 14px;}
        .history-modal .modal-content {
            width: 600px;
        }
        .history-modal .modal-title {
            font-size: 18px;
            color: #383838;
            font-weight: 600;
        }
        .history-modal .modal-body {
            width: 100%;
            height: auto;
            padding: 0;
        }
        .history-modal .modal-footer {
            height: 85px;
            box-sizing: border-box;
            border-top:0;
        }
        .history-modal .modal-footer .history-button {
            width: 288px;
            height: 46px;
            line-height: 34px;
            text-align: center;
            margin: 3px auto;
            font-size: 22px;
            color: #fff;
            background: #0092e4;
            display: block;
        }
        .history-modal table {
            width: 100%;
            margin-bottom: 0;
            background: #fff;
        }
        .history-modal table thead tr{
            height: 30px;
            line-height: 30px;
            background: #edf2f4;
            color: #607b96;
        }
        .history-modal table th,td {
            text-align: left;
            /*border: 1px solid transparent;*/
        }
        .history-modal table tbody tr {
            height: 22px;
            line-height: 22px;
            color: #000;
        }
        .history-modal .table>thead>tr>th {
            border:0;
        }
        .history-modal .table>tbody>tr>td {
            border:0;
        }

        .table tbody tr:hover td, .table tbody tr:hover th {
            background-color: #f5f5f5;
        }

        .history-page{
            margin-bottom: 10px;
            margin-left: 15px;
        }

        .history-page select{
            display: inline-block;
            width: auto;
        }

        .history-page span{
            font-size: 14px;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="underling">
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
                                <see-details username="{{ $manager->name }}" :id={{ $manager->id }} ></see-details>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- /table  -->
            </div>
        </div>

        <!--history modal-->
        <div class="modal fade history-modal" id="underlingModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×
                        </button>
                        <h4 class="modal-title" id="myModalLabel" v-cloak>
                            @{{ name }}
                        </h4>
                    </div>
                    <div class="modal-body clearfix">
                        <div class="modal-body-header clearfix">
                            <input type="text" class="input-medium form-control" placeholder="企业名" v-model="company">
                            <div class="search" @click="searchUpload">
                            搜索
                            </div>
                        </div>
                        <div class="modal-body-context" style="overflow-x: auto;">
                        <section class="history-page">
                            <span>总共@{{ maxPage }}页</span>
                            <span>现</span>
                            <select name="pagination" class="form-control" v-model="page" @change="searchUpload">
                            <option v-for="pageItem in maxPage" :value="pageItem+1">@{{ pageItem+1 }}</option>
                            </select>
                            <span>页</span>
                        </section>
                        <table class="table table-striped">
                            <thead style="white-space: nowrap">
                            <tr>
                                <th>企业名</th>
                                <th>时间</th>
                                <th>类别</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="dataItem in allData" v-cloak>
                                <td>@{{ dataItem.company.name }}</td>
                                <td>@{{ dataItem.created_at }}</td>
                                <td>@{{ dataItem.type | uploadType}}</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <!--/history modal-->
    </div>
    <!--/pdadding-md-->

    {{--查看详情模版--}}
    <template id="details-template" style="display: none;">
        <span class="label label-success upload-details" data-toggle="modal" data-target="#underlingModal" @click="notify">查看详情</span>
    </template>
@endsection
@section('moreScript')
    <script>
        //侧边栏位置锁定
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place5").addClass("active");
        })($);
    </script>
    <script>
        Vue.filter('uploadType', function (val) {
            switch (val) {
                case 1:
                    return '代发工资';
                    break;
                case 2:
                    return '社保公积金';
                    break;
                case 3:
                    return '理赔进度';
                    break;
                case 4:
                    return '社保进度';
                    break;
            }
        });

        Vue.component('see-details',{
            template: '#details-template',
            props: {
               id: {
                   default: 0,
                   type: Number,
                   required: true
               },
                username: {
                   required: true,
                   default: ""
               }
            },
            methods: {
                notify: function () {
                    this.$dispatch('id', this.id);
                    this.$dispatch('name', this.username);
                    var _this = this;
                    var url = "{{ url('admin/underling') }}"+"/"+this.id;
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.data.length!=0) {
                            _this.$dispatch('all-data', data.data);
                            _this.$dispatch('page', data.current_page);
                            _this.$dispatch('max-page', data.last_page);
                        }
                    }).fail(function () {
                        alert("该记录已失效！");
                    });

                    return true;
                }
            }
        });

        new Vue({
            el: '#underling',
            data: {
                id: 0,
                allData: [],
                page: 0,
                maxPage: 0,
                from: "",
                to: "",
                company: "",
                name: ""
            },
            methods: {
                searchUpload: function () {
                    var _this = this;
                    var url = "{{ url('admin/underling') }}"+"/"+this.id;
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        data: {
                            company: _this.company,
                            page: _this.page,
                            from: _this.from,
                            to: _this.to
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        _this.allData = data.data;
                        _this.maxPage = Number(data.last_page);
                    }).fail(function () {
                        alert("该记录已失效！");
                    });

                    return true;
                },
            },
            events: {
                'id': function (id) {
                    this.id = id;
                },
                'all-data': function (allData) {
                    this.allData = allData;
                },
                'max-page': function (maxPage) {
                    this.maxPage = Number(maxPage);
                },
                'page': function (page) {
                    this.page = page;
                },
                'name': function (name) {
                    this.name = name;
                }
            }
        });
    </script>
@endsection