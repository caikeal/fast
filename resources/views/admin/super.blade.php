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

        .new-list{
            background-color: #D7FFD0;
            color: #0006FF;
        }

        select[name=pagination]{
            display: inline-block;
            width: auto;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="padding-md" id="super" v-cloak>
        <!-- 超级管理员 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">超级管理员</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 快速搜索客服 -->
        <div class="row base-backcolor">
            <form class="form-search" action="#" method="get">
                {{csrf_field()}}
                <div class="col-xs-9 col-sm-10 col-md-10 col-lg-10">
                    <div class="pull-left poster-icon icon-search-color">
                        <i class="fa fa-search fa-lg poster-icon-height"></i>
                    </div>
                    <input type="text" class="input-medium form-control search-query" placeholder="快速搜索客服姓名"
                           name="name" v-model="searchInfo.name" autocomplete="off">
                </div>
                <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
                    <input type="submit" class="btn btn-primary btn-block" value="搜索" @click.prevent="searchManager">
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
                <span>共<em>@{{ searchInfo.pageInfo.total }}</em>条记录</span>
                <select name="pagination" class="form-control" v-model="searchInfo.pageInfo.current_page" @change="moreManager">
                    <option :value="pageItem+1" v-for="pageItem in searchInfo.pageInfo.last_page">@{{ pageItem+1 }}</option>
                </select>
                <span>页</span>
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
                        <!--new add managers start-->
                        <tr v-for="manager in newManagerList" class="new-list">
                            <td>@{{manager.name}}</td>
                            <td>@{{manager.email}}</td>
                            <td>
                                <span v-for="roleInfo in manager['roles']">
                                    @{{roleInfo.label}}<br>
                                </span>
                            </td>
                            <td>@{{manager['roles'][0]['level']}}级管理员</td>
                            <td>
                                <reset-pwd-btn :manager-id="manager.id"></reset-pwd-btn>
                            </td>
                            <td>
                                <toggle-manager :manager-id="manager.id" :manager-status='manager.deleted_at?"启用":"停用"'></toggle-manager>
                            </td>
                        </tr>
                        <!--new add managers end-->

                        <!--managerList-->
                        <tr v-for="manager in managerList">
                            <td>@{{manager.name}}</td>
                            <td>@{{manager.email}}</td>
                            <td>
                                <span v-for="roleInfo in manager['roles']">
                                    @{{roleInfo.label}}<br>
                                </span>
                            </td>
                            <td>@{{manager['roles'][0]['level']}}级管理员</td>
                            <td>
                                <reset-pwd-btn :manager-id="manager.id"></reset-pwd-btn>
                            </td>
                            <td>
                                <toggle-manager :manager-id="manager.id" :manager-status='manager.deleted_at?"启用":"停用"'></toggle-manager>
                            </td>
                        </tr>
                        <!--managerList-->
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
                                <input type="hidden" name="id" v-model="userOperation.userId">
                                <div class="row">
                                    <div class="col-sm-12"  :class="{'has-error': userError.is_userPhone}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="搜索手机号" v-model="userOperation.phone">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" @click="searchPerson">搜索</button>
                                            </span>
                                        </div>
                                        <p class="help-block col-lg-12" :style="{'display':userError.is_userPhone?'block':'none'}">@{{ userError.userPhoneErrors }}</p>
                                    </div>
                                </div>
                                <div class="seperator"></div>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">姓名</span>
                                            <input type="text" class="form-control none-left-border" disabled="disabled"
                                                   value="@{{ userOperation.userName }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">手机号</span>
                                            <input type="text" class="form-control none-left-border" disabled="disabled"
                                                   value="@{{userOperation.userPhone}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="seperator"></div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">企业名</span>
                                            <input type="text" class="form-control none-left-border" disabled="disabled"
                                                   value="@{{userOperation.companyName}}">
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
                                <div class="form-group" :class="{'has-error':managerError.name.is_managerName}">
                                    <label for="name" class="col-lg-2 control-label lable-xs-center">姓名:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="姓名"
                                               v-model="createManager.managerName">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':managerError.name.is_managerName?'block':'none'}">@{{ managerError.name.managerNameErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': managerError.account.is_managerAccount}">
                                    <label for="account" class="col-lg-2 control-label lable-xs-center">账号:</label>

                                    <div class=" col-lg-10">
                                        <input type="email" class="form-control" id="account" name="account"
                                               placeholder="账号" v-model="createManager.managerAccount">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerError.account.is_managerAccount?'block':'none'}">@{{ managerError.account.managerAccountErrors }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':managerError.password.is_managerPassword}">
                                    <label for="password" class="col-lg-2 control-label lable-xs-center">密码:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" id="password" name="password"
                                               placeholder="密码" v-model="createManager.managerPassword">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':managerError.password.is_managerPassword?'block':'none'}">@{{ managerError.password.managerPasswordErrors }}</p>
                                </div>
                                <div class="form-group" :class="{'has-error': managerError.roles.is_managerRoles}">
                                    <div class="col-lg-offset-2 col-lg-10">
                                    @foreach($memberRoles as $memberRole)
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="{{$memberRole->name}}" class="checkbox-blue" value={{$memberRole->id}}
                                                        v-model="createManager.managerRoles">
                                                <label for="{{$memberRole->name}}"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                {{$memberRole->label}}
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                    @endforeach
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerError.roles.is_managerRoles?'block':'none'}">@{{ managerError.roles.managerRolesErrors }}</p>
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
        <div class="modal fade" id="reset-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                <div class="form-group" :class="{'has-error': restPasswordError.is_newPassword}">
                                    <label for="new-pwd" class="col-lg-2 control-label" >新密码</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="new-pwd" placeholder="新密码" v-model="restPassword.newPassword">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':restPasswordError.is_newPassword?'block':'none'}">@{{ restPasswordError.newPasswordErrors }}</p>
                                </div>
                                <div class="form-group" :class="{'has-error':restPasswordError.is_confirmPassword}">
                                    <label for="confirm-pwd" class="col-lg-2 control-label">确认密码</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="confirm-pwd" placeholder="确认密码" v-model="restPassword.confirmPassword">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':restPasswordError.is_confirmPassword?'block':'none'}">@{{ restPasswordError.confirmPasswordErrors }}</p>
                                </div>
                                <div style="padding-top: 40px;"></div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <a class="btn btn-primary block" @click="resetPassword">确认重置</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal restPassword-->

        <template id="reset-btn-template"  style="display: none">
            <a class="btn btn-success" data-target="#reset-password" data-toggle="modal" @click="notify">重置密码</a>
        </template>

        <template id="toggle-manager-template" style="display: none">
            <a class="btn" :class="(managerStatus=='停用')?'btn-danger':'btn-info'" @click="toggleManager">@{{managerStatus}}</a>
        </template>
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
            $(".lock-place9").addClass("active");
        })($);

        //vue托管
        Vue.component('reset-pwd-btn',{
            template:'#reset-btn-template',
            props:{
                managerId:{
                    type:Number,
                    required: true
                }
            },
            methods:{
                notify:function(){
                    var _this=this;
                    if(_this.managerId){
                        this.$dispatch('mg-id',_this.managerId);
                    }
                }
            }
        });
        Vue.component('toggle-manager',{
            template:'#toggle-manager-template',
            props:{
                managerId:{
                    type:Number,
                    required:true
                },
                managerStatus:{
                    type:String,
                    required:true
                }
            },
            methods:{
                toggleManager: function () {
                    var _this=this;
                    if(!_this.managerId){
                        alert("数据格式错误！");
                    }
                    var url="{{url('admin/manager')}}"+"/"+_this.managerId;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content')
                        },
                        timeout:60000,
                        type:'POST',
                        data:{
                            _method:'DELETE'
                        }
                    }).done(function (data) {
                        if(data.ret_num==0){
                            _this.managerStatus=data.ret_msg;
                            alert("修改成功！");
                        }else{
                            alert("修改失败！");
                        }
                    }).fail(function (data) {
                        alert("网络错误！");
                    });
                }
            }
        });
        var vm=new Vue({
            el: '#super',
            data: {
                newManagerList: [],
                managerList: [],
                searchInfo: {
                    name: '',
                    searchName: '',
                    pageInfo: {
                        total: 0,
                        per_page: 15,
                        current_page: 0,
                        last_page: 0,
                        next_page_url: '',
                        prev_page_url: '',
                        from: 0,
                        to: 0
                    },
                },
                userOperation: {
                    phone: '',
                    userPhone: '',
                    userName: '',
                    userId: '',
                    companyName: ''
                },
                userError: {
                    is_userPhone: 0,
                    userPhoneErrors: ''
                },
                createManager: {
                    managerName: '',
                    managerAccount: '',
                    managerPassword: '',
                    managerRoles: ['{{$memberRoles->first()->id}}'],
                },
                managerError: {
                    name: {
                        is_managerName:0,
                        managerNameErrors:''
                    },
                    account: {
                        is_managerAccount:0,
                        managerAccountErrors:''
                    },
                    password: {
                        is_managerPassword:0,
                        managerPasswordErrors:'',
                    },
                    roles: {
                        is_managerRoles:0,
                        managerRolesErrors:'',
                    }
                },
                restPassword: {
                    managerId:'',
                    newPassword:'',
                    confirmPassword:'',
                },
                restPasswordError: {
                    is_newPassword:0,
                    newPasswordErrors:'',
                    is_confirmPassword:0,
                    confirmPasswordErrors:''
                }
            },
            ready: function () {
                var _this = this;
                var url = "{{ url('admin/manager') }}";
                $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        type:'GET'
                    })
                    .done(function(data){
                        if(data.data.length!=0){
                            _this.managerList = data.data;
                            _this.searchInfo.pageInfo = {
                                total: data.total,
                                per_page: data.per_page,
                                current_page: data.current_page,
                                last_page: data.last_page,
                                next_page_url: data.next_page_url,
                                prev_page_url: data.prev_page_url,
                                from: data.from,
                                to: data.to
                            };
                        }
                    })
                    .fail(function(){
                        alert("网络错误！");
                    });
            },
            methods: {
                searchPerson: function () {
                    var url = "{{url('admin/user')}}";
                    var _this=this;
                    _this.userError.is_userPhone=0;
                    _this.userError.userPhoneErrors='';
                    _this.userOperation.userPhone='';
                    _this.userOperation.userName='';
                    _this.userOperation.companyName='';
                    _this.userOperation.userId='';
                    if(!_this.userOperation.phone|| typeof _this.userOperation.phone=='undefined'){
                        _this.userError.userPhoneErrors='手机号必填！';
                        _this.userError.is_userPhone=1;
                        return false;
                    }
                    if(!_this.userOperation.phone.match(/^1[3456789][0-9]{9}$/)){
                        _this.userError.userPhoneErrors='手机号格式出错！';
                        _this.userError.is_userPhone=1;
                        return false;
                    }
                    $.ajax({
                        url:url,
                        data:{phone: _this.userOperation.phone},
                        type:'GET',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        dataType:'json'
                    }).done(function(data){
                        if(data.ret_num==0){
                            if(data.ret_msg) {
                                _this.userOperation.userPhone = data.ret_msg.phone;
                                _this.userOperation.userName = data.ret_msg.name;
                                _this.userOperation.companyName = data.ret_msg.company.name;
                                _this.userOperation.userId = data.ret_msg.id;
                            }else{
                                _this.userError.is_userPhone=1;
                                _this.userError.userPhoneErrors='不存在该手机号';
                            }
                        }else{
                            _this.userError.userPhoneErrors="网络错误";
                            _this.userError.is_userPhone=1;
                        }
                    }).fail(function (data) {
                        var err=JSON.parse(data.responseText);
                        if(err.phone){
                            _this.userError.userPhoneErrors=err.phone[0];
                            _this.userError.is_userPhone=1;
                        }
                    });
                },
                initAccount:function(){
                    var _this=this;
                    if(_this.userError.is_userPhone ||  !_this.userOperation.userId){
                        _this.userError.userPhoneErrors="未知用户不能初始化！";
                        _this.userError.is_userPhone=1;

                        return false;
                    }
                    var url = "{{url('admin/user')}}"+"/"+_this.userOperation.userId;
                    $.ajax({
                        url:url,
                        data:{phone: _this.userOperation.userPhone,_method:'PUT'},
                        type:'POST',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        dataType:'json'
                    }).done(function(data){
                        if(data.ret_num==0){
                            _this.userOperation.phone = '';
                            _this.userOperation.userPhone = '';
                            _this.userOperation.userName = '';
                            _this.userOperation.companyName = '';
                            _this.userOperation.userId = '';
                            _this.userError.is_userPhone=0;
                            _this.userError.userPhoneErrors='';
                            $("#init-user").modal('hide');
                            alert(data.ret_msg);

                            return true;
                        }else{
                            _this.userError.userPhoneErrors="网络错误";
                            _this.userError.is_userPhone=1;

                            return false;
                        }
                    }).fail(function (data) {
                        var err=JSON.parse(data.responseText);
                        if(err.phone){
                            _this.userError.userPhoneErrors=err.phone[0];
                            _this.userError.is_userPhone=1;

                            return false;
                        }
                    });
                },
                newAccount: function () {
                    var url = "{{url('admin/account')}}";
                    var _this=this;
                    _this.managerError = {
                        name: {
                            is_managerName:0,
                            managerNameErrors:''
                        },
                        account: {
                            is_managerAccount:0,
                            managerAccountErrors:''
                        },
                        password: {
                            is_managerPassword:0,
                            managerPasswordErrors:''
                        },
                        roles: {
                            is_managerRoles:0,
                            managerRolesErrors:''
                        }
                    };
                    if(!_this.createManager.managerName || typeof _this.createManager.managerName=='undefined'){
                        _this.managerError.name.managerNameErrors='姓名必填！';
                        _this.managerError.name.is_managerName=1;
                        return false;
                    }
                    if(!_this.createManager.managerAccount || typeof _this.createManager.managerAccount=='undefined'){
                        _this.managerError.account.managerAccountErrors='登录账户必填！';
                        _this.managerError.account.is_managerAccount=1;
                        return false;
                    }
                    if(!_this.createManager.managerAccount.match(/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/)){
                        _this.managerError.account.managerAccountErrors='登录账户必为邮箱！';
                        _this.managerError.account.is_managerAccount=1;
                        return false;
                    }
                    if(!_this.createManager.managerPassword || typeof _this.createManager.managerPassword=='undefined'){
                        _this.managerError.password.managerPasswordErrors='初始密码必填！';
                        _this.managerError.password.is_managerPassword=1;
                        return false;
                    }
                    if(_this.createManager.managerPassword.length<6){
                        _this.managerError.password.managerPasswordErrors='初始密码至少6位！';
                        _this.managerError.password.is_managerPassword=1;
                        return false;
                    }
                    if(!_this.createManager.managerPassword.match(/=|\+|-|@|_|\*|[a-zA-Z]/g)){
                        _this.managerError.password.managerPasswordErrors='"A-Z" "a-z" "+" "_" "*" "=" "-" "@"至少存在1项！';
                        _this.managerError.password.is_managerPassword=1;
                        return false;
                    }
                    if(!_this.createManager.managerRoles.length || typeof _this.createManager.managerRoles=='undefined'){
                        _this.managerError.roles.managerRolesErrors='权限必选！';
                        _this.managerError.roles.is_managerRoles=1;
                        return false;
                    }
                    if(_this.createManager.managerRoles.length!=1){
                        _this.managerError.roles.managerRolesErrors='权限最多选1项！';
                        _this.managerError.roles.is_managerRoles=1;
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
                            name: _this.createManager.managerName,
                            account: _this.createManager.managerAccount,
                            pwd: _this.createManager.managerPassword,
                            role: _this.createManager.managerRoles
                        },
                        type:'POST'
                    })
                    .done(function(data){
                        if(data.ret_num==0){
                            _this.managerError = {
                                name: {
                                    is_managerName:0,
                                    managerNameErrors:''
                                },
                                account: {
                                    is_managerAccount:0,
                                    managerAccountErrors:''
                                },
                                password: {
                                    is_managerPassword:0,
                                    managerPasswordErrors:''
                                },
                                roles: {
                                    is_managerRoles:0,
                                    managerRolesErrors:''
                                }
                            };
                            _this.createManager.managerName= '';
                            _this.createManager.managerAccount= '';
                            _this.createManager.managerPassword= '';
                            _this.createManager.managerRoles= [];
                            _this.newManagerList.push(data.data);
                            $('#create-manager').modal('hide');
                            alert(data.ret_msg);
                        }else{
                            _this.managerError.name.is_managerName=1;
                            _this.managerError.name.managerNameErrors=data.ret_msg;
                        }
                    })
                    .fail(function(data){
                        var errs=JSON.parse(data.responseText);
                        if(errs.name){
                            _this.managerError.name.is_managerName=1;
                            _this.managerError.name.managerNameErrors=errs.name[0];
                        }
                        if(errs.account){
                            _this.managerError.account.is_managerAccount=1;
                            _this.managerError.account.managerAccountErrors=errs.account[0];
                        }
                        if(errs.pwd){
                            _this.managerError.password.is_managerPassword=1;
                            _this.managerError.password.managerPasswordErrors=errs.pwd[0];
                        }
                        if(errs['role']){
                            _this.managerError.roles.is_managerRoles=1;
                            _this.managerError.roles.managerRolesErrors=errs['role'][0];
                        }
                    });
                },
                resetPassword: function () {
                    var _this=this;
                    _this.restPasswordError = {
                        is_newPassword: 0,
                        newPasswordErrors: '',
                        is_confirmPassword: 0,
                        confirmPasswordErrors: ''
                    };
                    if(!_this.restPassword.newPassword || typeof _this.restPassword.newPassword=='undefined'){
                        _this.restPasswordError.newPasswordErrors='密码必填！';
                        _this.restPasswordError.is_newPassword=1;
                        return false;
                    }
                    if(_this.restPassword.newPassword.length<6){
                        _this.restPasswordError.newPasswordErrors='新密码至少6位！';
                        _this.restPasswordError.is_newPassword=1;
                        return false;
                    }
                    if(!_this.restPassword.newPassword.match(/=|\+|-|@|_|\*|[a-zA-Z]/g)){
                        _this.restPasswordError.newPasswordErrors='"A-Z" "a-z" "+" "_" "*" "=" "-" "@"至少存在1项！';
                        _this.restPasswordError.is_newPassword=1;
                        return false;
                    }
                    if((_this.restPassword.newPassword)!==(_this.restPassword.confirmPassword)){
                        _this.restPasswordError.confirmPasswordErrors='两次密码不一致！';
                        _this.restPasswordError.is_confirmPassword=1;
                        return false;
                    }
                    var url = "{{url('admin/super/reset_password')}}"+"/"+_this.restPassword.managerId;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        data: {
                            pwd: _this.restPassword.newPassword,
                            pwd_confirmation: _this.restPassword.confirmPassword,
                            _method:'PUT'
                        },
                        type:'POST'
                    }).done(function (data) {
                        if(data.ret_num==0){
                            _this.restPassword.newPassword='';
                            _this.restPassword.confirmPassword='';
                            _this.restPassword.managerId='';
                            $("#reset-password").modal('hide');
                            alert(data.ret_msg);
                        }else{
                            _this.restPasswordError.newPasswordErrors=data.ret_msg;
                            _this.restPasswordError.is_newPassword=1;
                        }
                    }).fail(function (data) {
                        var errs=JSON.parse(data.responseText);
                        if(errs.pwd){
                            _this.restPasswordError.is_newPassword=1;
                            _this.restPasswordError.newPasswordErrors=errs.pwd[0];
                        }
                        if(errs.pwd_confirmation){
                            _this.restPasswordError.is_confirmPassword=1;
                            _this.restPasswordError.confirmPasswordErrors=errs.pwd_confirmation[0];
                        }
                    });
                },
                searchManager: function () {
                    var _this = this;
                    var url = "{{ url('admin/manager') }}";
                    _this.newManagerList = [];
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            'name': _this.searchInfo.name
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.searchInfo.searchName = _this.searchInfo.name;
                        _this.managerList = data.data;
                        _this.searchInfo.pageInfo = {
                            total: data.total,
                            per_page: data.per_page,
                            current_page: data.current_page,
                            last_page: data.last_page,
                            next_page_url: data.next_page_url,
                            prev_page_url: data.prev_page_url,
                            from: data.from,
                            to: data.to
                        };
                    }).fail(function(){
                        alert("网络错误！");
                    });
                },
                moreManager: function () {
                    var _this = this;
                    var url = "{{ url('admin/manager') }}";
                    _this.newManagerList = [];
                    _this.searchInfo.searchName = _this.searchInfo.name;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            'name': _this.searchInfo.searchName,
                            'page': _this.searchInfo.pageInfo.current_page
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.managerList = data.data;
                        _this.searchInfo.pageInfo = {
                            total: data.total,
                            per_page: data.per_page,
                            current_page: data.current_page,
                            last_page: data.last_page,
                            next_page_url: data.next_page_url,
                            prev_page_url: data.prev_page_url,
                            from: data.from,
                            to: data.to
                        };
                    }).fail(function(){
                        alert("网络错误！");
                    });
                }
            },
            events:{
                'mg-id': function (managerId) {
                    this.restPassword.managerId=managerId;
                }
            }
        });
    </script>
@endsection