<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'telefono',
    ];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }
}
