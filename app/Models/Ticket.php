<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $connection = 'gbi';
    protected $guarded = [];
    protected $table = 'old_ticket';
    public $timestamps = false;
}
