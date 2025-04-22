<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $with = ['author'];

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments() {
        return $this->hasMany(BlogComment::class);
    }

}
