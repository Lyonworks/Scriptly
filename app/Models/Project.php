<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'is_public',
        'forked_from',
        'html',
        'css',
        'js',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(ProjectLike::class);
    }

    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }
}
