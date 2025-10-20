<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectLike extends Model
{
    protected $table = 'project_likes';
    protected $fillable = ['project_id','user_id'];
    public function project() { return $this->belongsTo(Project::class); }
    public function user() { return $this->belongsTo(User::class); }
}
