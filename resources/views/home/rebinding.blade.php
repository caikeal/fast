@extends('home.app')
@section('head')
    <title>手机绑定</title>
@endsection
@section('moreCss')
    <style type="text/css">
        body {
            background-color: #f8f8f8;
        }
        .am-form input[type=text], .am-form input[type=password], .am-form button {
            border-radius: 5px;
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
            <form class="am-form" action="{{url('rebinding')}}" method="post">
                {!! csrf_field() !!}
                <fieldset>
                    {{--原手机号--}}
                    <div class="remind">原手机号</div>
                    <div class="am-form-group">
                        <input class="am-form-field am-input-lg" type="text" name="phone" disabled="disabled" value="{{Auth::user()->phone}}" placeholder="原手机号">
                    </div>
                    <div class="am-form-group {{ $errors->has('valid') ? ' am-form-error' : '' }}">
                        <div class="am-g am-g-collapse">
                            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                                <input class="am-form-field am-input-lg" type="text" name="valid" placeholder="验证码">
                            </div>

                            <button id="sms1"
                                    class="am-btn am-btn-primary am-btn-lg am-u-lg-4 am-u-md-4 am-u-sm-4 am-u-sm-offset-1 am-u-md-offset-1 am-u-lg-offset-1">
                                获取验证码
                            </button>
                        </div>
                        @if ($errors->has('valid'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('valid') }}</div>
                        @endif
                    </div>

                    {{--现手机号--}}
                    <div class="remind">现手机号</div>
                    <div class="am-form-group {{ $errors->has('newPhone') ? ' am-form-error' : '' }}">
                        <input class="am-form-field am-input-lg" type="text" name="newPhone" value="{{old("newPhone")}}" placeholder="输入新手机号码">
                        @if ($errors->has('newPhone'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('newPhone') }}</div>
                        @endif
                    </div>
                    <div class="am-form-group {{ $errors->has('newValid') ? ' am-form-error' : '' }}">
                        <div class="am-g am-g-collapse">
                            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                                <input class="am-form-field am-input-lg" type="text" name="newValid" placeholder="验证码">
                            </div>

                            <button id="sms2"
                                    class="am-btn am-btn-primary am-btn-lg am-u-lg-4 am-u-md-4 am-u-sm-4 am-u-sm-offset-1 am-u-md-offset-1 am-u-lg-offset-1">
                                获取验证码
                            </button>
                        </div>
                        @if ($errors->has('newValid'))
                            <div class="am-alert am-alert-danger" style="">{{ $errors->first('newValid') }}</div>
                        @endif
                    </div>
                    <button type="submit" class="am-btn am-btn-primary am-btn-block am-btn-lg">重新绑定</button>
                </fieldset>
            </form>
        </div>
    </section>
@endsection
@section('moreScript')
    <script>
        $(document).ready(function () {
            var ct1 = localStorage.getItem("ct1");
            var ct2 = localStorage.getItem("ct2");
            var nt1 = localStorage.getItem("nt1");
            var nt2 = localStorage.getItem("nt2");
            var now = new Date().getTime();
            if (typeof(ct1) != "undefined" && ct1 != 0 && ct1 > 0 && now - nt1 <= ct1 * 1000) {
                var dit1 = $("#sms1");
                downTime1(dit1, Math.round((ct1 * 1000 - (now - nt1)) / 1000));
            }
            if (typeof(ct2) != "undefined" && ct2 != 0 && ct2 > 0 && now - nt2 <= ct2 * 1000) {
                var dit2 = $("#sms2");
                downTime2(dit2, Math.round((ct2 * 1000 - (now - nt2)) / 1000));
            }
        });

        $("#sms1").on("click", function (e) {
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
            downTime1(_this, 120);
            $.ajax({url: url, dataType: "json", method: "get", timeout: 60000})
                    .done(function (data) {
                        if (data.status==1) {
                            $("[name=valid]").attr("data-code",data.sms);
                        } else if(data.status==2){
                            alert(data.message);
                            $("[name=valid]").attr("data-code",data.sms);
                        }else {
                            alert(data.message);
                            var loadId = localStorage.getItem("ld1");
                            if (loadId) {
                                clearTimeout(loadId);
                                localStorage.setItem("ct1", 0);
                                $(_this).attr("disabled", false);
                                $(_this).text("获取验证码");
                            }
                        }
                    })
                    .fail(function () {
                        alert("网络信号不好！请重新再试");
                        var loadId = localStorage.getItem("ld1");
                        if (loadId) {
                            clearTimeout(loadId);
                            localStorage.setItem("ct1", 0);
                            $(_this).attr("disabled", false);
                            $(_this).text("获取验证码");
                        }
                    });
        });

        $("#sms2").on("click", function (e) {
            e.preventDefault();
            var _this = this;
            var url = "{{url('sms')}}";
            var phone = $("[name=newPhone]").val();
            if(phone){
                url=url+"/"+phone
            }else{
                alert("请输入手机号码！");
                return false;
            }
            $("[name=newValid]").removeAttr("data-code");
            downTime2(_this, 120);
            $.ajax({url: url, dataType: "json", method: "get", timeout: 60000})
                    .done(function (data) {
                        if (data.status==1) {
                            $("[name=newValid]").attr("data-code",data.sms);
                        } else if(data.status==2){
                            alert(data.message);
                            $("[name=newValid]").attr("data-code",data.sms);
                        }else {
                            alert(data.message);
                            var loadId = localStorage.getItem("ld2");
                            if (loadId) {
                                clearTimeout(loadId);
                                localStorage.setItem("ct2", 0);
                                $(_this).attr("disabled", false);
                                $(_this).text("获取验证码");
                            }
                        }
                    })
                    .fail(function () {
                        alert("网络信号不好！请重新再试");
                        var loadId = localStorage.getItem("ld2");
                        if (loadId) {
                            clearTimeout(loadId);
                            localStorage.setItem("ct2", 0);
                            $(_this).attr("disabled", false);
                            $(_this).text("获取验证码");
                        }
                    });
        });

        var downTime1 = function (o, wait) {
            if (wait == 0) {
                $(o).attr("disabled", false);
                $(o).text("获取验证码");
                localStorage.setItem("ct1", 0);
                return false;
            } else {
                $(o).attr("disabled", true);
                $(o).text("重发(" + wait + ")");
                wait--;
                localStorage.setItem("ct1", wait);
                localStorage.setItem("nt1", new Date().getTime());
                loadId = setTimeout(function () {
                    downTime1(o, wait);
                }, 1000);
                localStorage.setItem("ld1", loadId);
                return loadId;
            }
        };
        var downTime2 = function (o, wait) {
            if (wait == 0) {
                $(o).attr("disabled", false);
                $(o).text("获取验证码");
                localStorage.setItem("ct2", 0);
                return false;
            } else {
                $(o).attr("disabled", true);
                $(o).text("重发(" + wait + ")");
                wait--;
                localStorage.setItem("ct2", wait);
                localStorage.setItem("nt2", new Date().getTime());
                loadId = setTimeout(function () {
                    downTime2(o, wait);
                }, 1000);
                localStorage.setItem("ld2", loadId);
                return loadId;
            }
        };
    </script>
@endsection