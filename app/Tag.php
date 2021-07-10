<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function posts(Type $var = null)
    {
        return $this->belongsToMany('App\Post');
    }
}
