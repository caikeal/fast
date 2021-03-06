@extends('home.module')
@section('moreCss')
    <style type="text/css">
        body {
            background-color: #f8f8f8;
        }

        section {
            padding-top: 2rem;
        }

        .am-form input[type=text], .am-form input[type=password], .am-form button {
            border-radius: 5px;
        }
        .remind{
            color: #0e90d2;
            padding: 0 1.2rem;
        }
    </style>
@endsection
@section('back')
    <div class="am-header-left am-header-nav">
        <a onClick="javascript :history.back(-1);" class="">
            <img class="am-header-icon-custom"
                 src="data:image/svg+xml;charset=utf-8,&lt;svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 12 20&quot;&gt;&lt;path d=&quot;M10,0l2,2l-8,8l8,8l-2,2L0,10L10,0z&quot; fill=&quot;%23fff&quot;/&gt;&lt;/svg&gt;"
                 alt=""/> 返回
        </a>
    </div>
@endsection
@section('content')
    <section class="am-g">
        <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
            <div class="remind">重置密码</div>
            <form action="{{url('reset')}}" class="am-form" method="post">
                {!! csrf_field() !!}
                <fieldset>
                    <div class="am-form-group {{ $errors->has('phone') ? ' am-form-error' : '' }}">
                        <input class="am-form-field am-input-lg" type="text" name="phone" value="{{old("phone")}}" placeholder="绑定的手机号">
                        @if ($errors->has('phone'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                    <div class="am-form-group {{ $errors->has('valid') ? ' am-form-error' : '' }}">
                        <div class="am-g am-g-collapse">
                            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                                <input class="am-form-field am-input-lg" type="text" name="valid" placeholder="验证码">
                            </div>

                            <button id="sms"
                                    class="am-btn am-btn-primary am-btn-lg am-u-lg-4 am-u-md-4 am-u-sm-4 am-u-sm-offset-1 am-u-md-offset-1 am-u-lg-offset-1">
                                获取验证码
                            </button>
                        </div>
                        @if ($errors->has('valid'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('valid') }}</div>
                        @endif
                    </div>
                    <div class="am-form-group {{ $errors->has('password') ? ' am-form-error' : '' }}">
                        <input class="am-form-field am-input-lg" type="password" name="password" placeholder="重置登录密码">
                        @if ($errors->has('password'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="am-form-group {{ $errors->has('password_confirmation') ? ' am-form-error' : '' }}">
                        <input class="am-form-field am-input-lg" type="password" name="password_confirmation"
                               placeholder="确认重置登录密码">
                        @if ($errors->has('password_confirmation'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>
                    <button type="submit" class="am-btn am-btn-primary am-btn-block am-btn-lg">重置</button>
                </fieldset>
            </form>
        </div>
    </section>
@endsection
@section('moreJs')
    <script>
        $(document).ready(function () {
            var ct4 = localStorage.getItem("ct4");
            var nt4 = localStorage.getItem("nt4");
            var now = new Date().getTime();
            if (typeof(ct4) != "undefined" && ct4 != 0 && ct4 > 0 && now - nt4 <= ct4 * 1000) {
                var dit = $("#sms");
                downTime(dit, Math.round((ct4 * 1000 - (now - nt4)) / 1000));
            }
        });
        $("#sms").on("click", function (e) {
            e.preventDefault();
            var _this = this;
            var url = "{{url('sms')}}";
            var phone = $("[name=phone]").val();
            if(phone){
                url=url+"/"+phone
            }else{
                alert("请输入手机号码！");
                return false;
            }

            $("[name=valid]").removeAttr("data-code");
            downTime(_this, 120);
            $.ajax({url: url, dataType: "json", method: "get", timeout: 60000})
                    .done(function (data) {
                        if (data.status==1) {
                            $("[name=valid]").attr("data-code",data.sms);
                        } else if(data.status==2){
                            alert(data.message);
                            $("[name=valid]").attr("data-code",data.sms);
                        } else {
                            alert(data.message);
                            var loadId = localStorage.getItem("ld4");
                            if (loadId) {
                                clearTimeout(loadId);
                                localStorage.setItem("ct4", 0);
                                $(_this).attr("disabled", false);
                                $(_this).text("获取验证码");
                            }
                        }
                    })
                    .fail(function () {
                        alert("网络信号不好！请重新再试");
                        var loadId = localStorage.getItem("ld4");
                        if (loadId) {
                            clearTimeout(loadId);
                            localStorage.setItem("ct4", 0);
                            $(_this).attr("disabled", false);
                            $(_this).text("获取验证码");
                        }

                    });
        });

        var downTime = function (o, wait) {
            if (wait == 0) {
                $(o).attr("disabled", false);
                $(o).text("获取验证码");
                localStorage.setItem("ct4", 0);
                return false;
            } else {
                $(o).attr("disabled", true);
                $(o).text("重发(" + wait + ")");
                wait--;
                localStorage.setItem("ct4", wait);
                localStorage.setItem("nt4", new Date().getTime());
                loadId = setTimeout(function () {
                    downTime(o, wait);
                }, 1000);
                localStorage.setItem("ld4", loadId);
                return loadId;
            }
        }
    </script>
@endsection