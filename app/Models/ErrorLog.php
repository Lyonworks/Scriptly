<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $casts = ['context' => 'array'];
    protected $fillable = ['level','message','trace','context'];
}

