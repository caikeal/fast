<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/25
 * Time: 22:56
 */

namespace App\Fast\Service\Salary;


use App\SalaryDetail;
use App\User;
use Carbon\Carbon;
use DB;

class Salary
{
    /**
     * 保存薪资、社保详情。
     * 策略：
     * 相同用户、相同企业、相同查询时间、相同类型的算是同种详情，采用覆盖策略
     * 否则，采用新建策略。
     *
     * @param $base_id
     * @param $company_id
     * @param $type
     * @param $manager_id
     * @param $all_content
     */
    public function storeSalary($base_id,$company_id,$type,$manager_id,$all_content)
    {
        $now = Carbon::now();
        $flagUnion = $this->getFlagUnionPlace($all_content); // 判断 k=0 时的最后一个值是否存在isUnion
        $unionArr = []; // 存放合并的数据
        $unionUser = []; // 临时存放以保存的userId
        foreach ($all_content as $k => $v) {
            if ($k > 0 && $v[0]) {
                $v1_type = is_string($v[1]) ? $v[1] : sprintf('%0.0f', $v[1]);
                $v1_type = trim($v1_type);
                $is_exist_user = User::where("id_card", "=", $v1_type)->first();
                $is_exist_detail = "";
                if ($is_exist_user) {
                    $is_exist_detail = SalaryDetail::where("user_id", "=", $is_exist_user->id)
                        ->where("company_id", "=", $company_id)
                        ->where("salary_day", "=", $v[2])
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
                    if ($kk <= 2 || ($flagUnion && $kk == $flagUnion)) {
                        continue;
                    }
                    
                    $wages .= $vv . "||";
                }
                $wages = rtrim($wages, "||");

                // 判断数据是否需要合并
                if ($flagUnion && $v[$flagUnion] == 1) {
                    // 合并数据处理
                    if (in_array($user_id, $unionUser)) {    // 已经存在
                        foreach ($unionArr as $uk => $uv) {
                            if ($uv['userId'] == $user_id) {
                                // 最大时间是否有变
                                if ($uv['maxDate']<$v[2]) {
                                    $unionArr[$uk]['maxDate'] = $v[2];
                                    $unionArr[$uk]['isExist'] = $is_exist_detail;//对应数据是否已经保存过
                                }
                                $unionArr[$uk]['data'][] = [
                                    "date" => $v[2],
                                    "wages" => $wages
                                ];
                            }
                        }
                    } else {    // 未保存过
                        $unionUser[] = $user_id;
                        $unionArr[] = [
                            'userId' => $user_id,
                            'maxDate' => $v[2],
                            'isExist' => $is_exist_detail,
                            'data' => [
                                [
                                    "date" => $v[2],
                                    "wages" => $wages
                                ]
                            ]
                        ];
                    }
                } else {
                    // 不需要合并的数据先行保存
                    if (!$is_exist_detail) {
                        DB::table('salary_details')->insert([
                            'user_id' => $user_id,
                            'base_id' => $base_id,
                            'company_id' => $company_id,
                            'wages' => $wages,
                            'salary_day' => $v[2],
                            'manager_id' => $manager_id,
                            'type' => $type,
                            'meta' => '',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    } else {
                        DB::table('salary_details')->where('company_id', "=", $company_id)
                            ->where('salary_day', "=", $v[2])
                            ->where('user_id', "=", $user_id)->update([
                                'base_id' => $base_id,
                                'wages' => $wages,
                                'manager_id' => $manager_id,
                                'meta' => '',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                    }
                }

            }
        }

        // 对于合并数据统一进行插入或更新操作
        if ($flagUnion) {
            logger('hehe:'.json_encode($unionArr)); // 获取元数据的
            foreach ($unionArr as $tk=>$tv) {
                $sqlInfo = [];    // 临时数据保存
                $metaInfo = [];   // 临时元数据保存
                if ($tv['isExist']) {
                    $sqlInfo = [
                        'base_id' => $base_id,
                        'wages' => '',
                        'manager_id' => $manager_id,
                        'meta' => '',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }  else {
                    $sqlInfo = [
                        'user_id' => $tv['userId'],
                        'base_id' => $base_id,
                        'company_id' => $company_id,
                        'wages' => '',
                        'salary_day' => $tv['maxDate'],
                        'manager_id' => $manager_id,
                        'type' => $type,
                        'meta' => '',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                foreach ($tv['data'] as $dk=>$dv) {
                    if ($tv['maxDate'] == $dv['date']) {
                        $sqlInfo['wages'] = $dv['wages'];
                    } else {
                        $metaInfo[] = [
                            'date' => $dv['date'],
                            'wages' => $dv['wages']
                        ];
                    }
                }
                $metaInfoT['balance'] = collect($metaInfo)->sortBy('date')->values()->all();
                $sqlInfo['meta'] = empty($metaInfoT['balance']) ? '' : json_encode($metaInfoT);

                if ($tv['isExist']) {  // 更新操作
                    DB::table('salary_details')->where('company_id', "=", $company_id)
                        ->where('salary_day', "=", $tv['maxDate'])
                        ->where('user_id', "=", $tv['userId'])->update($sqlInfo);
                } else {
                    DB::table('salary_details')->insert($sqlInfo);
                }
            }
        }
    }

    /**
     * 判断是否需要合并.需要合并，返回合并位数
     *
     * @param array $allContent
     * @return int
     */
    protected function getFlagUnionPlace(array $allContent)
    {
        $firstVal = collect($allContent)->first();
        $val = collect($firstVal)->last();
        if (stripos($val, 'isUnion') !== false) {
            $flagUnion = count($firstVal)-1;
        } else {
            $flagUnion = 0;
        }
        logger('isUion:'.$flagUnion);
        logger('isUionL:'.json_encode(['last'=>$val]));
        logger('isUionF:'.json_encode(['first'=>$firstVal]));
        return $flagUnion;
    }
}