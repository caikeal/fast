<?php
/**
 * Created by PhpStorm.
 * User: Caikeal
 * Date: 2016/8/9
 * Time: 14:17
 */

namespace App\Fast\Service\Statistics;


use Carbon\Carbon;

class StatisticsAnalyse
{
    /**
     * 开始统计年份.
     */
    const  STARTYEAR = 2016;
    /**
     * 开始统计月份.
     */
    const STARTMONTH = 7;

    /**
     * 分析当年统计年度.
     *
     * @return array
     */
    public function getAnalyseMonth()
    {
        $tplTime = [];
        $now = Carbon::now();
        $moreYear = $now->year - self::STARTYEAR > 0 ? Carbon::now()->year - self::STARTYEAR : 0;
        //2016年为基础年，需要特殊处理
        if ($now->year == self::STARTYEAR){
            for ($i = self::STARTMONTH; $i <= $now->month-1; $i++){
                if ($i<10){
                    $tplTime[] = self::STARTYEAR."0".$i;
                }else{
                    $tplTime[] = self::STARTYEAR.$i;
                }
            }
        }else{
            for ($i = self::STARTMONTH; $i <= 12; $i++){
                if ($i<10){
                    $tplTime[] = self::STARTYEAR."0".$i;
                }else{
                    $tplTime[] = self::STARTYEAR.$i;
                }
            }
        }
        
        //其余年份除当年需要特殊处理
        for ($i = 1; $i <= $moreYear; $i++){
            $tplYear = self::STARTYEAR + $i;
            //本年度特殊处理
            if ($tplYear == $now->year){
                for ($j = 1; $j <= $now->month-1; $j++){
                    if ($j < 10){
                        $tplTime[] = $tplYear."0".$j;
                    }else{
                        $tplTime[] = $tplYear.$j;
                    }
                }
            }
            //往年正常处理
            else{
                for ($j = 1; $j <= 12; $j++){
                    if ($j < 10){
                        $tplTime[] = $tplYear."0".$j;
                    }else{
                        $tplTime[] = $tplYear.$j;
                    }
                }
            }
        }

        return $tplTime;
    }
}