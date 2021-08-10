<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'form';

    function SubCategory()
    {
 

            return $this->hasMany("App\Models\FormField", 'FormId', 'Id')
            ->where('Label','Ownership')->select('Value');
        
    }
}
