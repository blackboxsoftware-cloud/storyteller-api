<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public function user(){
        $this->belongsTo(Admin::class);
    }
}
