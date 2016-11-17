<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/24
 * Time: 10:35
 */

namespace App\Fast\Service\History;


use App\Fast\Service\Excel\Excel;
use Storage;

class History
{
    protected $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    /**
     * 格式化文件的格式，去除已"app/"的文件名
     *
     * @param string $filePath
     *
     * @return string
     */
    public function pureFile($filePath='')
    {
        if(preg_match("/^[app\/]/", $filePath)){
            return str_replace("app/","",$filePath);
        }else{
            return $filePath;
        }
    }

    /**
     * 判断文件是否存在。
     *
     * @param string $filePath
     *
     * @return bool
     */
    public function hasFile($filePath='')
    {
        $filePath = $this->pureFile($filePath);
        if (!$filePath){
            return false;
        }

        return Storage::disk('local')->exists($filePath);
    }

    public function getFileName($filePath='')
    {
        $fileName = \File::name($filePath).".".\File::extension($filePath);

        return $fileName;
    }

    /**
     * 获取文件内容。
     *
     * @param string $filePath
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFile($filePath='')
    {
        if ($this->hasFile($filePath)){
            $content = $this->excel->readExcel(storage_path($filePath))->formatDates(true, "Y-m-d")->get()->toArray();
        }else{
            $content = [];
        }

        return $content;
    }

    /**
     * 历史数据缓存 12h。
     *
     * 缓存格式：
     * 上传凭证=>身份凭证：序列号；
     * 上传凭证：身份凭证=>序列号；
     * 上传凭证：身份凭证：序列号=>数据内容；
     * 上传凭证：page=>总页数
     * 上传凭证：head=>表格头
     * 
     * @param $content
     * @param $id
     * @return mixed
     */
    public function storeCache($content, $id)
    {
        $allLists = count($content)-1;
        $maxPage = ceil($allLists/15);
        $allIdentities = [];
        $turn = [];
        foreach ($content as $k=>$v){
            if ($k==0){
                //缓存表格头；上传凭证：head=》表格头
                \Cache::store('redis')->put("history:".$id.':head', $v, 720);
                continue;
            }
            //去除空值
            if (!$v[0] || !$v[1]){
                continue;
            }

            //获取身份证
            $lowerIdentity = $v[1] ? strtolower($v[1]) : '';
            $lowerIdentity = trim($lowerIdentity);

            $allIdentities[$id.":".$lowerIdentity][] = $k;

            if (!in_array($lowerIdentity.":".$k, $turn)){
                $turn[] = $lowerIdentity.":".$k;
            }
            //缓存所有数据；上传凭证：身份凭证：序列号=》数据内容
            \Cache::store('redis')->put("history:".$id.':'.$lowerIdentity.':'.$k, $v, 720);
        }

        //缓存序列号；上传凭证：身份凭证=》序列号；
        if (count($allIdentities)){
            $indentities = '';
            foreach ($allIdentities as $k=>$v){
                $indentities = implode("||", $v);
                \Cache::store('redis')->put("history:".$k, $indentities, 720);
            }
        }

        //缓存复合数据；上传凭证=》身份凭证：序列号；
        if (count($turn)){
            $turns = implode("||", $turn);
            \Cache::store('redis')->put("history:".$id, $turns, 720);
        }

        //缓存页数；上传凭证：page=》总页数
        \Cache::store('redis')->put("history:".$id.':page', $maxPage, 720);

        return $maxPage;
    }

    /**
     * 判断缓存是否存在。
     *
     * @param $uploadId
     *
     * @return bool
     */
    public function hasCache($uploadId)
    {
        return \Cache::store('redis')->has("history:".$uploadId);
    }

    /**
     * 分页缓存输出,附带身份证号检索。
     *
     * @param $uploadId
     * @param int $page
     * @param array $identity
     * @return array
     */
    public function getCache($uploadId, $page=1, $identity=[])
    {
        if (!$this->hasCache($uploadId)){
            return [];
        }

        $content = [];

        //带身份证的检索
        if (count($identity)){
            $allContent = [];
            foreach ($identity as $v){
                if (\Cache::store('redis')->has("history:".$uploadId.":".$v)) {
                    $allIndex = \Cache::store('redis')->get("history:".$uploadId . ":" . $v);
                    $indexArr = explode("||", $allIndex);
                    foreach ($indexArr as $vv){
                        $allContent[] = \Cache::store('redis')->get("history:".$uploadId . ":" . $v . ":" . $vv);
                    }
                }
            }

            if (count($allContent)){
                $maxPage = ceil(count($allContent)/15);
                $this->setMaxPage($uploadId, $maxPage);

                //数据范围[$from, $to)
                $from = ($page - 1) * 15;
                $to = $page * 15;
                $to = count($allContent) <= $to ? count($allContent) : $to;

                for ($i = $from; $i < $to; $i++) {
                    $content[] = $allContent[$i];
                }
            }
        }

        //不带身份证的检索
        if (!count($identity)) {
            $allIndex = \Cache::store('redis')->get("history:".$uploadId);

            //数据范围[$from, $to)
            $from = ($page - 1) * 15;
            $to = $page * 15;
            $indexArr = explode("||", $allIndex);
            $to = count($indexArr) <= $to ? count($indexArr) : $to;
            $maxPage = ceil(count($indexArr)/15);
            $this->setMaxPage($uploadId, $maxPage);

            for ($i = $from; $i < $to; $i++) {
                $content[] = \Cache::store('redis')->get("history:".$uploadId . ":" . $indexArr[$i]);
            }
        }
        return $content;
    }

    /**
     * 获取最大页数。
     *
     * @param $uploadId
     *
     * @return mixed
     */
    public function getMaxPage($uploadId)
    {
        return \Cache::store('redis')->get("history:".$uploadId.":page");
    }

    /**
     * 设置最大页数。
     * @param $uploadId
     *
     * @return mixed
     */
    private function setMaxPage($uploadId, $max)
    {
        return \Cache::store('redis')->put("history:".$uploadId.":page", $max, 720);
    }
}