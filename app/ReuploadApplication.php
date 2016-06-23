<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReuploadApplication extends Model
{
    protected $table="reupload_application";

    protected $fillable=[
        'applier','receiver','upload_id','status','expiration'
    ];
}
