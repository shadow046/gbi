<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PStatusLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'sqlsrv';
    protected $table = 'TaskStatusLog';
}
