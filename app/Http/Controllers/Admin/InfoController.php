<?php

namespace App\Http\Controllers\Admin;

use App\Info;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;

class InfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $infos = Info::orderBy('updated_at', 'desc')->paginate(15);

        return view('admin.info', ['infos' => $infos]);
    }

    public function sendSystemInfo(Request $request)
    {
        $file = $request->file('img');
        $content = $request->input('content');
        $title = $request->input('title');
        $img = '';

        if ($file) {
            $img = time().'.png';
            Storage::disk('img')->put($img, file_get_contents($file->getRealPath()));
        }


        $info = Info::create(['title'=>$title, 'p'=>$content, 'img' => $img?('system_info/'.$img):'']);
        $info->img = $info->img ? url($info->img) : '';

        return ['ret_num' => 0, 'ret_msg' => $info];
    }

    public function delete($id)
    {
        $info = Info::find($id);
        if ($info) {
            $info->delete();
        }
        return ['ret_num' => 0, 'ret_msg' => ''];
    }

    public function close($id)
    {
        $info = Info::find($id);
        if ($info) {
            $info->is_show = 0;
            $info->update();
        }
        return ['ret_num' => 0, 'ret_msg' => ''];
    }

    public function open($id)
    {
        $info = Info::find($id);
        if ($info) {
            $info->is_show = 1;
            $info->update();
        }
        return ['ret_num' => 0, 'ret_msg' => ''];
    }
}
