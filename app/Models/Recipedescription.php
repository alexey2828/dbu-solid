<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipedescription extends Model
{
    use HasFactory;

    protected $table = 'recipedescription';
    public $timestamps = false;
    protected $fillable = ['codeRecipe', 'codeComponent', 'weightSummer', 'weightWinter'];
}
