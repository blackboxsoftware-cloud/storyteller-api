<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryTeller extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function forumPosts() {
        return $this->hasMany(ForumPost::class);
    }
    public function category() {
        return $this->belongsTo(ServiceCategory::class);
    }
    public function skills() {
        return $this->hasMany(ProviderSkill::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
