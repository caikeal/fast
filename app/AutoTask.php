<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutoTask extends Model
{
    protected $table="auto_task";

    protected $fillable=[
        'creator','receiver','by','company_id','type','deal_time','memo'
    ];
}
