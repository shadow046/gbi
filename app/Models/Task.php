<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'task';

    function myform()
    {
        // return $this->hasMany("App\Models\Form", 'TaskId', 'Id');
        return $this->hasMany(
            "App\Models\FormField",
            'FormId',
            'Id'
        )->where('Label','Store Name')->select('Value');
    }
}
