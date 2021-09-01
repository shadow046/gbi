<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $connection = 'gbi';
    protected $guarded = [];
    protected $table = 'Data';
    public $timestamps = false;
}
