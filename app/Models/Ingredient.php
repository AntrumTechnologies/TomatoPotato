<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'recipe_step_id',
        'name',
        'quantity',
        'quantity_unit',
    ];
}
