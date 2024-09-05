<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grocery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'groceries';

    protected $fillable = [
        'user_id',
        'name',
        'quantity',
        'quantity_unit',
    ];
}
