@extends('admin.app')
@section('moreCss')
    <style type="text/css">
        body{font-family: -webkit-pictograph}
        .bg-default{background-color: #f1f5f6}
        .title{border:1px;background-color: #fff;margin-top: .1rem;}
        .statistic{border:1px;background-color: #fff;margin-top: 1rem;}
        .question-list{margin-left: 1rem;margin-right: 1rem;}
        .btn.btn-answer{padding: 6px 22px;}
        .ask-info,.answer-info{
            border: 1px solid #e2e2e2;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 5px rgba(148, 152, 154, 0.3);
            border-radius: 3px;
            padding: 15px;
            color: #000;
            background-color: #fff;
            margin-top: 15px;
        }
        .answer-info.answer-error{
            border: 1px solid #f44336;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 5px rgba(230, 139, 132, 0.9);
        }
        .ask-title{
            font-size: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e2e2;
            font-weight: 600;
        }
        .ask-content{
            line-height: 2;
            padding-top: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e2e2;
        }
        .ask-user-info{
            padding-top: 10px;
        }
        .answer-info [type=textarea]{
            width: 100%;
            height: 100%;
            min-height: 150px;
            border: none;
            resize:none;
            outline:none;
            font-size: 14px;
        }
        .remind{
            margin-top: 15px;
            color: #f44336;
        }
        .modal-footer{
            text-align: center;
        }
        .modal-footer .btn-confirm-answer{
            padding: 6px 80px;
        }
        .btn.btn-primary{
            background: #0C7CF5;
        }
        i.fa.fa-clock-o.fa-sm{
            margin-right: 5px;
        }
        .answer-page{
            float: right;
            margin: 15px;
        }
        .answer-page [name=pagination]{
            width: auto;
            display: inline-block;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="answer" v-cloak>
        <!-- 答疑解惑 -->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 title">
                <h3 class="text-info">答疑解惑</h3>
            </div>
        </div>
        <!-- 问题统计 -->
        <div class="row statistic">
            <div class="col-md-12 col-sm-12" >
                <h4><i class="fa fa-clock-o fa-sm"></i>等待处理的问题有:<span style="color: #34B5F6">@{{ pagination.total?pagination.total:0 }}</span>个</h4>
            </div>
            <hr>
            <!-- 问题列表 -->
            <div class="row question-list">
                <div class="col-md-4 col-sm-6" v-for="questionItem in question" track-by="id">
                    <div class="statistic-box bg-default m-bottom-md">
                        <div class="statistic-title">
                            @{{ questionItem.title }}
                        </div>
                        <div class="m-top-md">
                            <div data-toggle="modal" type="button" class="btn btn-primary btn-answer" @click="openQuestion(questionItem.id)">解答</div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="answer-page">
                <span>总共@{{ pagination.last_page?pagination.last_page:1 }}页</span>
                <span>现</span>
                <select name="pagination" class="form-control" v-model="page" @change="moreQuestion">
                    <option :value="pageItem+1" v-for="pageItem in pagination.last_page">@{{ pageItem+1 }}</option>
                </select>
                <span>页</span>
                <div class="clearfix"></div>
            </section>
        </div><!-- /row-->

        <!--answer-modal-->
        <div class="modal fade" id="answer-modal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            问题解答
                        </h4>
                    </div>
                    <div class="modal-body">
                        <!-- 提问信息 -->
                        <div class="ask-info">
                            <!-- 问题标题 -->
                            <div class="ask-title">
                                @{{ questionDetail.title }}
                            </div>
                            <!-- 问题内容 -->
                            <div class="ask-content">
                                @{{ questionDetail.detail }}
                            </div>
                            <!-- 提问人信息 -->
                            <div class="ask-user-info">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <strong class="text-muted">提问人:</strong>
                                        <span>@{{ questionDetail.user.name }}</span>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <strong class="text-muted">所在公司:</strong>
                                        <span>@{{ questionDetail.user.company?questionDetail.user.company.name:'' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <strong class="text-muted">身份证:</strong>
                                        <span>@{{ questionDetail.user.id_card }}</span>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <strong class="text-muted">联系方式:</strong>
                                        <span>@{{ questionDetail.user.phone }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- end 提问人信息 -->
                        </div>
                        <!-- end 提问信息 -->

                        <!-- 回答信息 -->
                        <div :class="['answer-info', {'answer-error': error.length==0?0:1}]">
                            <textarea id="inputText" type="textarea" placeholder="请您给出专业的回答!" v-model="answer"></textarea>
                        </div>
                        <!-- end 回答信息 -->

                        <!-- 提示信息 -->
                        <div class="remind">
                            <h5>@{{ error.length==0?'':error[0] }}</h5>
                            <h5>解答不能修改，为了保持您的专业度，请仔细确认你的答复，确认完毕请提交答复!</h5>
                        </div>
                        <!-- end 提示信息 -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-confirm-answer" @click="answerQuestion">确认解答</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal -->
    </div><!--/pdadding-md-->
@endsection
@section('moreScript')
    <script>
        new Vue({
            el: "#answer",
            data: {
                question: [],
                pagination: [],
                answer: "",
                page: 1,
                questionDetail: {
                    user: {
                        name: "",
                        id_card: "",
                        phone: ""
                    }
                },
                error: []
            },
            ready: function () {
                var _this = this;
                var url = "{{ url('admin/answer-info') }}"
                $.ajax({
                    url: url,
                    dataType: 'json',
                    data:{
                        _token: $("meta[name=csrf-token]").attr("content"),
                    },
                    method: 'GET'
                }).done(function (msg) {
                    if (msg.data.length){
                        _this.question = msg.data;
                        _this.pagination = {
                            current_page: msg.current_page,
                            from: msg.from,
                            last_page: msg.last_page,
                            next_page_url: msg.next_page_url,
                            per_page: msg.per_page,
                            prev_page_url: msg.prev_page_url,
                            to: msg.to,
                            total: msg.total
                        };
                    }
                    return false;
                }).fail(function (error) {
                    alert("网络错误！");
                    return false;
                });
            },
            methods: {
                moreQuestion: function () {
                    var _this = this;
                    var url = "{{ url('admin/answer-info') }}";
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        data: {
                            page: _this.page
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (msg) {
                        if (msg.data.length) {
                            _this.question = msg.data;
                            _this.pagination = {
                                current_page: msg.current_page,
                                from: msg.from,
                                last_page: msg.last_page,
                                next_page_url: msg.next_page_url,
                                per_page: msg.per_page,
                                prev_page_url: msg.prev_page_url,
                                to: msg.to,
                                total: msg.total
                            };
                        } else {
                            alert("暂无更多！");
                        }
                    }).fail(function () {
                        alert("网络错误！");
                    });

                    return true;
                },
                openQuestion: function (id) {
                    this.questionDetail= {
                        user:
                        {
                            name: "",
                            id_card: "",
                            phone: ""
                        }
                    };
                    this.answer="";
                    this.error=[];
                    $("#answer-modal").modal('show');
                    var _this = this;
                    var url = "{{ url('admin/answer') }}/"+id;
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (msg) {
                        if (msg.message===undefined) {
                            _this.questionDetail = msg;
                        } else {
                            alert("暂无更多！");
                        }
                    }).fail(function () {
                        alert("网络错误！");
                    });

                    return true;
                },
                answerQuestion: function () {
                    var _this = this;
                    _this.error = [];
                    var comfirmInfo = window.confirm("你确定要提交！");
                    if (!comfirmInfo){
                        return false;
                    }
                    if (_this.answer==''){
                        _this.error=['内容不能为空！'];
                        return false;
                    }

                    var url = "{{ url('admin/answer') }}/"+_this.questionDetail.id;
                    $.ajax(url, {
                        type: 'post',
                        dataType: 'json',
                        timeout: '120000',
                        data: {
                            answer: _this.answer,
                            _method: 'PUT'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (msg) {
                        alert(msg.message);
                        $("#answer-modal").modal('hide');
                        _this.question.$remove(_this.question.find(function (val) {
                            if (val.id==_this.questionDetail.id){
                                return true;
                            }
                        }));
                        _this.pagination.total=_this.pagination.total?_this.pagination.total-1:0;
                    }).fail(function (error) {
                        if (error.responseJSON.answer!==undefined){
                            _this.error = error.responseJSON.answer;
                        }else{
                            alert("网络错误！");
                        }
                    });

                    return true;
                }
            }
        });
    </script>
@endsection