<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/22
 * Time: 0:27
 */

namespace App\Fast\Service\Insurance;


use App\InsuranceDetail;
use App\User;
use Carbon\Carbon;
use DB;

class Insurance
{
    public function storeInsurance($base_id,$company_id,$type,$manager_id,$all_content){
        $now = Carbon::now();

        foreach ($all_content as $k => $v) {
            if ($k > 0 && $v[0]) {
                $v1_type = is_string($v[1]) ? $v[1] : sprintf('%0.0f', $v[1]);
                $is_exist_user = User::where("id_card", "=", $v1_type)->first();
                $is_exist_detail = "";
                if ($is_exist_user) {
                    $is_exist_detail = InsuranceDetail::where("user_id", "=", $is_exist_user->id)
                        ->where("company_id", "=", $company_id)
                        ->where("insurance_day", "=", $v[2])
                        ->where("type", $type)
                        ->first();
                }

                //用户创建
                if (!$is_exist_user) {
                    $user_id = DB::table('users')->insertGetId([
                        'name' => $v[0],
                        'id_card' => $v1_type,
                        'manager_id' => $manager_id,
                        'company_id' => $company_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    $user_id = $is_exist_user->id;
                }
                //薪资数据保存
                $wages = "";
                foreach ($v as $kk => $vv) {
                    if ($kk > 2) {
                        //由于是日期格式excel会以数组返回
                        if(is_array($vv)){
                            $vv=date("Y/m/d",strtotime($vv['date']));
                        }
                        $wages .= $vv . "||";
                    }
                }
                $wages = trim($wages, "||");
                if (!$is_exist_detail) {
                    DB::table('insurance_details')->insert([
                        'user_id' => $user_id,
                        'base_id' => $base_id,
                        'company_id' => $company_id,
                        'wages' => $wages,
                        'insurance_day' => $v[2],
                        'manager_id' => $manager_id,
                        'type' => $type,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    DB::table('insurance_details')->where('company_id', "=", $company_id)
                        ->where('insurance_day', "=", $v[2])
                        ->where('user_id', "=", $user_id)->update([
                            'base_id' => $base_id,
                            'wages' => $wages,
                            'manager_id' => $manager_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                }
            }
        }

    }
}