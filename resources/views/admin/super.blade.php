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
            padding: .5rem;
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
    </style>
@endsection
@section('content')
    <div class="padding-md" id="super">
        {{--<div class="alert alert-danger alert-dismissible" role="alert" v-if="systemErrors">--}}
            {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
            {{--<strong>Warning!</strong> @{{systemErrors}}--}}
        {{--</div>--}}
        <!-- 超级管理员 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">超级管理员</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 快速搜索客服 -->
        <div class="row base-backcolor">
            <form class="form-search" action="{{url('admin/super')}}" method="get">
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
        <div class="row base-backcolor">
            <a id="initUser" class="btn btn-link member-btn" data-toggle="modal" data-target="#init-user">
                <i class="fa fa-user fa-sm poster-btn btn-member"></i>
                <strong>用户管理</strong>
            </a>
            <a id="createManager" class="btn btn-link member-btn" data-toggle="modal" data-target="#create-manager">
                <i class="fa fa-plus fa-sm poster-btn btn-add-account"></i>
                <strong>创建帐号</strong>
            </a>
        </div>
        <div class="seperator"></div>
        <div class="row base-backcolor">
            <div class="seperator"></div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="pull-left poster-icon icon-result-color">
                    <i class="fa fa-edit fa-lg circle-check poster-icon-height"></i>
                </div>
                <p class="result-head">查询结果:</p>
            </div>
        </div>
        <div class="row base-backcolor page-group">
            <div class="pull-right">
                {{--<span>共<em>{{$managers->total()}}</em>条记录</span>--}}
                {!! $managers->appends(['name'=>$name])->links() !!}
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
                        <th>姓名</th>
                        <th>帐号</th>
                        <th>类别</th>
                        <th>权限</th>
                        <th>操作1</th>
                        <th>操作2</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    @foreach($managers as $manager)
                        <tr>
                            <td>{{$manager->name}}</td>
                            <td>{{$manager->email}}</td>
                            <td>{{$manager->roles[0]->label}}</td>
                            <td>{{$manager->roles[0]->level}}级管理员</td>
                            <td>
                                <a class="btn btn-success" data-target="#rest-password" data-toggle="modal">重置密码</a>
                            </td>
                            <td>
                                <a class="btn btn-danger">停用</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- /table  -->
            </div>
        </div>

        <!-- modal initUser-->
        <div class="modal fade" id="init-user" role="dialog" aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="gridSystemModalLabel">用户管理</h4>
                    </div>
                    <div class="modal-body">
                        <div class="seperator"></div>
                        <div class="container-fluid">
                            <form>
                                <input type="hidden" name="id" v-model="userId">
                                <div class="row">
                                    <div class="col-sm-12"  :class="{'has-error':is_userPhone}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="搜索手机号" v-model="phone">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" @click="searchPerson">搜索</button>
                                            </span>
                                        </div>
                                        <p class="help-block col-lg-12" :style="{'display':is_userPhone?'block':'none'}">@{{ userPhoneErrors }}</p>
                                    </div>
                                </div>
                                <div class="seperator"></div>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">姓名</span>
                                            <input type="text" class="form-control none-left-border" disabled="disabled"
                                                   value="@{{userName}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">手机号</span>
                                            <input type="text" class="form-control none-left-border" disabled="disabled"
                                                   value="@{{userPhone}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="seperator"></div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">企业名</span>
                                            <input type="text" class="form-control none-left-border" disabled="disabled"
                                                   value="@{{companyName}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="seperator" style="padding: 20px;"></div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block" @click="initAccount">确认初始化</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /modal initUser-->

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
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="salary" class="checkbox-blue" value=2
                                                       v-model="managerRoles">
                                                <label for="salary"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                理赔主管
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="compensate" class="checkbox-blue" value=3
                                                       v-model="managerRoles">
                                                <label for="compensate"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                薪资主管
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="benefit" class="checkbox-blue" value=4
                                                       v-model="managerRoles">
                                                <label for="benefit"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                福利主管
                                            </div>
                                        </div>
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

        <!-- modal restPassword-->
        <div class="modal fade" id="rest-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">重置密码</h4>
                    </div>
                    <div class="modal-body">
                        <div class="seperator"></div>
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group">
                                    <label for="" class="col-lg-2 control-label">新密码</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" placeholder="新密码">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-lg-2 control-label">确认密码</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" placeholder="确认密码">
                                    </div>
                                </div>
                                <div style="padding-top: 40px;"></div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <a class="btn btn-primary block">确认重置</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal restPassword-->
    </div>
    <!--/pdadding-md-->
@endsection
@section('addition')
@endsection
@section('moreScript')
    <script src="//cdn.bootcss.com/vue/1.0.17/vue.js"></script>
    <script>
        //侧边栏位置锁定
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place9").addClass("active");
        })($);

        //vue托管
        new Vue({
            el: '#super',
            data: {
                phone: '',
                userPhone: '',
                userName: '',
                userId:'',
                companyName: '',
                is_userPhone: 0,
                userPhoneErrors: '',
                managerName: '',
                managerAccount: '',
                managerPassword: '',
                managerRoles: [],
                is_managerName:0,
                is_managerAccount:0,
                is_managerPassword:0,
                is_managerRoles:0,
                managerNameErrors:'',
                managerAccountErrors:'',
                managerPasswordErrors:'',
                managerRolesErrors:'',
                systemErrors:''
            },
            methods: {
                searchPerson: function () {
                    var url = "{{url('admin/user')}}";
                    var _this=this;
                    _this.is_userPhone=0;
                    _this.userPhoneErrors='';
                    _this.userPhone='';
                    _this.userName='';
                    _this.companyName='';
                    _this.userId='';
                    if(!_this.phone|| typeof _this.phone=='undefined'){
                        _this.userPhoneErrors='手机号必填！';
                        _this.is_userPhone=1;
                        return false;
                    }
                    if(!_this.phone.match(/^1[3456789][0-9]{9}$/)){
                        _this.userPhoneErrors='手机号格式出错！';
                        _this.is_userPhone=1;
                        return false;
                    }
                    $.ajax({
                        url:url,
                        data:{phone: _this.phone},
                        type:'GET',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        dataType:'json'
                    }).done(function(data){
                        if(data.ret_num==0){
                            if(data.ret_msg) {
                                _this.userPhone = data.ret_msg.phone;
                                _this.userName = data.ret_msg.name;
                                _this.companyName = data.ret_msg.company.name;
                                _this.userId = data.ret_msg.id;
                            }else{
                                _this.is_userPhone=1;
                                _this.userPhoneErrors='不存在该手机号';
                            }
                        }else{
                            _this.userPhoneErrors="网络错误";
                            _this.is_userPhone=1;
                        }
                    }).fail(function (data) {
                        var err=JSON.parse(data.responseText);
                        if(err.phone){
                            _this.userPhoneErrors=err.phone[0];
                            _this.is_userPhone=1;
                        }
                    });
                },
                initAccount:function(){
                    var _this=this;
                    if(_this.is_userPhone ||  !_this.userId){
                        _this.userPhoneErrors="未知用户不能初始化！";
                        _this.is_userPhone=1;
                    }
                    var url = "{{url('admin/user')}}"+"/"+_this.userId;
                    $.ajax({
                        url:url,
                        data:{phone: _this.userPhone,_method:'PUT'},
                        type:'POST',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        dataType:'json'
                    }).done(function(data){
                        if(data.ret_num==0){
                            _this.phone = '';
                            _this.userPhone = '';
                            _this.userName = '';
                            _this.companyName = '';
                            _this.userId = '';
                            _this.is_userPhone=0;
                            _this.userPhoneErrors='';
                            $("#init-user").modal('hide');
                            alert(data.ret_msg);
                        }else{
                            _this.userPhoneErrors="网络错误";
                            _this.is_userPhone=1;
                        }
                    }).fail(function (data) {
                        var err=JSON.parse(data.responseText);
                        if(err.phone){
                            _this.userPhoneErrors=err.phone[0];
                            _this.is_userPhone=1;
                        }
                    });
                },
                newAccount: function () {
                    var url = "{{url('admin/account')}}";
                    var _this=this;
                    _this.is_managerName=0;
                    _this.is_managerAccount=0;
                    _this.is_managerPassword=0;
                    _this.is_managerRoles=0;
                    _this.managerNameErrors='';
                    _this.managerAccountErrors='';
                    _this.managerPasswordErrors='';
                    _this.managerRolesErrors='';
                    if(!_this.managerName || typeof _this.managerName=='undefined'){
                        _this.managerNameErrors='姓名必填！';
                        _this.is_managerName=1;
                        return false;
                    }
                    if(!_this.managerAccount || typeof _this.managerAccount=='undefined'){
                        _this.managerAccountErrors='登录账户必填！';
                        _this.is_managerAccount=1;
                        return false;
                    }
                    if(!_this.managerAccount.match(/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/)){
                        _this.managerAccountErrors='登录账户必为邮箱！';
                        _this.is_managerAccount=1;
                        return false;
                    }
                    if(!_this.managerPassword || typeof _this.managerPassword=='undefined'){
                        _this.managerPasswordErrors='初始密码必填！';
                        _this.is_managerPassword=1;
                        return false;
                    }
                    if(_this.managerPassword.length<6){
                        _this.managerPasswordErrors='初始密码至少6位！';
                        _this.is_managerPassword=1;
                        return false;
                    }
                    if(!_this.managerPassword.match(/=|\+|-|@|_|\*|[a-zA-Z]/g)){
                        _this.managerPasswordErrors='"A-Z" "a-z" "+" "_" "*" "=" "-" "@"至少存在1项！';
                        _this.is_managerPassword=1;
                        return false;
                    }
                    if(!_this.managerRoles.length || typeof _this.managerRoles=='undefined'){
                        _this.managerRolesErrors='权限必选！';
                        _this.is_managerRoles=1;
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
                            name: _this.managerName,
                            account: _this.managerAccount,
                            pwd: _this.managerPassword,
                            role: _this.managerRoles
                        },
                        type:'POST'
                    })
                    .done(function(data){
                        if(data.ret_num==0){
                            _this.is_managerName=0;
                            _this.is_managerAccount=0;
                            _this.is_managerPassword=0;
                            _this.is_managerRoles=0;
                            _this.managerNameErrors='';
                            _this.managerAccountErrors='';
                            _this.managerPasswordErrors='';
                            _this.managerRolesErrors='';
                            _this.managerName= '';
                            _this.managerAccount= '';
                            _this.managerPassword= '';
                            _this.managerRoles= [];
                            $('#create-manager').modal('hide');
                            alert(data.ret_msg);
                        }else{
                            _this.is_managerName=1;
                            _this.managerNameErrors=data.ret_msg;
                        }
                    })
                    .fail(function(data){
                        var errs=JSON.parse(data.responseText);
                        if(errs.name){
                            _this.is_managerName=1;
                            _this.managerNameErrors=errs.name[0];
                        }
                        if(errs.account){
                            _this.is_managerAccount=1;
                            _this.managerAccountErrors=errs.account[0];
                        }
                        if(errs.pwd){
                            _this.is_managerPassword=1;
                            _this.managerPasswordErrors=errs.pwd[0];
                        }
                        if(errs['role']){
                            _this.is_managerRoles=1;
                            _this.managerRolesErrors=errs['role'][0];
                        }
                    });
                }
            }
        });
    </script>
@endsection