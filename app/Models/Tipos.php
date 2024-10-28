<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos extends Model
{
    /** @use HasFactory<\Database\Factories\TiposFactory> */
    use HasFactory;
    protected $fillable = ['tipo'];
}
