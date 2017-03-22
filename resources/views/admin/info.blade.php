@extends('admin.app')
@section('moreCss')
    <style>
        .base-backcolor {
            background-color: #fff;
        }

        .poster-icon.icon-result-color {
            background-color: #F1CEAC;
        }

        .circle-check {
            margin-left: 3px;
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

        .table>tbody>tr>td{
            vertical-align: middle;
        }

        .table>thead>tr{
            background: #edf2f4;
            color: #607b96;
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

        .send-info{
            cursor: pointer;
            color: #00af00;
            padding: 10px 10px 0;
            margin-right: 8px;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="systemInfo">
        <!-- 系统通知消息 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">系统通知消息</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <div class="row base-backcolor">
            <div class="pull-right">
                <div class="send-info" data-toggle="modal" data-target="#sendInfoModal">发送消息</div>
            </div>
        </div>
        <div class="row base-backcolor page-group">
            <div class="pull-right">
                {!! $infos->links() !!}
            </div>
        </div>
        <!-- 历史记录表格 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <!-- table -->
                <table class="table table-striped" id="dataTable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>标题</th>
                        <th>内容</th>
                        <th>图片</th>
                        <th>显示情况</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr v-if="additionList.length" v-for="addItem in additionList" v-cloak>
                            <td>@{{ addItem['id'] }}</td>
                            <td>@{{ addItem['title'] }}</td>
                            <td>@{{ addItem['content'] }}</td>
                            <td>
                                <img :src="addItem['img']" width="100" v-if="addItem['img']">
                            </td>
                            <td>展示</td>
                            <td>
                                <a class="btn btn-warning" @click="closeInfo(addItem['id'])">关闭</a>
                                <a class="btn btn-danger" @click="deleteInfo(addItem['id'])">删除</a>
                            </td>
                        </tr>
                    @foreach($infos as $k=>$info)
                        <tr>
                            <td>{{ $k+1 }}</td>
                            <td>{{ $info->title }}</td>
                            <td>{{ $info->p }}</td>
                            <td>
                                @if($info->img)
                                    <img src="{{url($info->img)}}" width="100">
                                @endif
                            </td>
                            <td>{{ $info->is_show==1 ? '展示' : '未展示' }}</td>
                            <td>
                                @if($info->is_show==1)
                                    <a class="btn btn-warning" @click="closeInfo({{$info->id}})">关闭</a>
                                @else
                                    <a class="btn btn-success" @click="openInfo({{$info->id}})">开启</a>
                                @endif
                                <a class="btn btn-danger" @click="deleteInfo({{$info->id}})">删除</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- /table  -->
            </div>
        </div>

        <!--send info modal-->
        <div class="modal fade info-modal" id="sendInfoModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×
                        </button>
                        <h4 class="modal-title" id="myModalLabel" v-cloak>
                            创建消息
                        </h4>
                    </div>
                    <div class="modal-body clearfix">
                        <form class="form-horizontal">
                            <div class="form-group" :class="[addInfoErr.title.err?'has-error':'']">
                                <label class="col-md-3 control-label">
                                    <span class="text-danger">*</span>标题
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="addInfo.title">
                                    <span class="help-block" v-show="addInfoErr.title.err">@{{ addInfoErr.title.msg }}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="[addInfoErr.content.err?'has-error':'']">
                                <label class="col-md-3 control-label">
                                    <span class="text-danger">*</span>内容
                                </label>
                                <div class="col-md-9">
                                    <textarea rows="5" class="form-control" v-model="addInfo.content"></textarea>
                                    <span class="help-block" v-show="addInfoErr.content.err">@{{ addInfoErr.content.msg }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">
                                    <span class="text-danger">*</span>图片
                                </label>
                                <div class="col-md-9">
                                    <input id="addUpload" type="file" accept="image/png,image/gif,image/jpeg" class="form-control" @change="getFile">
                                    <span class="help-block" style="display: none;"></span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block m-top-md" @click="saveInfo">创建</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('moreScript')
    {{--侧边栏位置锁定--}}
    <script>
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place11").addClass("active");
        })($);
    </script>
    <script>
        new Vue({
            el: '#systemInfo',
            data: {
                addInfo: {
                    title: '',
                    content: '',
                    img: ''
                },
                addInfoErr: {
                    title: {
                        err: false,
                        msg: ''
                    },
                    content: {
                        err: false,
                        msg: ''
                    }
                },
                additionList: []
            },
            methods: {
                getFile: function (e) {
                    this.addInfo.img = e.target.files.length?e.target.files[0]:null;
                },
                clearAddInfo: function () {
                    this.addInfo = {
                        title: '',
                        content: '',
                        img: ''
                    };
                    $('#addUpload')[0].value = null;
                },
                clearAddInfoErr: function () {
                    this.addInfoErr = {
                        title: {
                            err: false,
                            msg: ''
                        },
                        content: {
                            err: false,
                            msg: ''
                        }
                    };
                },
                saveInfo: function () {
                    var _this = this;
                    var url = "{{ url('admin/system/info/create') }}";
                    this.clearAddInfoErr(); // 预先清除上次错误
                    if (!this.addInfo.title) {
                        this.addInfoErr.title.err = true;
                        this.addInfoErr.title.msg = '标题必填';
                        return false;
                    }
                    if (!this.addInfo.content) {
                        this.addInfoErr.content.err = true;
                        this.addInfoErr.content.msg = '内容必填';
                        return false;
                    }
                    var params = new FormData;
                    params.append('title', this.addInfo.title);
                    params.append('content', this.addInfo.content);
                    params.append('img', this.addInfo.img);
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        data: params,
                        processData: false,
                        contentType: false,
                        type:'POST'
                    }).done(function (data) {
                        alert("保存成功！");
                        _this.additionList.push({
                            id: data.ret_msg.id,
                            title: data.ret_msg.title,
                            content: data.ret_msg.p,
                            img: data.ret_msg.img,
                        });
                        $("#sendInfoModal").modal('hide');
                        _this.clearAddInfo();
                    }).fail(function () {
                        alert('保存失败！');
                    });
                },
                deleteInfo: function (id) {
                    var url = "{{ url('admin/system/info/delete') }}/"+id;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        type:'POST'
                    }).done(function (data) {
                        alert("修改成功！");
                        window.location.reload();
                    }).fail(function () {
                        alert('修改失败！');
                    });
                },
                closeInfo: function (id) {
                    var url = "{{ url('admin/system/info/close') }}/"+id;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        type:'POST'
                    }).done(function (data) {
                        alert("修改成功！");
                        window.location.reload();
                    }).fail(function () {
                        alert('修改失败！');
                    });
                },
                openInfo: function (id) {
                    var url = "{{ url('admin/system/info/open') }}/"+id;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        type:'POST'
                    }).done(function (data) {
                        alert("修改成功！");
                        window.location.reload();
                    }).fail(function () {
                        alert('修改失败！');
                    });
                }
            }
        });
    </script>
@endsection