<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\SalaryCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalaryCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type=$request->input("type");
        $allCats=SalaryCategory::where("type",$type)->get();
        $big=array();
        $small=array();
        foreach($allCats as $cat){
            if($cat['level']==1){
                $big[]=$cat;
            }else{
                $small[]=$cat;
            }
        }
        $data=[
            'big'=>$big,
            'small'=>$small,
            'status'=>1,
        ];
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salaryCategory=new SalaryCategory();
        $salaryCategory->name=$request->input('name');
        $salaryCategory->level=$request->input('level');
        $salaryCategory->type=$request->input('type');
        $salaryCategory->manager_id=\Auth::guard('admin')->user()->id;
        if($salaryCategory->save()){
            $data['status']=1;
            $data['cid']=$salaryCategory->id;
        }else{
            $data['status']=0;
        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
