<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';

    protected $fillable=[
        'creator','receiver','title','detail','answer','tags','type','status'
    ];

    protected $dates=['answer_at'];
}
