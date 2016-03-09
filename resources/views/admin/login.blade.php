<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 , user-scalable=no">
    <meta name="description" content="Fast Fast And Fast">
    <title>FAST后台登录</title>
    <meta name="author" content="Keal">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <link rel="apple-touch-icon-precomposed" href="{{env('APP_URL')}}/images/114.png" sizes="114x114" />
    <link rel="shortcut icon" href="{{env('APP_URL')}}/images/32.ico" type="image/x-icon" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{env('APP_URL')}}/css/admin/login/login.css"/>
</head>
<body>
<!--导航-->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!--小屏幕导航按钮和logo-->
        <div class="navbar-header">
            <a href="{{url('/')}}" class="navbar-brand"><img src="{{env('APP_URL')}}/images/adminLogo.png"></a>
        </div>
        <!--小屏幕导航按钮和logo-->

    </div>
</nav>
<!--导航-->

<!--home-->
<section id="home">
    <div class="lvjing">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img src="{{env('APP_URL')}}/images/picture.png">
                </div>
                <div class="col-lg-6" >
                    <img src="{{env('APP_URL')}}/images/words.png">
                    <!--   <p></p> -->
                    <div class="login">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('admin/login') }}">
                            {!! csrf_field() !!}
                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="col-sm-12">
                                    <input type="email" class="form-control" id="name"
                                           placeholder="输入帐号" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" id="password"
                                           placeholder="输入密码" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="form-group">
                               <div class="col-sm-12">

                                     <span id="vdspan">验证码:</span>
                                     <input class="form-control" type="text" name="validate" placeholder="输入验证码" id="vdcode">
                                     <img id="vdimg" src="aa" alt="看不清？点击更换" >　　

                               </div>
                            </div> -->
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox remember">
                                        <label>
                                            <input type="checkbox" id="remember" name="remember"
                                            @if(old('remember')) checked @endif>记住密码
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn bt-lg btn-primary submit">登录</button>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--home-->

<!--bbs-->
<section id="bbs">
    <div class="container">
        <div class="row wow fadeInUp" data-wow-duration="0.5s">
            <div class="col-md-4">
                <a href="" target="#">
                    <img src="{{env('APP_URL')}}/images/salary.png" class="img-responsive" alt=""/>
                    <h3 class="sendSalary">发放工资</h3>
                    <p>自定义工资单模版</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="" target="#">
                    <img src="{{env('APP_URL')}}/images/settlement.png" class="img-responsive" alt=""/>
                    <h3 class="settlement">医疗理赔</h3>
                    <p>历史理赔详情表单一目了然</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="" target="#">
                    <img src="{{env('APP_URL')}}/images/welfware.png" class="img-responsive" alt=""/>
                    <h3 class="welfware">弹性福利</h3>
                    <p>更多福利</p>
                </a>
            </div>
        </div>
    </div>
</section>
<!--bbs-->
<!--footer-->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    Copyright&nbsp;©&nbsp;2016&nbsp;&nbsp;FESCO Adeco Corporation,All&nbsp;&nbsp;Rights Reserved
                </p>
                <p>
                    北京外企德科人力资源服务苏州有限公司&nbsp;&nbsp;版权所有
                </p>
            </div>
        </div>
    </div>
</footer>
<!--footer-->


<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="{{env('APP_URL')}}/js/jquery.singlePageNav.min.js"></script>
<script src="{{env('APP_URL')}}/js/wow.min.js"></script>
</body>
</html>