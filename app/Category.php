<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function posts(Type $var = null)
    {
        return $this->hasMany('App\Post');
    }
}
