<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table="news";

    protected $fillable=[
        'sender','receiver','type','is_read','status','relate_id'
    ];
}
