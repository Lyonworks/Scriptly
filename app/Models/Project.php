<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

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

    public function getLanguagesAttribute()
    {
        $langs = [];

        if (!empty($this->html)) $langs[] = 'HTML';
        if (!empty($this->css))  $langs[] = 'CSS';
        if (!empty($this->js))   $langs[] = 'JavaScript';

        return count($langs) ? implode(', ', $langs) : '-';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (request()->is('admin/*')) {
            return $this->where('id', $value)->firstOrFail();
        }

        return $this->where('slug', $value)->firstOrFail();
    }
}
