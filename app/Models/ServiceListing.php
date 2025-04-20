<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceListing extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    public function service_provider(){
        return $this->belongsTo(ServiceProvider::class);
    }
}
