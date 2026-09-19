<?php

namespace App\Repositories;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Collection;

class PacienteRepository
{
    public function all(): Collection
    {
        return Paciente::orderBy('nombre')->get();
    }

    public function find(int $id): ?Paciente
    {
        return Paciente::find($id);
    }

    public function create(array $datos): Paciente
    {
        return Paciente::create($datos);
    }
}
