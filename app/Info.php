<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $table="infos";

    protected $fillable=['title', 'p', 'img'];
}
