<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function posts(){
        return $this->belongsToMany('App\Post')->withTimestamps();///withTimestamps for created_at & updated_at
    }

   
}
