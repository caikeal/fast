<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function super(){
        return view('admin.super');
    }

    public function index(){
        return view();
    }
}
