<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $connection = 'gbi';
    protected $guarded = [];
    protected $table = 'History';
    public $timestamps = false;
}
