<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/22
 * Time: 0:30
 */

namespace App\Fast\Service\Excel;


use Carbon\Carbon;

class InsuranceExcel extends Excel
{
    protected $path;

    public function store($fileName, $byte)
    {
        $now=Carbon::now();
        $this->path = 'import/excel/insurance/'.$now->year . "-" . $now->month . "/" . $fileName;
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