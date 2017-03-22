<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/3/21
 * Time: 下午5:19
 */

namespace App\Http\Controllers;


use App\Info;

class InfoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('binding');
    }

    public function index()
    {
        $info = Info::all()->last();
        return $info;
    }
}