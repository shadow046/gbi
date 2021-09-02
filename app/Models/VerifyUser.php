<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    protected $guarded = [];
    protected $connection = 'gbi';
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
