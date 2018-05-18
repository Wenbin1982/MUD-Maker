<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
