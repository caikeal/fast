<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table="news";

    /**
     * 追加发布时间距现在的时间
     * @var array
     */
    protected $appends = ['from_now'];

    protected $fillable=[
        'sender','receiver','type','is_read','status','relate_id','content'
    ];

    /**
     * 追加发布时间距现在的时间
     * **距离现在时间**      **显示格式**
     * < 1小时                 xx分钟前
     * 1小时-24小时            xx小时前
     * 1天-10天                xx天前
     * >10天                   直接显示日期
     * @return string|static
     */
    public function getFromNowAttribute()
    {
        Carbon::setLocale('zh');
        if (Carbon::now() >= Carbon::parse($this->attributes['created_at'])->addDays(10)){
            return Carbon::parse($this->attributes['created_at'])->toDateTimeString();
        }

        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
}
