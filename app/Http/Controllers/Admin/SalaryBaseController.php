<?php

namespace App\Http\Controllers\Admin;

use App\SalaryBase;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalaryBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * 查询模版结构.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $details = [];

        //查询缓存是否存在
        if(\Cache::store('redis')->has('BaseComponent:'.$id)){
            //先取缓存
            $result = \Cache::store('redis')->get('BaseComponent:'.$id);
        }else{
            //查数据存缓存
            //查询对应的模版类型
            $cats = SalaryBase::with(['categories'=>function($query){
                $query->orderBy('place');
            }])->where('id', $id)->first();

            //组织模版类型
            $bigCats = [];
            $smallCats = [];
            $smallBase = [];
            $bigId = 0;
            foreach ($cats->categories as $k=>$v){
                if ($v['level']==1){
                    $bigCats[] = ['name'=>$v['name'],'id'=>$v['id']];
                    $bigId = $v['id'];
                }elseif ($v['level']==2){
                    $smallCats[$bigId][] = $v['name'];
                    $smallBase[] = $v['name'];
                }
            }

            foreach ($bigCats as $k=>$v){
                $details[] = [
                    'name' => $v['name'],
                    'detailNum' => count($smallCats[$v['id']])
                ];
            }

            $result['base'] = $details;
            $result['smallBase'] = $smallBase;
            $result['title'] = $cats['title'];
            \Cache::store('redis')->put('BaseComponent:'.$id, $result, 720);
        }

        return $result;
    }
}
