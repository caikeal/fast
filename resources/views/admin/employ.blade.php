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
    </style>
@endsection
@section('content')
    <div class="padding-md" id="employ">
        {{--<div class="alert alert-danger alert-dismissible" role="alert" v-if="systemErrors">--}}
        {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
        {{--<strong>Warning!</strong> @{{systemErrors}}--}}
        {{--</div>--}}
        <!-- 企业管理 -->
        <div class="row base-backcolor">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="text-info">人员管理</h3>
            </div>
        </div>
        <div class="seperator"></div>
        <!-- 快速搜索下属 -->
        <div class="row base-backcolor">
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
        <div class="row base-backcolor">
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
                    <i class="fa fa-users fa-lg circle-check poster-icon-height"></i>
                </div>
                <p class="result-head">下属总览:</p>
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
                        <th>待处理任务数</th>
                        <th>组长</th>
                        <th>更新时间</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    @foreach($managers as $manager)
                        <tr>
                            <td>{{$manager->name}}</td>
                            <td>{{$manager->email}}</td>
                            <td>
                                {{count($manager->tasks)}}
                            </td>
                            <td>{{$manager->leader->name}}</td>
                            <td>{{$manager->updated_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- /table  -->
            </div>
        </div>

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
                                        @foreach($memberRoles as $memberRole)
                                        <div class="checkbox inline-block">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" id="{{$memberRole->name}}" class="checkbox-blue" value={{$memberRole->id}}
                                                       v-model="managerRoles">
                                                <label for="{{$memberRole->name}}"></label>
                                            </div>
                                            <div class="inline-block vertical-top">
                                                {{$memberRole->label}}
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        @endforeach
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
            $(".lock-place8").addClass("active");
        })($);

        //vue托管
        new Vue({
            el: "#employ",
            data:{
                is_managerName:0,
                managerNameErrors:'',
                is_managerAccount:0,
                managerAccountErrors:'',
                is_managerPassword:0,
                managerPasswordErrors:'',
                is_managerRoles:0,
                managerRolesErrors:'',
                managerName:'',
                managerAccount:'',
                managerPassword:'',
                managerRoles:['{{$memberRoles[0]->id}}']
            },
            methods:{
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
                },
            }
        });
    </script>
@endsection