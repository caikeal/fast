<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\Excel\InsuranceExcel;
use App\Fast\Service\Insurance\Insurance;
use App\SalaryBase;
use App\SalaryUpload;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    protected $excel;

    protected $insurance;

    public function __construct(InsuranceExcel $excel, Insurance $insurance)
    {
        $this->excel = $excel;
        $this->insurance = $insurance;
        $this->middleware('auth:admin');
    }

    /**
     * 处理上传的社保进度。
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request){
        //验证excel的格式
        if(!$request->file('excel')->isValid()){
            return response("failed",422);
        }

        $nameFile=$request->get('name');
        $type=$request->get('type');

        $extension=explode(".",$nameFile);
        if($extension[1]) {
            $fileName = "/" . time().".".$extension[1];
        }else{
            $fileName = "/" . time().".xls";
        }

        //保存excel
        $isStore = $this->excel->store($fileName, file_get_contents($request->file('excel')->getRealPath()));
        if(!$isStore){
            $this->excel->delete();
            return response("failed",404);
        }

        //读取excel基础数据
        $workExcel = $this->excel->read();
        $base_id = $this->excel->getWorkTitle();
        $workSheet = $this->excel->getSheet(0);
        $company_id=$this->excel->getSheetTitle();

        //验证excel的基础内容
        $baseExist=SalaryBase::where("id", $base_id)
            ->where('company_id', $company_id)
            ->where('type', $type)->count();//工资模版存在
        if($type!=4||!$base_id||!$company_id||!is_numeric($base_id)||!is_numeric($company_id)||!$baseExist){
            $this->excel->delete();
            return response("liner",422);//格式不正确
        }

        //读取excel内容
        $content=$this->excel->content();
        foreach($content as $k=>$v){
            //去除excel空姓名行
            if(!$v[0]){
                unset($content[$k]);
            }
        }

        //空格数据表验证
        if(count($content)<2){
            $this->excel->delete();
            return response("No Data",422);//必须有实际数据
        }

        $manager_id=\Auth::guard('admin')->user()->id;

        //将数据写入数据库
        \DB::beginTransaction();
        try{
            //记录上传者
            $salaryUpload=new SalaryUpload();
            $salaryUpload->manager_id=$manager_id;
            $salaryUpload->base_id=$base_id;
            $salaryUpload->company_id=$company_id;
            $salaryUpload->type=$type;
            $salaryUpload->upload='app/'.$this->excel->getPath();
            $salaryUpload->save();

            //保存详细数据
            $this->insurance->storeInsurance($base_id, $company_id, $type, $manager_id, $content);
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            $this->excel->delete();
            return response("Save Wrong",422);//保存失败
        }

        return response("success");
    }
}
