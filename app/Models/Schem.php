<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schem extends Model
{
    use HasFactory;
    protected $connection = 'schem';
    protected $guarded = [];
    protected $table = 'COLUMNS';
    public $timestamps = false;
}
