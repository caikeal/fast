<?php

namespace App\Http\Controllers;

use App\SmsRecord;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout','reset','showResetForm']]);
    }

    public function showLoginForm(){
        return view('home.login');
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 高频访问1分钟限制
     * 处理登录（将初始登录密码迁移到登录阶段）
     */
    public function login (LoginRequest $request)
    {
        $account=$request->input('account');
        $password=$request->input('password');
        //手机号和身份证登录区分
        if(mb_strlen(trim($account))==11 && is_numeric($account)) {
            $credentials = ["phone" => $account, "password" => $password];
        }else{
            $userInfo = User::where("id_card", "=", $account)->first();
            if ($userInfo) {
                if ($userInfo['is_first']==1) {
                    return redirect()->back()
                        ->withInput($request->only("account"))
                        ->withErrors([
                            "account" => "请使用手机号登录！",
                        ]);
                } else {
                    /**
                     * 为了减轻后台批量上传是数据集中加密导致服务器保存缓慢的问题
                     * 将初始登录密码迁移到登录阶段
                     */
                    $userInfo->password= bcrypt(substr($account, -6));
                    $userInfo->save();

                    //验证登陆
                    $credentials = ["id_card" => $account, "password" => $password];
                }
            } else {
                $credentials = ["phone" => $account, "password" => $password];
            }
        }

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            if(Auth::user()->is_first==1) {
                return redirect('index');
            }else{
                return redirect('binding');
            }
        }

        $this->incrementLoginAttempts($request);

        return redirect()->back()
            ->withInput($request->only("account"))
            ->withErrors([
                $this->username() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request){
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('login');
    }

    public function showResetForm(){
        return view('home.reset');
    }

    /**
     * 重置密码
     * @param ResetRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reset(ResetRequest $request){
        $is_exist=SmsRecord::where("phone","=",$request->input('phone'))->where("code","=",$request->input('valid'))->count();
        if(!$is_exist){
            return redirect()->back()
                ->withInput($request->only("phone"))
                ->withErrors([
                    "valid" => "验证码错误",
                ]);
        }
        $exist_user = User::where("phone","=",$request->input('phone'))->count();
        if(!$exist_user){
            return redirect()->back()
                ->withInput($request->only("phone"))
                ->withErrors([
                    "phone" => "不存在该用户",
                ]);
        }
        $password=bcrypt($request->input('password'));
        if(Auth::check()){
            $userId=Auth::user()->id;
            User::where("id", "=", $userId)->update(["password" => $password]);
            Auth::logout();
            return redirect('login');
        }else{
            User::where("phone", "=", $request->input("phone"))
                ->update(["password" => $password]);
            return redirect('login');
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'account';
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return '账号密码错误！';
    }
}
