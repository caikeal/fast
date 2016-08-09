<?php
/**
 * Created by PhpStorm.
 * User: Caikeal
 * Date: 2016/8/8
 * Time: 19:45
 */

namespace App\Fast\Service\Answer;


class Answer
{
    /**
     * 可以回答的类型数组.
     *
     * @return array
     */
    public function canAnswerType()
    {
        $roles = \Auth::guard("admin")->user()->roles()->get();
        //获取用户可以回答的问题类型
        $roleNameArr = $roles->map(function($item, $key){
            if (strpos($item['name'], 'compensate') !== false){
                return [3,4];
            }else if (strpos($item['name'], 'salary') !== false){
                return [1,2];
            }else{
                return [];
            }
        });

        return $roleNameArr;
    }
}