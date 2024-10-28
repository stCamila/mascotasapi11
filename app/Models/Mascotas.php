<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascotas extends Model
{
    /** @use HasFactory<\Database\Factories\MascotasFactory> */
    use HasFactory;
    protected $fillable = ['id_tipo','raza','nombre','cuidados','fecha_nacimiento','precio','foto'];
}
