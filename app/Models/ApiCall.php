<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    protected $casts = ['payload' => 'array', 'response' => 'array'];
    protected $fillable = ['user_id','endpoint','response_time_ms','status_code','payload','response'];

    public function user() { return $this->belongsTo(User::class); }
}
