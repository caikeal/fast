@extends('admin.app')
@section('moreCss')
    <link href="{{env('APP_URL')}}/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
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

        .btn-link.member-btn {
            font-size: medium;
            color: #1D75D8;
            margin-left: 42px;
            line-height: 4rem
        }

        .poster-btn {
            border-radius: 1rem;
            color: #fff;
            width: 2rem;
            height: 2rem;
            text-align: center;
            padding: .5rem 0;
            margin-right: 10px;
        }

        .poster-btn.btn-member {
            background-color: #1D75D8;
        }

        .poster-btn.btn-add-account {
            background-color: #1CD01D;
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

        .thumbnail-radius{
            border-radius: 50%;
            height: 50px;
            width: 50px;
            display: inline;
            margin-bottom:0;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="company">
        {{--<div class="alert alert-danger alert-dismissible" role="alert" v-if="systemErrors">--}}
        {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
        {{--<strong>Warning!</strong> @{{systemErrors}}--}}
        {{--</div>--}}
        <!-- 企业管理 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">企业管理</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 快速搜索下属 -->
        <div class="row base-backcolor">
            <form class="form-search" action="{{url('admin/task')}}" method="get">
                {{csrf_field()}}
                <div class="col-xs-9 col-sm-10 col-md-10 col-lg-10">
                    <div class="pull-left poster-icon icon-search-color">
                        <i class="fa fa-search fa-lg poster-icon-height"></i>
                    </div>
                    <input type="text" class="input-medium form-control search-query" placeholder="快速搜索企业或组织"
                           name="name" value="{{$name}}" autocomplete="off">
                </div>
                <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
                    <input type="submit" class="btn btn-primary btn-block" value="搜索">
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="row base-backcolor">
            @if(\Auth::guard('admin')->user()->can('addCompany'))
            <a id="createCompany" class="btn btn-link member-btn" data-toggle="modal" data-target="#create-company">
                <i class="fa fa-university fa-sm poster-btn btn-member"></i>
                <strong>新增企业</strong>
            </a>
            @endif
            @if(\Auth::guard('admin')->user()->can('addTask'))
            <a id="createTask" class="btn btn-link member-btn" data-toggle="modal" data-target="#create-task">
                <i class="fa fa-plus fa-sm poster-btn btn-add-account"></i>
                <strong>新增任务</strong>
            </a>
            @else
                <div style="padding: 5px"></div>
            @endif
        </div>
        <div class="seperator"></div>
        <div class="row base-backcolor">
            <div class="seperator"></div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="pull-left poster-icon icon-result-color">
                    <i class="fa fa-users fa-lg circle-check poster-icon-height"></i>
                </div>
                <p class="result-head">任务总览:</p>
            </div>
        </div>
        <div class="row base-backcolor page-group">
            <div class="pull-right">
                {{--<span>共<em>{{$managers->total()}}</em>条记录</span>--}}
                {!! $tasks->appends(['name'=>$name])->links() !!}
                {{--<select>--}}
                {{--@for($i=0;$i<($managers->lastPage());$i++)--}}
                {{--<option {{$managers->currentPage()==$i+1?"checked":""}}>--}}
                {{--<a href="{{$managers->url($i+1)}}">{{$i+1}}</a>--}}
                {{--</option>--}}
                {{--@endfor--}}
                {{--</select>--}}
                {{--<span>页</span>--}}
            </div>
        </div>
        <div class="line"></div>
        <!-- 历史记录表格 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <!-- table -->
                <table class="table" id="dataTable">
                    <thead>
                    <tr>
                        <th>企业</th>
                        <th>发薪日</th>
                        <th>类型</th>
                        <th>客服</th>
                        <th>任务状态</th>
                        <th>最近处理时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <img class="thumbnail thumbnail-radius" src="{{env("APP_URL")}}/{{$task->company->poster}}">
                                <span>{{$task->company->name}}</span>
                            </td>
                            <td>{{date("Y-m-d",$task->deal_time)}}</td>
                            <td>
                                {{$task->type==1?'薪资':'社保'}}
                            </td>
                            <td>{{$task->receiver->name}}</td>
                            <td>{{$task->status?'已提交':'进行中'}}</td>
                            <td>{{$task->updated_at}}</td>
                            <td>
                                @if($task->status==0)
                                    @if(\Auth::guard('admin')->user()->can('editTask'))
                                        <reset-task-btn :task-id={{$task->id}}></reset-task-btn>
                                    @else
                                    <a class="btn btn-success">进行中</a>
                                    @endif
                                @else
                                    <a class="btn btn-danger">已完成</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- /table  -->
            </div>
        </div>

        <!-- modal createCompany-->
        <div class="modal fade" id="create-company" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">新增企业</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':is_newCompany}">
                                    <label for="name" class="col-lg-2 control-label lable-xs-center">企业名:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" id="company" name="company" placeholder="企业名"
                                               v-model="newCompany">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_newCompany?'block':'none'}">@{{ newCompanyErrors }}</p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block m-top-md" @click="createCompany">创建账号</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal createCompany-->

        <!-- modal createTask-->
        <div class="modal fade" id="create-task" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">创建任务</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':is_companyName}">
                                    <label for="company-name" class="col-lg-2 control-label lable-xs-center">企业名:</label>

                                    <div class=" col-lg-10">
                                        <select class="form-control" id="company-name" name="company-name" v-model="companyName">
                                            @foreach($companys as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_companyName?'block':'none'}">@{{ companyNameErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_receiver}">
                                    <label for="receiver" class="col-lg-2 control-label lable-xs-center">分配人员:</label>

                                    <div class=" col-lg-10">
                                        <select class="form-control" id="receiver" name="receiver" v-model="receiver">
                                            @foreach($ownManagers as $ownManager)
                                            <option value="{{$ownManager->id}}" >{{$ownManager->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_receiver?'block':'none'}">@{{ receiverErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_salaryDay}">
                                    <label for="salary-day" class="col-lg-2 control-label lable-xs-center">发薪日:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control day-picker" id="salary-day" name="salary-day"
                                               placeholder="发薪日" v-model="salaryDay">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_salaryDay?'block':'none'}">@{{ salaryDayErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_insuranceDay}">
                                    <label for="insurance-day" class="col-lg-2 control-label lable-xs-center">社保日期:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control day-picker" id="insurance-day" name="insurance-day"
                                               placeholder="社保日期" v-model="insuranceDay">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_insuranceDay?'block':'none'}">@{{ insuranceDayErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_memo}">
                                    <label class="col-lg-2 control-label lable-xs-center">备注:</label>
                                    <div class="col-lg-10">
                                        <textarea v-model="memo" style="width:100%" rows="10"></textarea>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_memo?'block':'none'}">@{{ memoErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_taskType}">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="task1" class="checkbox-blue" disabled value=1
                                                           v-model="taskType">
                                                <label for="task1"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                代发工资
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;

                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="task2" class="checkbox-blue" disabled value=2
                                                       v-model="taskType">
                                                <label for="task2"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                代缴社保
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_taskType?'block':'none'}">@{{ taskTypeErrors }}</p>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block m-top-md" @click="newTask">创建任务</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal createTask-->

        <!-- modal restTask-->
        <div class="modal fade" id="reset-task" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">重配任务</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':is_editCompanyId}">
                                    <label for="company-id" class="col-lg-2 control-label lable-xs-center">企业名:</label>

                                    <div class=" col-lg-10">
                                        <select class="form-control" id="company-id" name="company-id" v-model="editCompanyId">
                                            @foreach($companys as $company)
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_editCompanyId?'block':'none'}">@{{ editCompanyIdErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_editReceiveId}">
                                    <label for="receive-id" class="col-lg-2 control-label lable-xs-center">分配人员:</label>

                                    <div class=" col-lg-10">
                                        <select class="form-control" id="receive-id" name="receive-id" v-model="editReceiveId">
                                            @foreach($ownManagers as $ownManager)
                                                <option value="{{$ownManager->id}}" >{{$ownManager->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_editReceiveId?'block':'none'}">@{{ editReceiveIdErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_editSalaryDay}">
                                    <label for="edit-salary-day" class="col-lg-2 control-label lable-xs-center">发薪日:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control day-picker" id="edit-salary-day" name="edit-salary-day"
                                               placeholder="发薪日" v-model="editSalaryDay">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_editSalaryDay?'block':'none'}">@{{ editSalaryDayErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_editInsuranceDay}">
                                    <label for="edit-insurance-day" class="col-lg-2 control-label lable-xs-center">社保日期:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control day-picker" id="edit-insurance-day" name="edit-insurance-day"
                                               placeholder="社保日期" v-model="editInsuranceDay">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_editInsuranceDay?'block':'none'}">@{{ editInsuranceDayErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_editMemo}">
                                    <label class="col-lg-2 control-label lable-xs-center">备注:</label>
                                    <div class="col-lg-10">
                                        <textarea v-model="editMemo" style="width:100%" rows="10"></textarea>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_editMemo?'block':'none'}">@{{ editMemoErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':is_editTaskType}">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="task3" class="checkbox-blue" disabled value=1
                                                       v-model="editTaskType">
                                                <label for="task3"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                代发工资
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;

                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="task4" class="checkbox-blue" disabled value=2
                                                       v-model="editTaskType">
                                                <label for="task4"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                代缴社保
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':is_taskType?'block':'none'}">@{{ taskTypeErrors }}</p>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block m-top-md" @click="changeTask">保存任务</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal restTask-->

        <template id="task-btn-template"  style="display: none">
            <a class="btn btn-success" data-target="#reset-task" data-toggle="modal" @click="notify">修改信息</a>
        </template>
    </div>
    <!--/pdadding-md-->
@endsection
@section('addition')
@endsection
@section('moreScript')
    <script src="{{env('APP_URL')}}/js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{env('APP_URL')}}/js/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="//cdn.bootcss.com/vue/1.0.17/vue.js"></script>
    <script>
        //侧边栏位置锁定
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place7").addClass("active");
        })($);

        //日历
        $('.day-picker').datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayBtn: true,
            minView:2
        });

        //vue托管
        Vue.component('reset-task-btn',{
            template:'#task-btn-template',
            props:{
                taskId:{
                    type:Number,
                    required: true
                }
            },
            methods:{
                notify:function(){
                    var _this=this;
                    if(_this.taskId){
                        this.$dispatch('task-id',_this.taskId);
                    }
                    var url="{{url('admin/task')}}/"+_this.taskId+"/edit";
                    var company_id='';
                    var receive_id='';
                    var memo='';
                    var salary_day='';
                    var insurance_day='';
                    var type='';
                    var status='';
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        data: {},
                        type:'GET'
                    }).done(function (data) {
                        if(data.ret_num==0){
                            company_id=data.val.company_id;
                            receive_id=data.val.receive_id;
                            memo=data.val.memo;
                            type=data.val.type;
                            salary_day=(type==1)?data.val.deal_time:'';
                            insurance_day=(type==2)?data.val.deal_time:'';
                            status=data.val.status;
                            _this.$dispatch('company-id',company_id);
                            _this.$dispatch('receive-id',receive_id);
                            _this.$dispatch('memo',memo);
                            _this.$dispatch('insurance-day',insurance_day);
                            _this.$dispatch('salary-day',salary_day);
                            _this.$dispatch('type',type);
                            _this.$dispatch('status',status);
                        }
                    }).fail(function (data) {
                        alert("网络错误！");
                    });
                }
            }
        });
        new Vue({
            el: "#company",
            data:{
                is_companyName:0,
                companyNameErrors:'',
                is_receiver:0,
                receiverErrors:'',
                is_salaryDay:0,
                salaryDayErrors:'',
                is_insuranceDay:0,
                insuranceDayErrors:'',
                is_taskType:0,
                taskTypeErrors:'',
                is_memo:0,
                memoErrors:'',
                companyName:'',
                receiver:'',
                salaryDay:'',
                insuranceDay:'',
                taskType:[],
                memo:'',
                taskId:'',
                editCompanyId:'',
                editReceiveId:'',
                editMemo:'',
                editSalaryDay:'',
                editInsuranceDay:'',
                editType:'',
                editStatus:'',
                editTaskType:'',
                is_editCompanyId:0,
                editCompanyIdErrors:'',
                is_editReceiveId:0,
                editReceiveIdErrors:'',
                is_editMemo:0,
                editMemoErrors:'',
                is_editSalaryDay:0,
                editSalaryDayErrors:'',
                is_editTaskType:0,
                editTaskTypeErrors:'',
                is_newCompany:0,
                newCompanyErrors:'',
                newCompany:''
            },
            computed:{
                taskType: function () {
                    var type=[];
                    if(this.salaryDay){
                        type.push("1");
                    }
                    if(this.insuranceDay){
                        type.push("2");
                    }
                    return type;
                },
                editTaskType: function () {
                    var type=[];
                    if(this.editSalaryDay){
                        type.push("1");
                    }
                    if(this.editInsuranceDay){
                        type.push("2");
                    }
                    return type;
                }
            },
            methods:{
                newTask: function () {
                    var url = "{{url('admin/task')}}";
                    var _this=this;
                    _this.is_companyName=0;
                    _this.is_receiver=0;
                    _this.is_salaryDay=0;
                    _this.is_insuranceDay=0;
                    _this.is_taskType=0;
                    _this.is_memo=0;
                    _this.companyNameErrors='';
                    _this.receiverErrors='';
                    _this.salaryDayErrors='';
                    _this.insuranceDayErrors='';
                    _this.taskTypeErrors='';
                    _this.memoErrors='';
                    if(!_this.companyName || typeof _this.companyName=='undefined'){
                        _this.companyNameErrors='企业名必填！';
                        _this.is_companyName=1;
                        return false;
                    }
                    if(!_this.receiver || typeof _this.receiver=='undefined'){
                        _this.receiverErrors='分配用户必填！';
                        _this.is_receiver=1;
                        return false;
                    }
                    if(!_this.salaryDay&&!_this.insuranceDay){
                        _this.salaryDayErrors='薪资和社保两者必填1项！';
                        _this.is_salaryDay=1;
                        _this.insuranceDayErrors='薪资和社保两者必填1项！';
                        _this.is_insuranceDay=1;
                        return false;
                    }
                    if(_this.salaryDay&&!_this.salaryDay.match(/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/)){
                        _this.salaryDayErrors='时间格式错误！';
                        _this.is_salaryDay=1;
                        return false;
                    }
                    if(_this.insuranceDay&&!_this.insuranceDay.match(/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/)){
                        _this.insuranceDayErrors='时间格式错误！';
                        _this.is_insuranceDay=1;
                        return false;
                    }
                    $.ajax({
                                url:url,
                                dataType:'json',
                                headers:{
                                    'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                                },
                                timeout:60000,
                                data: {
                                    name: _this.companyName,
                                    receiver: _this.receiver,
                                    sd: _this.salaryDay,
                                    id: _this.insuranceDay,
                                    memo:_this.memo
                                },
                                type:'POST'
                            })
                            .done(function(data){
                                if(data.ret_num==0){
                                    _this.is_companyName=0;
                                    _this.companyNameErrors='';
                                    _this.is_receiver=0;
                                    _this.receiverErrors='';
                                    _this.is_salaryDay=0;
                                    _this.salaryDayErrors='';
                                    _this.is_insuranceDay=0;
                                    _this.insuranceDayErrors='';
                                    _this.is_taskType=0;
                                    _this.taskTypeErrors='';
                                    _this.is_memo=0;
                                    _this.memoErrors='';
                                    _this.companyName='';
                                    _this.receiver='';
                                    _this.salaryDay='';
                                    _this.insuranceDay='';
                                    _this.taskType=[];
                                    _this.memo='';
                                    $('#create-task').modal('hide');
                                    alert(data.ret_msg);
                                }else{
                                    _this.is_companyName=1;
                                    _this.companyNameErrors=data.ret_msg;
                                }
                            })
                            .fail(function(data){
                                var errs=JSON.parse(data.responseText);
                                if(errs.name){
                                    _this.is_companyName=1;
                                    _this.companyNameErrors=errs.name[0];
                                }
                                if(errs.receiver){
                                    _this.is_receiver=1;
                                    _this.receiverErrors=errs.receiver[0];
                                }
                                if(errs.sd){
                                    _this.is_salaryDay=1;
                                    _this.salaryDayErrors=errs.sd[0];
                                }
                                if(errs.id){
                                    _this.is_insuranceDay=1;
                                    _this.insuranceDayErrors=errs.id[0];
                                }
                            });
                },
                changeTask: function () {
                    var _this=this;
                    var url = "{{url('admin/task')}}/"+_this.taskId;
                    _this.is_editCompanyId=0;
                    _this.is_editReceiveId=0;
                    _this.is_editSalaryDay=0;
                    _this.is_editInsuranceDay=0;
                    _this.is_editTaskType=0;
                    _this.is_editMemo=0;
                    _this.editCompanyIdErrors='';
                    _this.editReceiveIdErrors='';
                    _this.editSalaryDayErrors='';
                    _this.editInsuranceDayErrors='';
                    _this.editTaskTypeErrors='';
                    _this.editMemoErrors='';
                    if(!_this.editCompanyId || typeof _this.editCompanyId=='undefined'){
                        _this.editCompanyIdErrors='企业名必填！';
                        _this.is_editCompanyId=1;
                        return false;
                    }
                    if(!_this.editReceiveId || typeof _this.editReceiveId=='undefined'){
                        _this.editReceiveIdErrors='分配用户必填！';
                        _this.is_editReceiveId=1;
                        return false;
                    }
                    if(!_this.editSalaryDay&&!_this.editInsuranceDay){
                        _this.editSalaryDayErrors='薪资和社保两者必填1项！';
                        _this.is_editSalaryDay=1;
                        _this.editInsuranceDayErrors='薪资和社保两者必填1项！';
                        _this.is_editInsuranceDay=1;
                        return false;
                    }
                    if(_this.editSalaryDay&&!_this.editSalaryDay.match(/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/)){
                        _this.editSalaryDayErrors='时间格式错误！';
                        _this.is_editSalaryDay=1;
                        return false;
                    }
                    if(_this.editInsuranceDay&&!_this.editInsuranceDay.match(/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/)){
                        _this.editInsuranceDayErrors='时间格式错误！';
                        _this.is_editInsuranceDay=1;
                        return false;
                    }
                    $.ajax({
                                url:url,
                                dataType:'json',
                                headers:{
                                    'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                                },
                                timeout:60000,
                                data: {
                                    name: _this.editCompanyId,
                                    receiver: _this.editReceiveId,
                                    sd: _this.editSalaryDay,
                                    id: _this.editInsuranceDay,
                                    memo:_this.editMemo,
                                    _method:'PUT'
                                },
                                type:'POST'
                            })
                            .done(function(data){
                                if(data.ret_num==0){
                                    _this.is_editCompanyId=0;
                                    _this.editCompanyIdErrors='';
                                    _this.is_editReceiveId=0;
                                    _this.editReceiveIdErrors='';
                                    _this.is_editSalaryDay=0;
                                    _this.editSalaryDayErrors='';
                                    _this.is_editInsuranceDay=0;
                                    _this.editInsuranceDayErrors='';
                                    _this.is_editTaskType=0;
                                    _this.editTaskTypeErrors='';
                                    _this.is_editMemo=0;
                                    _this.editMemoErrors='';
                                    _this.editCompanyId='';
                                    _this.editReceiveId='';
                                    _this.editSalaryDay='';
                                    _this.editInsuranceDay='';
                                    _this.editTaskType=[];
                                    _this.editMemo='';
                                    $('#reset-task').modal('hide');
                                    alert(data.ret_msg);
                                }else{
                                    _this.is_editCompanyId=1;
                                    _this.editCompanyIdErrors=data.ret_msg;
                                }
                            })
                            .fail(function(data){
                                var errs=JSON.parse(data.responseText);
                                if(errs.name){
                                    _this.is_editCompanyId=1;
                                    _this.editCompanyIdErrors=errs.name[0];
                                }
                                if(errs.receiver){
                                    _this.is_editReceiveId=1;
                                    _this.editReceiveIdErrors=errs.receiver[0];
                                }
                                if(errs.sd){
                                    _this.is_editSalaryDay=1;
                                    _this.editSalaryDayErrors=errs.sd[0];
                                }
                                if(errs.id){
                                    _this.is_editInsuranceDay=1;
                                    _this.editInsuranceDayErrors=errs.id[0];
                                }
                            });
                },
                createCompany:function(){
                    var _this=this;
                    var url = "{{url('admin/company')}}";
                    _this.is_newCompany=0;
                    _this.newCompanyErrors='';
                    if(!_this.newCompany || typeof _this.newCompany=='undefined'){
                        _this.is_newCompany=1;
                        _this.newCompanyErrors='公司名称必填！';
                        return false;
                    }
                    $.ajax({
                                url:url,
                                dataType:'json',
                                headers:{
                                    'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                                },
                                timeout:60000,
                                data: {
                                    name: _this.newCompany,
                                },
                                type:'POST'
                            })
                            .done(function(data){
                                if(data.ret_num==0){
                                    _this.is_newCompany=0;
                                    _this.newCompanyErrors='';
                                    _this.newCompany='';

                                    $('#create-company').modal('hide');
                                    alert(data.ret_msg);
                                }else{
                                    _this.is_newCompany=1;
                                    _this.newCompanyErrors=data.ret_msg;
                                }
                            })
                            .fail(function(data){
                                var errs=JSON.parse(data.responseText);
                                if(errs.name){
                                    _this.is_newCompany=1;
                                    _this.newCompanyErrors=errs.name[0];
                                }
                            });
                }
            },
            events:{
                'task-id': function (taskId) {
                    this.taskId=taskId;
                },
                'company-id':function(company_id){
                    this.editCompanyId=company_id;
                },
                'receive-id': function (receive_id) {
                    this.editReceiveId=receive_id;
                },
                'memo': function (memo) {
                    this.editMemo=memo;
                },
                'insurance-day': function (insurance_day) {
                    this.editInsuranceDay=insurance_day;
                },
                'salary-day': function (salary_day) {
                    this.editSalaryDay=salary_day;
                },
                'type': function (type) {
                    this.editType=type;
                },
                'status': function (status) {
                    this.editStatus=status;
                }
            }
        });
    </script>
@endsection