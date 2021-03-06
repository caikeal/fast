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

        .table-roles{
            overflow-x: scroll;
            margin-top: 30px;
        }

        .table-roles thead{
            white-space: nowrap;
            background-color: #c5e4f7;
        }

        .list.editing .view, .list .edit{
            display: none;
        }

        .list.editing .edit, .list .view{
            display: block;
        }

        .list .edit input{
            padding: 2px 5px;
            outline: 0;
            border: 1px solid #b5b5b5;
        }

        .list .point{
            cursor: pointer;
        }

        .list.editing .point{
            border-top: none;
        }

        .form-horizontal .form-group.permission-group{
            border: 1px solid #ccc;
            background-color: #fff;
            margin-left: 0;
            margin-right: 0;
            padding: 10px;
            border-radius: 5px;
        }

        .form-horizontal .form-group.permission-group .col-xs-10{
            border-left: 1px solid #e2e2e2;
        }

        .remind{
            color: #df4c43;
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
            <a id="identityManage" class="btn btn-link member-btn" data-toggle="modal" @click.prevent="identityList">
                <i class="fa fa-bolt fa-sm poster-btn btn-member"></i>
                <strong>身份管理</strong>
            </a>
            <a id="roleManage" class="btn btn-link member-btn" data-toggle="modal" @click.prevent="startPermission">
                <i class="fa fa-lock fa-sm poster-btn btn-add-account"></i>
                <strong>权限管理</strong>
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
                        <th>操作3</th>
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
                            <td>@{{manager['roles'].length ? manager['roles'][0]['level'] : ''}}级管理员</td>
                            <td>
                                <reset-pwd-btn :manager-id="manager.id"></reset-pwd-btn>
                            </td>
                            <td>
                                <toggle-manager :manager-id="manager.id" :manager-status='manager.deleted_at?"启用":"停用"'></toggle-manager>
                            </td>
                            <td>
                                <role-manage-btn :list="manager"></role-manage-btn>
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
                            <td>@{{manager['roles'].length?manager['roles'][0]['level']:''}}级管理员</td>
                            <td>
                                <reset-pwd-btn :manager-id="manager.id"></reset-pwd-btn>
                            </td>
                            <td>
                                <toggle-manager :manager-id="manager.id" :manager-status='manager.deleted_at?"启用":"停用"'></toggle-manager>
                            </td>
                            <td>
                                <role-manage-btn :list="manager"></role-manage-btn>
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

        <!-- modal identityManage -->
        <div class="modal fade" id="identity-manage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">身份管理</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <!-- 创建角色 -->
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':identityError.label.isInvalid}">
                                    <label class="col-lg-2 control-label lable-xs-center">身份名称:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" name="label" placeholder="身份名称"
                                               autocomplete="off" v-model="identity.label">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':identityError.label.isInvalid?'block':'none'}">@{{ identityError.label.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': identityError.name.isInvalid}">
                                    <label class="col-lg-2 control-label lable-xs-center">身份类型:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" name="account"
                                               placeholder="身份类型（英文,如：salaryLeader）" autocomplete="off" v-model="identity.name">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': identityError.name.isInvalid?'block':'none'}">@{{ identityError.name.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': identityError.level.isInvalid}">
                                    <label class="col-lg-2 control-label lable-xs-center">权限等级:</label>

                                    <div class="col-lg-10">
                                        <div class="radio inline-block">
                                            <div class="custom-radio m-right-xs">
                                                <input type="radio" id="inlineRadio1" name="inlineRadio" value="1" v-model="identity.level" @change="getAffiliation">
                                                <label for="inlineRadio1"></label>
                                            </div>
                                            <div class="inline-block vertical-top">1级管理员</div>
                                        </div>
                                        <div class="radio inline-block">
                                            <div class="custom-radio m-right-xs">
                                                <input type="radio" id="inlineRadio2" name="inlineRadio" value="2" v-model="identity.level" @change="getAffiliation">
                                                <label for="inlineRadio2"></label>
                                            </div>
                                            <div class="inline-block vertical-top">2级管理员</div>
                                        </div>
                                        <div class="radio inline-block">
                                            <div class="custom-radio m-right-xs">
                                                <input type="radio" id="inlineRadio3" name="inlineRadio" value="3" v-model="identity.level" @change="getAffiliation">
                                                <label for="inlineRadio3"></label>
                                            </div>
                                            <div class="inline-block vertical-top">3级管理员</div>
                                        </div>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': identityError.level.isInvalid?'block':'none'}">@{{ identityError.level.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': identityError.relate.isInvalid}">
                                    <label for="relate" class="col-lg-2 control-label lable-xs-center">隶属关系:</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="relate" v-model="identity.relate">
                                            <option :value="rels.id" v-for="rels in identity.relations">@{{ rels.label }}</option>
                                        </select>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': identityError.relate.isInvalid?'block':'none'}">@{{ identityError.relate.msg }}</p>
                                </div>
                                <div class="row">
                                    <div class="col-sm-offset-2 col-sm-8">
                                        <a class="btn btn-primary block m-top-md" @click.prevent="saveIdentity">保存角色</a>
                                    </div>
                                </div>
                            </form>
                            <!-- /创建角色 -->

                            <!-- 角色列表 -->
                            <div class="table-roles">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>身份名</th>
                                        <th>身份类型</th>
                                        <th>管理员等级</th>
                                        <th>隶属关系</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr :class="['list', {'editing': identity.change.label==item}]" v-for="item in identity.list">
                                        <td class="view">@{{ item.label }}</td>
                                        <td class="edit"><input type="text" @keyup.esc="cancelIdentity()" v-model="identity.change.cache"></td>
                                        <td>@{{ item.name }}</td>
                                        <td>@{{ item.level }}</td>
                                        <td>@{{ item.father.label }}</td>
                                        <td class="view point"><a @click.prevent="changeIdentity(item)">修改</a></td>
                                        <td class="edit point">
                                            <a @click.prevent="updateIdentity(item)">保存</a>
                                            <a @click.prevent="cancelIdentity(item)">取消</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /角色列表 -->
                        </div>
                    </div>
                    {{--<div class="modal-footer">--}}

                    {{--</div>--}}
                </div>
            </div>
        </div>
        <!-- /modal identityManage -->

        <!-- modal assignmentManage-->
        <div class="modal fade" id="assignment-manage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">权限管理</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':permissionError.role.isInvalid}">
                                    <label for="name" class="col-xs-2 control-label lable-xs-center">帐号类别:</label>

                                    <div class="col-xs-10">
                                        <select class="form-control" name="relate" v-model="permission.role" @change="choosePermission">
                                            <option :value="roleItem.id" v-for="roleItem in permission.roles">@{{ roleItem.label }}</option>
                                        </select>
                                    </div>

                                    <p class="help-block col-xs-offset-2 col-xs-10" :style="{'display':permissionError.role.isInvalid?'block':'none'}">@{{ permissionError.role.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error':permissionError.level.isInvalid}">
                                    <label for="name" class="col-xs-2 control-label lable-xs-center">所属等级:</label>

                                    <div class="col-xs-10">
                                        <input type="text" class="form-control" readonly :value="permission.level">
                                    </div>

                                    <p class="help-block col-xs-offset-2 col-xs-10" :style="{'display':permissionError.level.isInvalid?'block':'none'}">@{{ permissionError.level.msg }}</p>
                                </div>

                                <section class="permission-list">
                                    <div class="form-group permission-group" :class="{'has-error': permissionError.task.isInvalid}">
                                        <div class="col-xs-2">
                                            <div class="checkbox inline-block">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="task-permission" class="checkbox-blue" value=1
                                                            v-model="permission.task.choose" @click="togglePermission(permission.task)">
                                                    <label for="task-permission"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    任务
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-10">
                                            <div class="checkbox inline-block" v-for="op in permission.task.option">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="task-permission-@{{ op.id }}" class="checkbox-blue" :value="op.id"
                                                                v-model="permission.task.sub">
                                                    <label for="task-permission-@{{ op.id }}"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    @{{ op.label }}
                                                </div>
                                                &nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                        <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': permissionError.task.isInvalid?'block':'none'}">@{{ permissionError.task.msg }}</p>
                                    </div>
                                    <div class="form-group permission-group" :class="{'has-error': permissionError.person.isInvalid}">
                                        <div class="col-xs-2">
                                            <div class="checkbox inline-block">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="person-permission" class="checkbox-blue" value=1
                                                            v-model="permission.person.choose" @click="togglePermission(permission.person)">
                                                    <label for="person-permission"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    人员
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-10">
                                            <div class="checkbox inline-block" v-for="op1 in permission.person.option">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="person-permission-@{{ op1.id }}" class="checkbox-blue" :value="op1.id"
                                                                v-model="permission.person.sub">
                                                    <label for="person-permission-@{{ op1.id }}"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    @{{ op1.label }}
                                                </div>&nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                        <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': permissionError.person.isInvalid?'block':'none'}">@{{ permissionError.person.msg }}</p>
                                    </div>
                                    <div class="form-group permission-group" :class="{'has-error': permissionError.system.isInvalid}">
                                        <div class="col-xs-2">
                                            <div class="checkbox inline-block">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="system-permission" class="checkbox-blue" value=1
                                                           v-model="permission.system.choose" @click="togglePermission(permission.system)">
                                                    <label for="system-permission"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    系统
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-10">
                                            <div class="checkbox inline-block" v-for="op2 in permission.system.option">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="system-permission-@{{ op2.id }}" class="checkbox-blue" :value="op2.id"
                                                           v-model="permission.system.sub">
                                                    <label for="system-permission-@{{ op2.id }}"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    @{{ op2.label }}
                                                </div>
                                                &nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                        <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': permissionError.system.isInvalid?'block':'none'}">@{{ permissionError.system.msg }}</p>
                                    </div>
                                    <div class="form-group permission-group" :class="{'has-error': permissionError.salary.isInvalid}">
                                        <div class="col-xs-2">
                                            <div class="checkbox inline-block">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="salary-permission" class="checkbox-blue" value=1
                                                           v-model="permission.salary.choose" @click="togglePermission(permission.salary)">
                                                    <label for="salary-permission"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    薪资
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-10">
                                            <div class="checkbox inline-block" v-for="op3 in permission.salary.option">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="salary-permission-@{{ op3.id }}" class="checkbox-blue" :value="op3.id"
                                                           v-model="permission.salary.sub">
                                                    <label for="salary-permission-@{{ op3.id }}"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    @{{ op3.label }}
                                                </div>
                                                &nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                        <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': permissionError.salary.isInvalid?'block':'none'}">@{{ permissionError.salary.msg }}</p>
                                    </div>
                                    <div class="form-group permission-group" :class="{'has-error': permissionError.compensation.isInvalid}">
                                        <div class="col-xs-2">
                                            <div class="checkbox inline-block">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="compensation-permission" class="checkbox-blue" value=1
                                                           v-model="permission.compensation.choose" @click="togglePermission(permission.compensation)">
                                                    <label for="compensation-permission"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    理赔
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-10">
                                            <div class="checkbox inline-block" v-for="op4 in permission.compensation.option">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="compensation-permission-@{{ op4.id }}" class="checkbox-blue" :value="op4.id"
                                                           v-model="permission.compensation.sub">
                                                    <label for="compensation-permission-@{{ op4.id }}"></label>
                                                </div>
                                                <div class="inline-block vertical-top">
                                                    @{{ op4.label }}
                                                </div>
                                                &nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                        <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': permissionError.compensation.isInvalid?'block':'none'}">@{{ permissionError.compensation.msg }}</p>
                                    </div>
                                    <div class="form-group permission-group" :class="{'has-error': permissionError.statistics.isInvalid}">
                                    <div class="col-xs-2">
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="statistics-permission" class="checkbox-blue" value=1
                                                       v-model="permission.statistics.choose" @click="togglePermission(permission.statistics)">
                                                <label for="statistics-permission"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                分析
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-10">
                                        <div class="checkbox inline-block" v-for="op5 in permission.statistics.option">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="statistics-permission-@{{ op5.id }}" class="checkbox-blue" :value="op5.id"
                                                       v-model="permission.statistics.sub">
                                                <label for="statistics-permission-@{{ op5.id }}"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                @{{ op5.label }}
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': permissionError.statistics.isInvalid?'block':'none'}">@{{ permissionError.statistics.msg }}</p>
                                </div>
                                </section>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-primary block m-top-md" @click.prevent="updatePermission">保存</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal assignmentManage-->

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

        <!-- modal roleManage-->
        <div class="modal fade" id="role-manage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">权限编辑</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <!-- 权限编辑 -->
                            <form action="" class="form-horizontal">
                                <div class="form-group" :class="{'has-error':managerAccountError.name.isInvalid}">
                                    <label class="col-lg-2 control-label lable-xs-center">姓名:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" name="label" placeholder="姓名"
                                               autocomplete="off" v-model="managerAccount.name.current">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display':managerAccountError.name.isInvalid?'block':'none'}">@{{ managerAccountError.name.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': managerAccountError.account.isInvalid}">
                                    <label class="col-lg-2 control-label lable-xs-center">帐号:</label>

                                    <div class=" col-lg-10">
                                        <input type="text" class="form-control" name="account"
                                               placeholder="帐号" autocomplete="off" v-model="managerAccount.account.current">
                                    </div>

                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerAccountError.account.isInvalid?'block':'none'}">@{{ managerAccountError.account.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': managerAccountError.level.isInvalid}">
                                    <label class="col-lg-2 control-label lable-xs-center">权限等级:</label>

                                    <div class="col-lg-10">
                                        <div class="radio inline-block">
                                            <div class="custom-radio m-right-xs">
                                                <input type="radio" id="radio-account-1" name="radio-account-1" :value=1 v-model="managerAccount.level" @change="getRoles">
                                                <label for="radio-account-1"></label>
                                            </div>
                                            <div class="inline-block vertical-top">1级管理员</div>
                                        </div>
                                        <div class="radio inline-block">
                                            <div class="custom-radio m-right-xs">
                                                <input type="radio" id="radio-account-2" name="radio-account-2" :value=2 v-model="managerAccount.level" @change="getRoles">
                                                <label for="radio-account-2"></label>
                                            </div>
                                            <div class="inline-block vertical-top">2级管理员</div>
                                        </div>
                                        <div class="radio inline-block">
                                            <div class="custom-radio m-right-xs">
                                                <input type="radio" id="radio-account-3" name="radio-account-3" :value=3 v-model="managerAccount.level" @change="getRoles">
                                                <label for="radio-account-3"></label>
                                            </div>
                                            <div class="inline-block vertical-top">3级管理员</div>
                                        </div>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerAccountError.level.isInvalid?'block':'none'}">@{{ managerAccountError.level.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': managerAccountError.role.isInvalid}">
                                    <label for="role" class="col-lg-2 control-label lable-xs-center">职位:</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="role" v-model="managerAccount.role.current" @change="getRelations">
                                            <option :value="roleItem.id" v-for="roleItem in managerAccount.roleOptions">@{{ roleItem.label }}</option>
                                        </select>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerAccountError.role.isInvalid?'block':'none'}">@{{ managerAccountError.role.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': managerAccountError.superior.isInvalid}">
                                    <label for="superior" class="col-lg-2 control-label lable-xs-center">隶属上级:</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="superior" v-model="managerAccount.superior.current">
                                            <option :value="sup.id" v-for="sup in managerAccount.superiorOptions">@{{ sup.name }}</option>
                                        </select>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerAccountError.superior.isInvalid?'block':'none'}">@{{ managerAccountError.superior.msg }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': managerAccountError.equal.isInvalid}">
                                    <label for="equal" class="col-lg-2 control-label lable-xs-center">下级隶属:</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="equal" v-model="managerAccount.equal.current">
                                            <option :value="equal.id" v-for="equal in managerAccount.equalOptions">@{{ equal.name }}</option>
                                        </select>
                                    </div>
                                    <p class="help-block col-lg-offset-2 col-lg-10" :style="{'display': managerAccountError.equal.isInvalid?'block':'none'}">@{{ managerAccountError.equal.msg }}</p>
                                </div>

                            </form>
                            <!-- /权限编辑 -->
                            <div class="remind">请谨慎修改！确保<b>上下级关系</b>在<b>升职</b>或者<b>降职</b>时<b>转移</b>正确</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <a class="btn btn-primary block m-top-md" @click.prevent="saveManagerRole">保存</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal roleManage-->

        <template id="reset-btn-template"  style="display: none">
            <a class="btn btn-success" data-target="#reset-password" data-toggle="modal" @click="notify">重置密码</a>
        </template>

        <template id="toggle-manager-template" style="display: none">
            <a class="btn" :class="(managerStatus=='停用')?'btn-danger':'btn-info'" @click="toggleManager">@{{managerStatus}}</a>
        </template>

        <template id="role-manage-template" style="display: none">
            <a class="btn btn-info" data-target="#role-manage" data-toggle="modal" @click="roleManageList">编辑</a>
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
                            alert("修改成功！请您确保其上下属隶属关系正确！");
                        }else{
                            alert("修改失败！");
                        }
                    }).fail(function (err) {
                        if (err.hasOwnProperty('responseJSON')) {
                            if (err.responseJSON.hasOwnProperty('invalid')) {
                                alert(err.responseJSON.invalid);
                                return false;
                            }
                        }
                        alert("网络错误！");
                    });
                }
            }
        });
        Vue.component('role-manage-btn',{
            template:'#role-manage-template',
            props:{
                list:{
                    required:true
                }
            },
            methods:{
                roleManageList: function () {
                    var _this=this;
                    var url="{{url('admin/manager-level-list')}}";
                    _this.$dispatch('mg-list', _this.list);
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content')
                        },
                        timeout:60000,
                        type:'GET',
                        data:{
                            manager_id: _this.list.id,
                            role_id: _this.list.roles[0]['id']
                        }
                    }).done(function (data) {
                        _this.$dispatch('role-manage-list', data.data);
                    }).fail(function (err) {
                        if (err.hasOwnProperty('responseJSON')) {
                            if (err.responseJSON.hasOwnProperty('invalid')) {
                                alert(err.responseJSON.invalid);
                                return false;
                            }
                        }
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
                },
                identity: {
                    name: '',
                    label: '',
                    level: null,
                    relate: null,
                    relations: [],
                    list: [],
                    change: {
                        label: null,
                        cache: ''
                    }
                },
                identityError: {
                    name: {
                        isInvalid: 0,
                        msg: ''
                    },
                    label: {
                        isInvalid: 0,
                        msg: ''
                    },
                    level: {
                        isInvalid: 0,
                        msg: ''
                    },
                    relate: {
                        isInvalid: 0,
                        msg: ''
                    }
                },
                permission: {
                    roles: [],
                    role: null,
                    level: null,
                    task: {
                        choose: false,
                        option: [
                            {
                                id: 1,
                                label: '查看任务'
                            },
                            {
                                id: 2,
                                label: '新增企业'
                            },
                            {
                                id: 3,
                                label: '新增任务'
                            },
                            {
                                id: 4,
                                label: '编辑任务'
                            },
                            {
                                id: 10,
                                label: '删除任务'
                            }
                        ],
                        sub: []
                    },
                    person: {
                        choose: false,
                        option: [
                            {
                                id: 5,
                                label: '人员管理'
                            },
                            {
                                id: 6,
                                label: '创建账号'
                            }
                        ],
                        sub: []
                    },
                    system: {
                        choose: false,
                        option: [
                            {
                                id: 7,
                                label: '系统管理'
                            },
                            {
                                id: 12,
                                label: '系统消息'
                            }
                        ],
                        sub: []
                    },
                    salary: {
                        choose: false,
                        option: [
                            {
                                id: 8,
                                label: '薪资管理'
                            }
                        ],
                        sub: []
                    },
                    compensation: {
                        choose: false,
                        option: [
                            {
                                id: 9,
                                label: '理赔管理'
                            }
                        ],
                        sub: []
                    },
                    statistics: {
                        choose: false,
                        option: [
                            {
                                id: 11,
                                label: '数据分析'
                            }
                        ],
                        sub: []
                    }
                },
                permissionError: {
                    role: {
                        isInvalid: 0,
                        msg: ''
                    },
                    level: {
                        isInvalid: 0,
                        msg: ''
                    },
                    task: {
                        isInvalid: 0,
                        msg: ''
                    },
                    person: {
                        isInvalid: 0,
                        msg: ''
                    },
                    system: {
                        isInvalid: 0,
                        msg: ''
                    },
                    salary: {
                        isInvalid: 0,
                        msg: ''
                    },
                    compensation: {
                        isInvalid: 0,
                        msg: ''
                    },
                    statistics: {
                        isInvalid: 0,
                        msg: ''
                    }
                },
                managerAccount: {
                    roleOptions: null,
                    superiorOptions: null,
                    equalOptions: null,
                    name: {
                        origin: '',
                        current: ''
                    },
                    account: {
                        origin: '',
                        current: ''
                    },
                    role: {
                        origin: '',
                        current: ''
                    },
                    superior: {
                        origin: '',
                        current: ''
                    },
                    equal: {
                        origin: '',
                        current: ''
                    },
                    level: null
                },
                managerAccountError: {
                    name: {
                        isInvalid: 0,
                        msg: ''
                    },
                    level: {
                        isInvalid: 0,
                        msg: ''
                    },
                    account: {
                        isInvalid: 0,
                        msg: ''
                    },
                    role: {
                        isInvalid: 0,
                        msg: ''
                    },
                    superior: {
                        isInvalid: 0,
                        msg: ''
                    },
                    equal: {
                        isInvalid: 0,
                        msg: ''
                    },
                }
            },
            watch: {
                'permission.task.sub': function (newVal, oldVal) {
                    if (newVal.length == 0) {
                        this.permission.task.choose = false;
                    }else{
                        this.permission.task.choose = true;
                    }
                },
                'permission.person.sub': function (newVal, oldVal) {
                    if (newVal.length == 0) {
                        this.permission.person.choose = false;
                    }else{
                        this.permission.person.choose = true;
                    }
                },
                'permission.system.sub': function (newVal, oldVal) {
                    if (newVal.length == 0) {
                        this.permission.system.choose = false;
                    }else{
                        this.permission.system.choose = true;
                    }
                },
                'permission.salary.sub': function (newVal, oldVal) {
                    if (newVal.length == 0) {
                        this.permission.salary.choose = false;
                    }else{
                        this.permission.salary.choose = true;
                    }
                },
                'permission.compensation.sub': function (newVal, oldVal) {
                    if (newVal.length == 0) {
                        this.permission.compensation.choose = false;
                    }else{
                        this.permission.compensation.choose = true;
                    }
                },
                'permission.statistics.sub': function (newVal, oldVal) {
                    if (newVal.length == 0) {
                        this.permission.statistics.choose = false;
                    }else{
                        this.permission.statistics.choose = true;
                    }
                },
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
                    .fail(function(err){
                        if (err.hasOwnProperty('responseJSON')) {
                            if (err.responseJSON.hasOwnProperty('invalid')) {
                                alert(err.responseJSON.invalid);
                            }
                        } else {
                            alert("网络错误！");
                        }
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
                },
                getAffiliation: function () {
                    var _this = this;
                    var url = "{{ url('admin/affiliation') }}";
                    _this.identity.relations = [];
                    _this.identity.relate = 0;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            'level': _this.identity.level,
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.identity.relations = data.data;
                        return false;
                    }).fail(function(error){
                        errs = error.responseJSON;
                        if (errs.level.length){
                            _this.identityError.level.isInvalid = 1;
                            _this.identityError.level.msg = errs.level[0];
                            return false;
                        }else{
                            alert("网络错误！");
                        }
                    });
                },
                saveIdentity: function () {
                    var _this = this;
                    var url = "{{ url('admin/role-create') }}";
                    _this.identityError = {
                        name: {
                            isInvalid: 0,
                            msg: ''
                        },
                        label: {
                            isInvalid: 0,
                            msg: ''
                        },
                        level: {
                            isInvalid: 0,
                            msg: ''
                        },
                        relate: {
                            isInvalid: 0,
                            msg: ''
                        }
                    };
                    if (!_this.identity.name.trim()) {
                        _this.identityError.name.isInvalid = 1;
                        _this.identityError.name.msg = '请填写！';

                        return false;
                    }
                    if (!_this.identity.label.trim()) {
                        _this.identityError.label.isInvalid = 1;
                        _this.identityError.label.msg = '请填写！';

                        return false;
                    }
                    if (!/^(salary|compensate|welfare|system){1}[A-Z]([a-z])+/.test(_this.identity.name.trim())) {
                        _this.identityError.name.isInvalid = 1;
                        _this.identityError.name.msg = '必须以\'salary,compensate,welfare,system\'中任意1个开头，后面跟有意义的单词，并且首字母大写';

                        return false;
                    }
                    if (!_this.identity.level) {
                        _this.identityError.level.isInvalid = 1;
                        _this.identityError.level.msg = '请选择！';

                        return fasle;
                    }
                    if (!_this.identity.relate) {
                        _this.identityError.relate.isInvalid = 1;
                        _this.identityError.relate.msg = '请选择！';

                        return fasle;
                    }
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            'name': _this.identity.name.trim(),
                            'label': _this.identity.label.trim(),
                            'level': _this.identity.level,
                            'relate': _this.identity.relate,
                        },
                        timeout:60000,
                        type:'POST'
                    }).done(function(data){
                        if (data.message == 'success' && data.hasOwnProperty('data')){
                            _this.identity.list.push({
                                id: data.data.id,
                                label: _this.identity.label,
                                name: _this.identity.name,
                                level: _this.identity.level,
                                pid: _this.identity.relate,
                                father: {
                                    id: _this.identity.relate,
                                    label: _this.identity.relations.find(function(val){
                                        if (val.id == _this.identity.relate){
                                            return val.label;
                                        }
                                    }).label
                                }
                            });
                            alert("保存成功！");
                        }
                        _this.identity.name = '';
                        _this.identity.label = '';
                        _this.identity.level = 0;
                        _this.identity.relate = 0;
                        _this.identity.relations = [];
                        return true;
                    }).fail(function(error){
                        errs = error.responseJSON;
                        if (errs.hasOwnProperty('name')){
                            _this.identityError.name.isInvalid = 1;
                            _this.identityError.name.msg = errs.name[0];
                            return false;
                        }
                        if (errs.hasOwnProperty('label')){
                            _this.identityError.label.isInvalid = 1;
                            _this.identityError.label.msg = errs.label[0];
                            return false;
                        }
                        if (errs.hasOwnProperty('level')){
                            _this.identityError.level.isInvalid = 1;
                            _this.identityError.level.msg = errs.level[0];
                            return false;
                        }
                        if (errs.hasOwnProperty('relate')){
                            _this.identityError.relate.isInvalid = 1;
                            _this.identityError.relate.msg = errs.relate[0];
                            return false;
                        }
                        alert("网络错误！");
                    });
                },
                identityList: function () {
                    var _this = this;
                    var url = "{{ url('admin/role-list') }}";
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.identity.list = data.data;
                        $("#identity-manage").modal('show');

                        return true;
                    }).fail(function(error){
                        alert("网络错误！");

                        return false;
                    });
                },
                changeIdentity: function (item) {
                    this.identity.change.label = item;
                    this.identity.change.cache = item.label;
                },
                cancelIdentity: function (item) {
                    this.identity.change.label = null;
                    this.identity.change.cache = '';
                },
                updateIdentity: function (item) {
                    var _this = this;
                    var url = "{{ url('admin/role-update') }}";
                    if (_this.identity.change.cache.trim() == _this.identity.change.label.label){
                        _this.identity.change.label = null;
                        _this.identity.change.cahce = '';
                        return true;
                    }
                    if (!_this.identity.change.cache.trim()){
                        alert("请勿输入空值！");
                        return false
                    }

                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            label: _this.identity.change.cache.trim(),
                            id: _this.identity.change.label.id
                        },
                        timeout:60000,
                        type:'POST'
                    }).done(function(data){
                        item.label = _this.identity.change.cache.trim();
                        _this.identity.change.label = null;
                        _this.identity.change.cahce = '';

                        return true;
                    }).fail(function(error){
                        var err = error.responseJSON;
                        if (err.hasOwnProperty('invalid')){
                            alert(err.invalid);

                            return false;
                        }
                        if (err.hasOwnProperty('id')){
                            alert(err.id[0]);

                            return false;
                        }
                        if (err.hasOwnProperty('label')){
                            alert(err.label[0]);

                            return false;
                        }
                        alert("网络错误！");

                        return false;
                    });
                },
                startPermission: function () {
                    var _this = this;
                    var url = "{{ url('admin/permission-list') }}";
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.permission.roles = data.data.roles;
                        $("#assignment-manage").modal('show');
                        return true;
                    }).fail(function(error){
                        var err = error.responseJSON;
                        if (err.hasOwnProperty('invalid')){
                            alert(err.invalid);

                            return false;
                        }
                        alert("网络错误！");

                        return false;
                    });
                },
                choosePermission: function () {
                    var _this = this;
                    var url = "{{ url('admin/permission-own') }}";
                    _this.permission.task.choose = false;
                    _this.permission.task.sub = [];
                    _this.permission.person.choose = false;
                    _this.permission.person.sub = [];
                    _this.permission.system.choose = false;
                    _this.permission.system.sub = [];
                    _this.permission.salary.choose = false;
                    _this.permission.salary.sub = [];
                    _this.permission.compensation.choose = false;
                    _this.permission.compensation.sub = [];
                    _this.permission.statistics.choose = false;
                    _this.permission.statistics.sub = [];
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            id: _this.permission.role
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        if (data.data.permission_choose.hasOwnProperty('task')){
                            _this.permission.task.sub = data.data.permission_choose.task;
                        }
                        if (data.data.permission_choose.hasOwnProperty('person')){
                            _this.permission.person.sub = data.data.permission_choose.person;
                        }
                        if (data.data.permission_choose.hasOwnProperty('system')){
                            _this.permission.system.sub = data.data.permission_choose.system;
                        }
                        if (data.data.permission_choose.hasOwnProperty('salary')){
                            _this.permission.salary.sub = data.data.permission_choose.salary;
                        }
                        if (data.data.permission_choose.hasOwnProperty('compensation')){
                            _this.permission.compensation.sub = data.data.permission_choose.compensation;
                        }
                        if (data.data.permission_choose.hasOwnProperty('statistics')){
                            _this.permission.statistics.sub = data.data.permission_choose.statistics;
                        }
                        _this.permission.level = data.data.level;

                        return true;
                    }).fail(function(error){
                        var err = error.responseJSON;
                        if (err.hasOwnProperty('invalid')){
                            alert(err.invalid);

                            return false;
                        }
                        if (err.hasOwnProperty('id')){
                            alert(err.id[0]);

                            return false;
                        }
                        if (err.hasOwnProperty('level')){
                            alert(err.level[0]);

                            return false;
                        }
                        alert("网络错误！");

                        return false;
                    });
                },
                updatePermission: function () {
                    var _this = this;
                    var url = "{{ url('admin/permission-update') }}";
                    _this.permissionError = {
                        role: {
                            isInvalid: 0,
                            msg: ''
                        },
                        level: {
                            isInvalid: 0,
                            msg: ''
                        },
                        task: {
                            isInvalid: 0,
                            msg: ''
                        },
                        person: {
                            isInvalid: 0,
                            msg: ''
                        },
                        system: {
                            isInvalid: 0,
                            msg: ''
                        },
                        salary: {
                            isInvalid: 0,
                            msg: ''
                        },
                        compensation: {
                            isInvalid: 0,
                            msg: ''
                        },
                        statistics: {
                            isInvalid: 0,
                            msg: ''
                        }
                    };
                    if (!_this.permission.role) {
                        _this.permissionError.role.isInvalid = 1;
                        _this.permissionError.role.msg = '必填！';

                        return false;
                    }
                    var permissions = [];
                    permissions = permissions.concat(
                            _this.permission.task.sub, _this.permission.person.sub,
                            _this.permission.system.sub, _this.permission.salary.sub,
                            _this.permission.compensation.sub, _this.permission.statistics.sub
                    );
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            id: _this.permission.role,
                            permissions: permissions
                        },
                        timeout:60000,
                        type:'POST'
                    }).done(function(data){
                        if (data.message=='success'){
                            alert('保存成功!');
                        }else{
                            alert('保存失败!');
                        }

                        return true;
                    }).fail(function(error){
                        var err = error.responseJSON;
                        if (err.hasOwnProperty('invalid')){
                            alert(err.invalid);

                            return false;
                        }
                        if (err.hasOwnProperty('id')){
                            alert(err.id[0]);

                            return false;
                        }
                        if (err.hasOwnProperty('level')){
                            alert(err.level[0]);

                            return false;
                        }
                        if (err.hasOwnProperty('permissions')){
                            alert(err.permissions[0]);

                            return false;
                        }
                        alert("网络错误！");

                        return false;
                    });
                },
                togglePermission: function (val) {
                    if (val.choose){
                        val.sub = [];
                    }else {
                        var options = [];
                        options = val.option.map(function (item) {
                            return item['id'];
                        });
                        val.sub = options;
                    }
                },
                getRoles: function () {
                    var _this = this;
                    var url = "{{ url('admin/manager-role-list') }}";
                    _this.managerAccount.roleOptions = null;
                    _this.managerAccount.role.current = 0;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            'level': _this.managerAccount.level,
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.managerAccount.roleOptions = data.data;

                        return true;
                    }).fail(function(error){
                        errs = error.responseJSON;
                        if (errs.level.length){
                            _this.managerAccountError.level.isInvalid = 1;
                            _this.managerAccountError.level.msg = errs.level[0];
                            return false;
                        }else{
                            alert("网络错误！");
                        }
                    });
                },
                getRelations: function () {
                    var _this = this;
                    var url = "{{ url('admin/manager-relation') }}";
                    _this.managerAccount.superiorOptions = null;
                    _this.managerAccount.equalOptions = null;
                    _this.managerAccount.superior.current = 0;
                    _this.managerAccount.equal.current = 0;
                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: {
                            'role_id': _this.managerAccount.role.current,
                        },
                        timeout:60000,
                        type:'GET'
                    }).done(function(data){
                        _this.managerAccount.superiorOptions = data.data.superior_list;
                        _this.managerAccount.equalOptions = data.data.equal_list;

                        return true;
                    }).fail(function(error){
                        errs = error.responseJSON;
                        if (errs.role_id.length){
                            _this.managerAccountError.role.isInvalid = 1;
                            _this.managerAccountError.role.msg = errs.role_id[0];
                            return false;
                        }else{
                            alert("网络错误！");
                        }
                    });
                },
                saveManagerRole: function () {
                    var _this = this;
                    var url = "{{ url('admin/manager-role-save') }}";
                    _this.managerAccountError = {
                        name: {
                            isInvalid: 0,
                            msg: ''
                        },
                        level: {
                            isInvalid: 0,
                            msg: ''
                        },
                        account: {
                            isInvalid: 0,
                            msg: ''
                        },
                        role: {
                            isInvalid: 0,
                            msg: ''
                        },
                        superior: {
                            isInvalid: 0,
                            msg: ''
                        },
                        equal: {
                            isInvalid: 0,
                            msg: ''
                        },
                    };

                    var change = {length:0, manager_id:_this.managerAccount.equal.origin};
                    if (_this.managerAccount.name.current != _this.managerAccount.name.origin){
                        change.name =  _this.managerAccount.name.current;
                        change.length++;
                    }
                    if (_this.managerAccount.account.current != _this.managerAccount.account.origin){
                        change.account =  _this.managerAccount.account.current;
                        change.length++;
                    }
                    if (_this.managerAccount.role.current != _this.managerAccount.role.origin){
                        change.role =  _this.managerAccount.role.current;
                        change.length++;
                    }
                    if (_this.managerAccount.superior.current != _this.managerAccount.superior.origin){
                        change.superior =  _this.managerAccount.superior.current;
                        change.length++;
                    }
                    if (_this.managerAccount.equal.current != _this.managerAccount.equal.origin){
                        change.equal =  _this.managerAccount.equal.current;
                        change.length++;
                    }
                    if (change == 0){
                        $('#role-manage').modal('hide');

                        return false;
                    }

                    if (!_this.managerAccount.name.current.trim()){
                        _this.managerAccountError.name.isInvalid = 1;
                        _this.managerAccountError.name.msg = '不能为空！';

                        return false;
                    }
                    if (!_this.managerAccount.account.current.trim()){
                        _this.managerAccountError.account.isInvalid = 1;
                        _this.managerAccountError.account.msg = '不能为空！';

                        return false;
                    }
                    if (!_this.managerAccount.role.current){
                        _this.managerAccountError.role.isInvalid = 1;
                        _this.managerAccountError.role.msg = '不能为空！';

                        return false;
                    }
                    if (!_this.managerAccount.superior.current){
                        _this.managerAccountError.superior.isInvalid = 1;
                        _this.managerAccountError.superior.msg = '不能为空！';

                        return false;
                    }
                    if (!_this.managerAccount.equal.current){
                        _this.managerAccountError.equal.isInvalid = 1;
                        _this.managerAccountError.equal.msg = '不能为空！';

                        return false;
                    }
                    if (!_this.managerAccount.account.current.match(/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/)){
                        _this.managerAccountError.account.isInvalid = 1;
                        _this.managerAccountError.account.msg = '邮箱格式错误！';

                        return false;
                    }

                    $.ajax({
                        url:url,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                        },
                        data: change,
                        timeout:60000,
                        type:'POST'
                    }).done(function(data){
                        alert("保存成功！");
                        window.location.reload();

                        return true;
                    }).fail(function(error){
                        errs = error.responseJSON;
                        if (errs.hasOwnProperty('role')){
                            _this.managerAccountError.role.isInvalid = 1;
                            _this.managerAccountError.role.msg = errs.role[0];
                            return false;
                        }else if(errs.hasOwnProperty('name')){
                            _this.managerAccountError.name.isInvalid = 1;
                            _this.managerAccountError.name.msg = errs.name[0];
                            return false;
                        }else if(errs.hasOwnProperty('account')){
                            _this.managerAccountError.account.isInvalid = 1;
                            _this.managerAccountError.account.msg = errs.account[0];
                            return false;
                        }else if(errs.hasOwnProperty('equal')){
                            _this.managerAccountError.equal.isInvalid = 1;
                            _this.managerAccountError.equal.msg = errs.equal[0];
                            return false;
                        }else if(errs.hasOwnProperty('superior')){
                            _this.managerAccountError.superior.isInvalid = 1;
                            _this.managerAccountError.superior.msg = errs.superior[0];
                            return false;
                        }else if (errs.hasOwnProperty('invalid')) {
                            alert(errs.invalid);
                            return false;
                        } else {
                            alert("网络错误！");
                        }
                    });
                }
            },
            events:{
                'mg-id': function (managerId) {
                    this.restPassword.managerId=managerId;
                },
                'role-manage-list': function (data) {
                    this.managerAccount.roleOptions = data.roles;
                    this.managerAccount.equalOptions = data.equal_list;
                    this.managerAccount.superiorOptions = data.superior_list;
                    this.managerAccount.superior = {
                        origin: data.leader,
                        current: data.leader,
                    };
                },
                'mg-list': function (data) {
                    this.managerAccount.name = {
                        origin: data.name,
                        current: data.name
                    };
                    this.managerAccount.account = {
                        origin: data.email,
                        current: data.email,
                    };
                    this.managerAccount.role = {
                        origin: data.roles.length?data.roles[0]['id']:0,
                        current: data.roles.length?data.roles[0]['id']:0,
                    };
                    this.managerAccount.equal = {
                        origin: data.id,
                        current: data.id
                    };
                    this.managerAccount.level = data.roles.length?data.roles[0]['level']:null;
                }
            }
        });
    </script>
@endsection