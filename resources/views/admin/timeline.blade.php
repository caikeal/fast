@extends('admin.app')
@section('moreCss')
    <link rel="stylesheet" href="{{env("APP_URL")}}/css/admin/webuploader.css">
@endsection
@section('content')
    <div class="padding-md" id="type" data-type="1">
        <h2 class="header-text">
            工资上传
						<span class="sub-header">
							{{--19 Updates--}}
						</span>
        </h2>

        <div class="row">
            <div class="col-md-10">
                <div class="timeline-wrapper clearfix">
                    <div class="timeline-year">
                        {{$now->year.".".$now->month}}
                    </div>
                    @foreach($tasks as $k=>$task)
                        @if($task->deal_time<$nextMonthTime)
                            <div class="timeline-row alt">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                    </div><!-- ./timeline-icon -->
                                    <div class="timeline-item-inner">
                                        <div class="timeline-body">
                                            <div class="timeline-avatar">
                                                <img src="{{env("APP_URL")}}/{{$task->company->poster}}" alt="" class="img-circle">
                                            </div>
                                            <div class="timeline-content">
                                                <div class="font-16 font-semi-bold"><a
                                                            href="#">{{$task->company->name}}</a></div>
                                                <small class="block text-muted m-bottom-xs">{{date("Y-m-d",$task->deal_time)}}</small>
                                            </div>
                                            <div class="timeline-ctrl">
                                                @if($task->status==0)
                                                    <div id="uploader{{$k}}" class="upload-ctrl"
                                                         data-task="{{$task->id}}">
                                                        <!--用来存放文件信息-->
                                                        <div id="thelist{{$k}}" class="uploader-list"></div>
                                                        <div class="btns">
                                                            <div id="picker{{$k}}">选择文件</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="webuploader-container upload-ctrl">
                                                        <div class="webuploader-pick webuploader-pick-disable">
                                                            <i class="fa fa-cloud-upload"></i> 上传
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-5 col-md-5 col-sm-5 timeline-select">
                                                    <select name="c{{$task->company_id}}" class="form-control">
                                                        @foreach($task->salaryModels()->where("type",1)->get() as $salaryModel)
                                                            <option value="{{$salaryModel->id}}">{{$salaryModel->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <a class="btn btn-success timeline-btn download-base"
                                                   data-company="c{{$task->company_id}}">下载模版
                                                </a>

                                                <div class="timeline-new" data-company="c{{$task->company_id}}"><i
                                                            class="fa fa-plus-circle"></i> 新建模版
                                                </div>
                                            </div>
                                        </div><!-- ./timeline-body -->
                                    </div><!-- ./timeline-item-inner -->
                                </div><!-- ./timeline-item -->
                            </div><!-- ./timeline-row -->
                        @elseif(($k-1>=0) && $task->deal_time>=$nextMonthTime && $tasks[$k-1]['deal_time']<$nextMonthTime)
                            <div class="timeline-year bg-purple">
                                {{$next->year.".".$next->month}}
                            </div>
                        @endif
                        @if($task->deal_time>=$nextMonthTime)
                            <div class="timeline-row alt">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                    </div><!-- ./timeline-icon -->
                                    <div class="timeline-item-inner">
                                        <div class="timeline-body">
                                            <div class="timeline-avatar">
                                                <img src="{{env("APP_URL")}}/{{$task->company->poster}}" alt="" class="img-circle">
                                            </div>
                                            <div class="timeline-content">
                                                <div class="font-16 font-semi-bold"><a
                                                            href="#">{{$task->company->name}}</a></div>
                                                <small class="block text-muted m-bottom-xs">{{date("Y-m-d",$task->deal_time)}}</small>
                                            </div>
                                            <div class="timeline-ctrl">
                                                @if($task->status==0)
                                                    <div id="uploader{{$k}}" class="upload-ctrl"
                                                         data-task="{{$task->id}}">
                                                        <!--用来存放文件信息-->
                                                        <div id="thelist{{$k}}" class="uploader-list"></div>
                                                        <div class="btns">
                                                            <div id="picker{{$k}}">选择文件</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="webuploader-container upload-ctrl">
                                                        <div class="webuploader-pick webuploader-pick-disable">
                                                            <i class="fa fa-cloud-upload"></i> 上传
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-5 col-md-5 col-sm-5 timeline-select">
                                                    <select name="c{{$task->company_id}}" class="form-control">
                                                        @foreach($task->salaryModels()->where("type",1)->get() as $salaryModel2)
                                                            <option value="{{$salaryModel2->id}}">{{$salaryModel2->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="btn btn-success timeline-btn download-base"
                                                     data-company="c{{$task->company_id}}">下载模版
                                                </div>
                                                <div class="timeline-new" data-company="c{{$task->company_id}}"><i
                                                            class="fa fa-plus-circle"></i> 新建模版
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ./timeline-body -->
                                    </div>
                                    <!-- ./timeline-item-inner -->
                                </div>
                                <!-- ./timeline-item -->
                            </div>
                            <!-- ./timeline-row -->
                        @endif
                    @endforeach
                </div>
                <!-- ./timeline-wrapper -->
            </div>
            <!-- ./col -->
        </div>
        <!-- ./row -->
    </div>
    <!-- ./padding-md -->
    @endsection
    @section('addition')
            <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        新建模板
                    </h4>
                </div>
                <div class="modal-body">
                    {{--@if($errors->any())--}}
                    {{--<ul class="list-group">--}}
                    {{--@foreach($errors->all() as $error)--}}
                    {{--<li class="list-group-item list-group-item-danger">{{$error}}</li>--}}
                    {{--@endforeach--}}
                    {{--</ul>--}}
                    {{--@endif--}}
                    <form class="bs-example bs-example-form" role="form" action="{{url('admin/salary/base')}}"
                          method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="cid" value="">
                        <input type="hidden" name="type" value="1">

                        <div class="modle-form">
                            <div class="row creatCategroy">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="createBigCategory">
                                   <span class="input-group-btn">
                                      <button class="btn btn-primary" type="button" id="createBig">
                                          大类选项创建
                                      </button>
                                   </span>
                                    </div><!-- /input-group -->
                                </div><!-- /.col-lg-12 -->

                                <div class="clearfix" style="margin: 10px 0;"></div>

                                <div class="col-lg-12">
                                    <div class="input-group create">
                                        <input type="text" class="form-control" id="littleCategory">
                                   <span class="input-group-btn">
                                      <button class="btn btn-primary" type="button" id="createLittle">
                                          小类选项创建
                                      </button>
                                   </span>
                                    </div><!-- /input-group -->
                                </div><!-- /.col-lg-12 -->
                            </div><!-- /.row -->

                            <div class="clearfix" style="margin: 10px 0;"></div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <input class="form-control" type="text" name="title" placeholder="模版标题">
                                </div>
                            </div>
                            <br>

                            <div>
                                    <span class="help-block" style="color: red">
                                        <span style="font-weight: 800">*</span>
                                        模版中自动包含
                                        <span style="font-weight: 800">'姓名'、'身份证'、'发薪日'</span>，
                                        <span style="font-weight: 800"> 请勿</span>
                                        再次
                                        <span style="font-weight: 800">创建</span>
                                    </span>
                            </div>
                            <div class="row addCategory well">
                                <div style="margin-top: 15px;">
                                    <lagre class="text-muted">选择大类:</lagre>
                                    <select class="bigCategorySelect text-muted" name="category[]">
                                    </select>
                                    <a class="addSmall text-muted" type="button"
                                       style="margin-left: 25px;color: #0BC10E;cursor: pointer;"><i
                                                class="fa fa-plus-circle"></i> 新增小类</a>
                                </div>

                                <div class="addsmallSelect" style="margin-top: 15px;margin-left: 1px;">
                                    <small class="text-muted">选择小类:</small>
                                    <select class="smallCategorySelect text-muted" name="category[]"
                                            style="margin-left: 4px;">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" style="padding: 10px 100px 10px;">
                            <h4><a id="addBig" type="button" style="color: #0BC10E;cursor: pointer;"><i
                                            class="fa fa-plus-circle"></i> 新增分组</a></h4>
                        </div>
                        <div class="modal-footer" style="text-align: center;">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">关闭
                                </button>
                                <button id="saveModle" type="submit" class="btn btn-primary">
                                    保存模板
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal -->
@endsection
@section('moreScript')
    //侧边栏位置锁定
    <script>
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place2").addClass("active");
        })($);
    </script>
    //文件上传
    <script src="{{env('APP_URL')}}/js/webuploader-0.1.5/webuploader.js"></script>
    <script>
        // 文件接收服务端。
        $.extend(WebUploader.Uploader.options, {
            server: "{{url('admin/salary/upload')}}"
        });
    </script>
    <script src="{{env('APP_URL')}}/js/admin/upload.js"></script>
    <script type="text/javascript">
        //下载
        $(".download-base").on("click", function () {
            var bid = $(this).prev().children("select").val();
            if (bid == null) {
                alert("未选择模版！");
                return false;
            }
            var url = "{{url('admin/salary/download')}}?bid=" + bid;
//            console.log(url);
            window.location.href = "{{url('admin/salary/download')}}?bid=" + bid;
        });

        //起吊模态框
        $(".timeline-new").click(function () {
            var url = "{{url('admin/salary/category')}}";
            var cp = $(this).attr("data-company").substr(1);
            var bi = $(this).prevAll(".timeline-select").children("[name=c" + cp + "]").val();
            $("[name='cid']").val(cp);
            $(".bigCategorySelect").each(function (index, ele) {
                if (index > 0) {
                    $(ele).parent().parent(".addCategory").remove();
                }
            });
            $(".smallCategorySelect").each(function (index, ele) {
                if (index > 0) {
                    $(ele).remove();
                }
            });
            $(".bigCategorySelect >option").remove();
            $(".smallCategorySelect >option").remove();
            $.ajax(url, {
                type: 'get',
                dataType: 'json',
                timeout: '120000',
                data: {cid: cp, bid: bi, type: 1},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (data) {
                if (data.status) {
                    if (data.big.length) {
                        for (var i = 0; i < (data.big).length; i++) {
                            $(".bigCategorySelect").append("<option value='" + data.big[i].id + "'>" + data.big[i].name + "</option>");
                        }
                    }
                    if (data.small.length) {
                        for (var j = 0; j < (data.small).length; j++) {
                            $(".smallCategorySelect").append("<option value='" + data.small[j].id + "'>" + data.small[j].name + "</option>");
                        }
                    }
                    $('#myModal').modal();
                } else {
                    alert("网络错误！");
                }
            }).fail(function () {
                alert("网络错误！");
            }).complete();
        });

        //创建大类选项
        $("#createBig").click(function () {
            var bigCategory = $("#createBigCategory").val().trim();
            if (bigCategory != "") {
                //发送post请求给后台
                url = "{{url('admin/salary/category')}}";
                $.ajax(url, {
                    type: 'post',
                    dataType: 'json',
                    timeout: '120000',
                    data: {name: bigCategory, level: 1, type: 1},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function (data) {
                    if (data.status) {
                        $(".bigCategorySelect ").append("<option value='" + data.cid + "'>" + bigCategory + "</option>");
                        $("#createBigCategory").val("");
                    } else {
                        alert("网络错误！");
                    }
                }).fail(function () {
                    alert("网络错误！");
                }).complete();
            } else {
                alert("请勿添加空白！");
            }
        });

        //点击创建小分类选项按钮
        $("#createLittle").click(function () {
            var littleCategory = $("#littleCategory").val().trim();
            if (littleCategory != "") {
                //发送post请求给后台
                url = "{{url('admin/salary/category')}}";
                $.ajax(url, {
                    type: 'post',
                    dataType: 'json',
                    timeout: '120000',
                    data: {name: littleCategory, level: 2, type: 1},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function (data) {
                    if (data.status) {
                        $(".smallCategorySelect").append("<option value='" + data.cid + "'>" + littleCategory + "</option>");
                        $("#littleCategory").val("");
                    } else {
                        alert("网络错误！");
                    }
                }).fail(function () {
                    alert("网络错误！");
                }).complete();
            } else {
                alert("请勿添加空白！");
            }
        });

        //点击添加大分类按钮
        $("#addBig").on("click", function () {
            var bigThis = $(this);
            $(".modle-form").append($(".addCategory:first").clone());
            //给新增的添加绑定事件
            $(".addSmall").last().on("click", function () {
                var _this = $(this);
                _this.next().append($(".smallCategorySelect:first").clone());
            });
            //删除多余的smallCateforySelect
            $(".addCategory").last().find(".smallCategorySelect:first").nextAll().remove();
        });

        //点击添加小分类按钮
        $(".modal-content").on("click", ".addSmall", function () {
            var _this = $(this);
            _this.parent().next().append($(".smallCategorySelect:first").clone());
        });
    </script>
@endsection