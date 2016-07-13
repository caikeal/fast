<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleStatistics extends Model
{
    protected $table = 'module_statistics';

    protected $fillable = [
        'user_id', 'ip', 'module'
    ];
}
