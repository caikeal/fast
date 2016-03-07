<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    /**
     * 登录页面
     */
    public function showLoginForm(){
        return view('admin.login');
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 高频访问1分钟限制
     * 处理登录
     */
    public function login(LoginRequest $request){
        $throttles=$this->isUsingThrottlesLoginsTrait();
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->has('remember'))) {
            if ($throttles) {
                $this->clearLoginAttempts($request);
            }
            return redirect('admin/index');
        }

        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 退出登录
     */
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }

    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
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
