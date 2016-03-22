@extends('admin.app')
@section('moreCss')
 <style>
 .base-backcolor{
 background-color: #fff;
 }
 .form-search{
 margin-top: 1rem;
 }
 .poster-icon{
 border-radius:2.5rem;
 line-height: 2.5rem;
 text-align:center;
 color: #fff;
 width: 2.5rem;
 }
 .poster-icon.icon-search-color{
 background-color: #ACCEF1;
 }
 .poster-icon.icon-result-color{
 background-color: #F1CEAC;
 }
 .circle-check{
 margin-left: 3px;
 }
 input[type=text].search-query{
 border-radius:2.5rem;
 width: 70%;
 margin-left: 3.5rem
 }
 .btn-link.member-btn{
 font-size: medium;
 color:#1D75D8;
 margin-left: 42px;
 line-height: 4rem
 }
 .poster-btn{
 border-radius:1rem;
 color: #fff;
 width: 2rem;
 height:2rem;
 text-align: center;
 padding: .5rem;
 margin-right:10px;
 }
 .poster-btn.btn-member{
 background-color:#1D75D8;
 }
 .poster-btn.btn-add-account{
 background-color:#1CD01D;
 }
 .result-head{
 margin-left:3.5rem;
 line-height: 2.5rem;
 font-weight: 600;
 }
 .page-group{
 padding-bottom: 1rem;
 }
 .line{
 margin-top: .1rem;
 }
 .input-group .none-left-border{
 border-left: 0;
 border-color: #ccc;
 }
 .col-xs-2.lable-xs-center{
 padding-top: 7px;
 }
 </style>
@endsection
@section('content')
 <div class="padding-md">
  <!-- 超级管理员 -->
  <div class="row base-backcolor">
   <div class="col-sm-12 col-md-12 col-lg-12">
    <h3 class="text-info">超级管理员</h3>
   </div>
  </div>
  <div class="seperator"></div>
  <!-- 快速搜索客服 -->
  <div class="row base-backcolor">
   <form class="form-search">
    <div class="col-xs-9 col-sm-10 col-md-10 col-lg-10">
     <div class="pull-left poster-icon icon-search-color">
      <i class="fa fa-search fa-lg poster-icon-height"></i>
     </div>
     <input type="text" class="input-medium form-control search-query" placeholder="快速搜索客服姓名" name="name">
    </div>
    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
     <a class="btn btn-primary block">搜索</a>
    </div>
    <div class="clearfix"></div>
   </form>
  </div>
  <div class="row base-backcolor">
   <a id="initUser" class="btn btn-link member-btn"><i class="fa fa-user fa-sm poster-btn btn-member"></i><strong>用户管理</strong></a>
   <a id="createManager" class="btn btn-link member-btn" style=""><i class="fa fa-plus fa-sm poster-btn btn-add-account"></i><strong>创建帐号</strong></a>
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
    <span>共<em>15</em>条记录</span>
    <select>
     <option>1</option>
     <option>2</option>
     <option>3</option>
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
     </tbody>
    </table>
    <!-- /table  -->
   </div>
  </div>
 </div>
 <!--/pdadding-md-->
@endsection
@section('addition')
        <!-- template initUser-->
 <template id="init-user" style="display: none;">
  <br>
  <div class="container-fluid">
   <form action="">
    <div class="row">
     <div class="col-sm-12">
      <div class="input-group">
       <input type="text" class="form-control" placeholder="搜索手机号">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">搜索</button>
              </span>
      </div>
     </div>
    </div>
    <div class="seperator"></div>
    <div class="row">
     <div class="col-sm-6 col-xs-6">
      <div class="input-group">
       <span class="input-group-addon">姓名</span>
       <input type="text" class="form-control none-left-border" disabled="disabled" value="casdfsdcsdcasdc">
      </div>
     </div>
     <div class="col-sm-6 col-xs-6">
      <div class="input-group">
       <span class="input-group-addon">手机号</span>
       <input type="text" class="form-control none-left-border" disabled="disabled" value="123123123123123">
      </div>
     </div>
    </div>
    <div class="seperator"></div>
    <div class="row">
     <div class="col-sm-12">
      <div class="checkbox inline-block">
       <div class="custom-checkbox">
        <input type="checkbox" id="inlineCheckbox1" class="checkbox-blue" checked="checked" disabled="disabled">
        <label for="inlineCheckbox1"></label>
       </div>
       <div class="inline-block vertical-top">
        男
       </div>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="checkbox inline-block">
       <div class="custom-checkbox">
        <input type="checkbox" id="inlineCheckbox2" class="checkbox-blue" disabled="disabled">
        <label for="inlineCheckbox2"></label>
       </div>
       <div class="inline-block vertical-top">
        女
       </div>
      </div>
     </div>
    </div>
    <div class="seperator"></div>
    <div class="row">
     <div class="col-sm-12">
      <div class="input-group">
       <span class="input-group-addon">企业名</span>
       <input type="text" class="form-control none-left-border" disabled="disabled" value="ssssafscsdcascs">
      </div>
     </div>
    </div>
    <div class="seperator" style="padding: 20px;"></div>
    <div class="row">
     <div class="col-sm-offset-2 col-sm-8">
      <a class="btn btn-primary block">确认初始化</a>
     </div>
    </div>
   </form>
  </div>
 </template>
 <!-- /template initUser-->
 <!-- template createManager-->
 <template id="create-manager" style="display: none;">
  <br>
  <div class="container-fluid">
   <form action="" class="form-horizontal">
    <div class="form-group">
     <label for="name" class="col-lg-2 control-label lable-xs-center">姓名:</label>
     <div class=" col-lg-10">
      <input type="text" class="form-control" id="name" name="name" placeholder="姓名">
     </div>
    </div>
    <div class="form-group">
     <label for="account" class="col-lg-2 control-label lable-xs-center">账号:</label>
     <div class=" col-lg-10">
      <input type="text" class="form-control" id="account" name="account" placeholder="账号">
     </div>
    </div>
    <div class="form-group">
     <label for="password" class="col-lg-2 control-label lable-xs-center">密码:</label>
     <div class=" col-lg-10">
      <input type="text" class="form-control" id="password" name="password" placeholder="密码">
     </div>
    </div>
    <div class="form-group">
     <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox inline-block">
       <div class="custom-checkbox">
        <input type="checkbox" id="inlineCheckbox1" class="checkbox-blue">
        <label for="inlineCheckbox1"></label>
       </div>
       <div class="inline-block vertical-top">
        薪资客服
       </div>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="checkbox inline-block">
       <div class="custom-checkbox">
        <input type="checkbox" id="inlineCheckbox2" class="checkbox-blue">
        <label for="inlineCheckbox2"></label>
       </div>
       <div class="inline-block vertical-top">
        理赔客服
       </div>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="checkbox inline-block">
       <div class="custom-checkbox">
        <input type="checkbox" id="inlineCheckbox3" class="checkbox-blue">
        <label for="inlineCheckbox3"></label>
       </div>
       <div class="inline-block vertical-top">
        福利客服
       </div>
      </div>
     </div>
    </div>
    <a class="btn btn-primary block m-top-md" id="submit">创建账号</a>
   </form>
  </div>
 </template>
 <!-- /template createManager-->
 <!-- template restPassword-->
 <template id="reset-password" style="display: none;">
  <br>
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
    <div class="col-sm-8 col-sm-offset-2">
     <a class="btn btn-primary block">确认重置</a>
    </div>
   </form>
  </div>
 </template>
 <!-- /template restPassword-->
@endsection
@section('moreScript')
 <script src='{{env('APP_URL')}}/js/layer/layer.js'></script>
 <script>
  //侧边栏位置锁定
  !(function () {
   $(".main-menu .accordion>li").removeClass("active");
   $(".lock-place9").addClass("active");
  })($);

  //用户管理
  $("#initUser").on("click",function(){
   //多窗口模式，层叠置顶
   layer.open({
    type: 1 //此处dom
    ,title: '用户管理'
    ,shadeClose: true
    ,area: ['40%', '60%']
    ,content: $('#init-user').html()
   });
  });

  //创建帐号
  $("#createManager").on("click",function(){
   //多窗口模式，层叠置顶
   layer.open({
    type: 1 //此处dom
    ,title: '创建帐号'
    ,shadeClose: true
    ,area: ['40%', '60%']
    ,content: $('#create-manager').html()
   });
  });
 </script>
@endsection