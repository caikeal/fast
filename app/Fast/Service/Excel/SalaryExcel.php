<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/21
 * Time: 10:38
 */

namespace App\Fast\Service\Excel;


use Carbon\Carbon;

class SalaryExcel extends Excel
{
    protected $path;

    public function store($fileName, $byte)
    {
        $now=Carbon::now();
        $this->path = 'import/excel/salary/'.$now->year . "-" . $now->month . "/" . $fileName;
        return $this->storeExcel($this->path, $byte);
    }

    public function delete()
    {
        return $this->deleteExcel($this->path);
    }

    public function read()
    {
        return $this->readExcel(storage_path('app/'.$this->path), 0);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function content()
    {
        return $this->excel->formatDates(true, 'Y-m-d')->get()->toArray();
    }
}