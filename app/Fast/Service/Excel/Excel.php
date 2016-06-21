<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/20
 * Time: 23:59
 */

namespace App\Fast\Service\Excel;


class Excel
{
    protected $excel;

    protected $sheet;

    public function storeExcel($pathName, $byteData)
    {
        return \Storage::put($pathName, $byteData);
    }

    public function deleteExcel($pathName)
    {
        return \Storage::delete($pathName);
    }

    public function readExcel($path, $sheetIndex=0)
    {
        $this->excel = \Excel::selectSheetsByIndex($sheetIndex)->load($path);
        return $this->excel;
    }

    public function getWorkTitle()
    {
        return $this->excel->getTitle();
    }

    public function getSheet($sheetIndex=0)
    {
        $this->sheet = $this->excel->getSheet($sheetIndex);
        return $this->sheet;
    }

    public function getSheetTitle()
    {
        return $this->sheet->getTitle();
    }
}