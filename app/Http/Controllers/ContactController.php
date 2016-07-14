<?php

namespace App\Http\Controllers;

use App\CompensationDetail;
use App\SalaryDetail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $user_id = \Auth::user()->id;
        $salary = SalaryDetail::where('user_id', $user_id)->where('type',1)
            ->orderBy("created_at", "desc")->first();

        if ($salary){
            $salaryFromer = $salary->fromer->first(['id','name','phone','email']);
        }else{
            $salaryFromer = [
                'name' => '',
                'phone' => '',
                'email' => ''
            ];
        }

        $insurance = SalaryDetail::where('user_id', $user_id)->where('type',2)
            ->orderBy("created_at", "desc")->first();
        if ($insurance){
            $insuranceFromer = $insurance->fromer->first(['id','name','phone','email']);
        }else{
            $insuranceFromer = [
                'name' => '',
                'phone' => '',
                'email' => ''
            ];
        }

        $compensation = CompensationDetail::where('user_id', $user_id)->where('type',3)
            ->orderBy("created_at", "desc")->first();
        if ($compensation){
            $compensationFromer = $compensation->fromer->first(['id','name','phone','email']);
        }else{
            $compensationFromer = [
                'name' => '',
                'phone' => '',
                'email' => ''
            ];
        }

        return view('home.contactus',[
            'salaryFromer'=>$salaryFromer,
            'insuranceFromer'=>$insuranceFromer,
            'compensationFromer'=>$compensationFromer
        ]);
    }
}
