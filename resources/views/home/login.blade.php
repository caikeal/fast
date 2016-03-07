@extends('home.module')
@section('moreCss')
    <style>
        body{
            background-color: #e2e2e2;
        }
        .am-form input[type=text]{
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        .am-form input[type=password]{
            border-bottom-left-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
        }
        button[type=submit],button#forget {
            border-radius: 5px;
        }
        .header {
            text-align: center;
        }
        .header h1 {
            font-size: 200%;
            color: #333;
            margin-top: 30px;
        }
        .header p {
            font-size: 14px;
        }
        .logo {
            box-sizing: border-box;
            width: 60%;
            left: 20%;
            vertical-align: middle;
            border: 0;
        }
    </style>
@endsection
@section('content')
    <section>
        <div class="am-g am-center am-u-sm-centered">
            <img class="logo am-u-sm-centered" src="{{env('APP_URL')}}/images/logo.png">
        </div>
        <div class="am-g" style="margin-top: 2.5rem;">
            <div class="am-u-md-8 am-u-sm-centered">
                <form class="am-form" action="{{url('login')}}" method="post">
                    {!! csrf_field() !!}
                    <fieldset class="am-form-set {{ count($errors)>0 ? ' am-form-error' : '' }}">
                        <input type="text" name="account" class="{{ $errors->has('account') ? 'am-form-field' : '' }}" placeholder="输入帐号" value="{{old('account')}}">
                        <input type="password" name="password" class="{{ $errors->has('password') ? 'am-form-field' : '' }}" placeholder="输入密码">
                    </fieldset>
                    @if ($errors->has('account'))
                        <div class="am-alert am-alert-danger" style="">{{ $errors->first('account') }}</div>
                    @endif
                    @if ($errors->has('password'))
                        <div class="am-alert am-alert-danger" style="">{{ $errors->first('password') }}</div>
                    @endif
                    <a href="{{url('reset')}}" class="am-btn am-btn-default am-fr" id="forget" style="margin-bottom: 2px">忘记密码?</a>
                    <button type="submit" class="am-btn am-btn-primary am-btn-block">登录</button>
                </form>
            </div>
        </div>
    </section>
@endsection
