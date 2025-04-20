<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'service_category_id',
        'description',
        'website',
        'social_media',
        'profile_image',
        'location',
        'availability',
        'preferred_genres',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function category() {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
    public function skills() {
        return $this->hasMany(ProviderSkill::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function listings() {
        return $this->hasMany(ServiceListing::class);
    }
}
