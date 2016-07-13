<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        //判断首次登录完善个人信息
        $manager = Auth::guard('admin')->user();

        return view('admin.index', ["manager" => $manager]);
    }
}
