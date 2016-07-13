<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * 获取所有企业.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCompanys = Company::get(['id', 'name as text']);
        $result['ret_num'] = 0;
        $result['msg'] = '成功！';
        $result['data'] = $allCompanys;
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name=$request->input('name');
        if(!$name){
            $result['ret_num']=110;
            $result['ret_msg']="企业名必填！";
            return response()->json($result);
        }
        $company=new Company();
        $company->name=$name;
        $company->poster="images/fast_company.png";
        $company->manager_id=\Auth::guard('admin')->user()->id;
        $company->save();
        $result['ret_num']=0;
        $result['ret_msg']="新增成功！";
        $result['data']=$company;
        return response()->json($result);
    }
}
