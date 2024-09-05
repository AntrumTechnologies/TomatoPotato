<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeStep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'recipe_id',
        'title',
        'description',
        'picture',
    ];
}
