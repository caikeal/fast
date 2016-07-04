@extends('admin.app')
@section('moreCss')
    <style type="text/css">
        .base-backcolor {
            background-color: #fff;
        }
        .fast-table {
            box-shadow:0 0 1px #968C8C;
        }
        .fast-table th {
            height: 42px;
            line-height: 42px;
            color: #607b96;
            font-size: 14px;
            background: #edf2f4;
        }
        .fast-table th:first-child{
            padding-left: 40px;
        }
        .fast-table tbody tr td {
            vertical-align: middle;
            height: 60px;
            box-sizing: border-box;
            border-bottom: 1px solid #e5e5e5;
            border-top: 0;
        }
        .fast-table tbody tr td:first-child {
            border-bottom:0;
        }
        .fast-table tr:first-child {
            height: 42px;
            line-height: 42px;
        }
        .fast-table tr:first-child th{
            border: 0 solid transparent;
        }
        .fast-table tbody tr:last-child {
            height: 30px;
            border: 1px solid #e5e5e5;
        }
        .fast-table tr td {
            line-height: 60px;
            color: #425063;
            font-size: 14px;
        }
        .fast-table tbody tr td:first-child{
            padding-left: 40px;
        }

        .fast-table .label {
            cursor: pointer;
            width: 80px;
            height: 27px;
            line-height: 27px;
            display: inline-block;
            text-align: center;
        }
        .fast-table tbody tr{
            background-color: #fff !important;
        }
        .fast-table .check {
            width: 80px;
            height: 27px;
            line-height: 27px;
            display: block;
            margin-top: 16px;
            text-align: center;
            background: #40c148;
        }
        .fast-table .label-remind{
            cursor: default;
        }
        .pass{
            background: #0092e4;
        }
        .table tbody tr:hover td, .table tbody tr:hover th {
            background-color: #f5f5f5;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="news">
        <!-- 企业管理 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">消息中心</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 搜索企业 -->
        <div class="col-sm-12 col-md-12 col-lg-12" style="padding-left: 5px;">
            <h4>
                <div class="history-icon record-icon" style="background-color: #f1ceac;">
                </div>
                <span style="height: 20px;line-height: 30px;">最新历史记录</span></h4>
            <hr>
        </div>
        <!-- 历史记录表格 -->
        <div class="row" style="background-color: #fff;padding: 20px;box-sizing: border-box;">
            <!-- table -->
            <table class="fast-table table table-striped" id="dataTable">
                <thead>
                <tr>
                    <th></th>
                    <th style="width: 20%">时间</th>
                    <th style="width: 50%">内容</th>
                    <th style="width: 30%">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($news as $k=>$v)
                    <tr>
                        <td></td>
                        <td>{{ $v['created_at'] }}</td>
                        <td>
                            <a href="{{ url('admin/history') }}">
                            {{ $v['content'] }}
                            </a>
                        </td>
                        <td>
                            @if($v['type']==1)
                                @if($v['status']==1)
                                    <span class="label label-remind">已通过</span>
                                @elseif($v['status']==2)
                                    <span class="label label-remind">已拒绝</span>
                                @elseif($v['status']==4)
                                    <span class="label label-remind">已失效</span>
                                @elseif($v['status']==3)
                                    <span class="label label-success pass" @click="apply({{ $v['id'] }},1)">通过</span>
                                    <span class="label label-danger refuse" @click="apply({{ $v['id'] }},2)">拒绝</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                </tr>
                </tbody>
            </table>
            <!-- /table  -->
            <div style="float: right">
                {!! $news->links() !!}
            </div>
        </div>
    </div>
    <!--/pdadding-md-->
@endsection
@section('moreScript')
    <script>
    new Vue({
        el: '#news',
        data: '',
        methods: {
            apply: function (news,val) {
                $.ajax("{{ url('admin/news') }}/"+news, {
                    type: 'post',
                    dataType: 'json',
                    timeout: '120000',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method: 'PUT',
                        st: val
                    }
                }).done(function (data) {
                    if (data.ret_num==0) {
                        alert(data.ret_msg);
                    } else {
                        alert("网络错误！");
                    }
                }).fail(function (error) {
                    if (error.responseJSON.invalid){
                        alert(error.responseJSON.invalid);
                    }else if(error.responseJSON.error){
                        alert(error.responseJSON.error);
                    }else{
                        alert("网络错误！");
                    }
                });
            }
        }
    });
    </script>
@endsection