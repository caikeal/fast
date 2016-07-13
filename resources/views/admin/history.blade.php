@extends('admin.app')
@section('moreCss')
    <link href="{{env('APP_URL')}}/css/admin/history/history.css" rel="stylesheet">
    <link href="{{env('APP_URL')}}/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{env("APP_URL")}}/css/admin/webuploader.css">
    <style type="text/css">
        .webuploader-pick{
            background: inherit;
        }
        .webuploader-pick-hover{
            background: inherit;
        }
        .fast-table .reload.sure-reupload{
            margin-top: 6px;
            margin-left: -15px;
            background: #FF9800;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="history">
        <!-- 企业管理 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">历史查询</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 搜索企业 -->
        <div class="row clearfix"
             style="background-color: #fff;margin-top: .1rem;padding: 20px;box-sizing: border-box;">
            <form class="form-search clearfix" action="" method="get">
                <section class="search-box">
                    <div class="history-detail">
                        <div class="history-icon mag-icon" style="background-color: #accef1;">
                        </div>
                        <input type="text" class="input-medium search-query form-control" placeholder="企业 / 组织名称"
                           name="company" style="width: 420px;float: left;" value="{{ $company }}">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="float: left;">搜索</button>
                    </div>

                    <div class="clearfix"></div>
                </section>

                <div class="clearfix date-row">
                    <div class="history-icon alarm-clock-icon" style="background-color: #f1e5ac;">
                    </div>
                    <input type="text" name="from" class="input-edit fl datepicker-history form_datetime"
                       placeholder="开始时间" value="{{ $from }}">
                    <span class="fl history-date-span">----</span>
                    <input type="text" name="to" class="input-edit fl datepicker-history form_datetime"
                       placeholder="结束时间" value="{{ $to }}">
                    <span class="fl history-date-span2">选择一个时间区间</span>
                </div>
            </form>
        </div>
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
                    <th style="width: 40%">企业</th>
                    <th style="width: 20%">上传时间</th>
                    <th style="width: 10%">类别</th>
                    <th style="width: 15%">操作</th>
                    <th style="width: 15%">修改</th>
                </tr>
                </thead>
                <tbody>
                <?php $countUp=0;?>
                @foreach($uploads as $k=>$v)
                    <tr>
                        <td></td>
                        <td><img src="{{ $v['company']['poster'] ? env('APP_URL') .'/'. $v['company']['poster'] : env('APP_URL').'/images/fast_company.png' }}" class="thumbnail thumbnail-radius">
                            <span style="text-align: center;margin: 20px 0 0 20px; ">{{ $v['company']['name'] }}</span>
                        </td>
                        <td>{{ $v['created_at'] }}</td>
                        <td>
                            @if($v['type']==1)
                                薪资上传
                            @elseif($v['type']==2)
                                社保上传
                            @elseif($v['type']==3)
                                理赔上传
                            @elseif($v['type']==4)
                                社保进度上传
                            @endif
                        </td>
                        <td>
                            <see-details upload-id="{{ $v['id'] }}" company-name="{{ $v['company']['name'] }}"></see-details>
                        </td>
                        <td>
                            @if($v['type']!=1 && $v['type']!=2)
                            @elseif($roleLevel==1 && ($v['type']==1 || $v['type']==2))
                                <div id="uploader{{ $countUp }}" class="upload-ctrl"
                                     data-upload={{ $v['id'] }} data-type={{ $v['type'] }} data-company={{ $v['company']['id'] }} data-reupload=0>
                                    <!--用来存放文件信息-->
                                    <div id="thelist{{ $countUp }}" class="uploader-list"></div>
                                    <div class="btns">
                                        <div id="picker{{ $countUp }}">选择文件</div>
                                    </div>
                                </div>
                                <?php $countUp++;?>
                            @elseif(count($v['application']) && $v['application']['0']['status']==1)
                                <div id="uploader{{ $countUp }}" class="upload-ctrl"
                                     data-upload={{ $v['id'] }} data-type={{ $v['type'] }} data-company={{ $v['company']['id'] }} data-reupload={{ $v['application']['0']['id'] }}>
                                    <!--用来存放文件信息-->
                                    <div id="thelist{{ $countUp }}" class="uploader-list"></div>
                                    <div class="btns">
                                        <div id="picker{{ $countUp }}">选择文件</div>
                                    </div>
                                </div>
                                <?php $countUp++;?>
                            @else
                                <span class="label label-success reload"  @click="reuploadApply({{ $v['id'] }})">申请上传</span>
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
                {!! $uploads->appends(['company' => $company, 'from' => $from, 'to' => $to])->links() !!}
            </div>
        </div>

        <!--history modal-->
        <div class="modal fade history-modal" id="historyModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×
                        </button>
                        <h4 class="modal-title" id="myModalLabel" v-cloak>
                            @{{ companyName }}
                        </h4>
                    </div>
                    <div class="modal-body clearfix">
                        <div class="modal-body-header clearfix">
                            <input type="text" class="input-medium search-query form-control" placeholder="姓名 / 身份证号" v-model="name">
                            <div class="search" @click="searchUpload">
                                搜索
                            </div>
                        </div>
                        <div class="modal-body-context" style="overflow-x: auto;">
                            <section class="history-page">
                                <span>总共@{{ maxPage }}页</span>
                                <span>现</span>
                                <select name="pagination" class="form-control" v-model="p" @change="searchUpload">
                                    <option v-for="pageItem in maxPage" :value="pageItem+1">@{{ pageItem+1 }}</option>
                                </select>
                                <span>页</span>
                            </section>
                            <table class="table table-striped">
                                <thead style="white-space: nowrap">
                                <tr v-cloak>
                                    <th v-for="headItem in head" track-by="$index">@{{ headItem }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="dataItem in allData" v-cloak>
                                    <td v-for="data in dataItem" track-by="$index">@{{ data }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary history-button" @click="historyDownload">
                            下载
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--/history modal-->
    </div>
    <!--/pdadding-md-->

    {{--查看详情模版--}}
    <template id="details-template" style="display: none;">
        <span class="label label-success check" data-toggle="modal" data-target="#historyModal" @click="notify">查看</span>
    </template>


@endsection
@section('moreScript')
    {{--侧边栏位置锁定--}}
    <script>
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place4").addClass("active");
        })($);
    </script>
    <script src="http://7xqxb2.com2.z0.glb.qiniucdn.com/webuploader.min.js"></script>
    <script>
        // 文件接收服务端。
        $.extend(WebUploader.Uploader.options, {
            server: "{{url('admin/history/reupload')}}"
        });
    </script>
    <script src="{{env('APP_URL')}}/js/admin/reupload.js"></script>
    <script src="{{env('APP_URL')}}/js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{env('APP_URL')}}/js/bootstrap-datetimepicker.zh-CN.js"></script>
    <script>
        //日历
        $('.form_datetime').datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true,
            todayBtn: true,
            minView: 0
        });
    </script>
    <script>
        Vue.component('see-details',{
            template: '#details-template',
            props: {
                uploadId: 0,
                companyName: ""
            },
            methods: {
                notify: function () {
                    this.$dispatch('upload-id', this.uploadId);
                    var _this = this;
                    var url = "{{ url('admin/history') }}"+"/"+this.uploadId;
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.ret_num==0) {
                            _this.$dispatch('all-data', data.data);
                            _this.$dispatch('head', data.head);
                            _this.$dispatch('max-page', data.max_page);
                            _this.$dispatch('company-name', _this.companyName);
                        } else {
                            alert("无法读取该条数据！");
                        }
                    }).fail(function () {
                        alert("该记录已失效！");
                    });

                    return true;
                }
            }
        });

        new Vue({
            el: '#history',
            data: {
                uploadId: 0,
                allData: [],
                head: [],
                maxPage: 0,
                name: "",
                companyName: "",
                p: 0
            },
            methods: {
                searchUpload: function () {
                    var _this = this;
                    var url = "{{ url('admin/history') }}"+"/"+this.uploadId;
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        data: {
                            name: _this.name,
                            page: _this.p
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.ret_num==0) {
                            _this.allData = data.data;
                            _this.head = data.head;
                            _this.maxPage = Number(data.max_page);
                        } else {
                            alert("无法读取该条数据！");
                        }
                    }).fail(function () {
                        alert("该记录已失效！");
                    });

                    return true;
                },

                historyDownload: function () {
                    var _this = this;
                    var url = "{{ url('admin/history/download') }}"+"?upload_id="+_this.uploadId;
                    window.open(url);
                    return true;
                },

                reuploadApply: function (tplId) {
                    var _this = this;
                    var url = "{{ url('admin/task_application') }}";
                    var confirm = window.confirm("您确定要重新上传吗？");
                    if (!confirm){
                        return false;
                    }
                    $.ajax(url, {
                        type: 'post',
                        dataType: 'json',
                        timeout: '120000',
                        data: {
                            upload_id: tplId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.ret_num==0) {
                            alert(data.ret_msg);
                        } else {
                            alert(data.ret_msg);
                        }
                    }).fail(function (error) {
                        alert(error.responseJSON.invalid);
                    });

                    return true;
                }
            },
            events: {
                'upload-id': function (uploadId) {
                    this.uploadId = uploadId;
                    this.name = "";
                },
                'all-data': function (allData) {
                    this.allData = allData;
                },
                'head': function (head) {
                    this.head = head;
                },
                'max-page': function (maxPage) {
                    this.maxPage = Number(maxPage);
                    this.p = Number(maxPage);
                },
                'company-name': function (companyName) {
                    this.companyName = companyName;
                }
            }
        });
    </script>
@endsection