<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OwnController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function showMyForm(){
        return view('home.my');
    }
}
