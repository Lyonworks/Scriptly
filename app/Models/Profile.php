<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id','bio','location','link1','link2','link3','avatar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
