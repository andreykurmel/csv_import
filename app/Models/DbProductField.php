<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DbProductField extends Model
{
    protected $table = 'product_fields';

    protected $fillable = [
        'field', 'name', 'type'
    ];
}
