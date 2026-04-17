<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $fillable = [
        'phone_number',
        'type',
        'name',
        'amount',
        'status',
        'package_id',
    ];
}
