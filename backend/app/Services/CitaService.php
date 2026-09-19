<?php

namespace App\Services;

use App\Models\Cita;
use App\Repositories\CitaRepository;
use Illuminate\Database\Eloquent\Collection;

class CitaService
{
    public function __construct(private CitaRepository $citas)
    {
    }

    public function listar(array $filtros): Collection
    {
        return $this->citas->all($filtros);
    }

    public function buscar(int $id): ?Cita
    {
        return $this->citas->find($id);
    }

    public function crear(array $datos): Cita
    {
        $datos['estado'] = $datos['estado'] ?? Cita::ESTADO_PENDIENTE;

        return $this->citas->create($datos)->load(['paciente', 'doctor']);
    }

    public function reprogramar(Cita $cita, array $datos): Cita
    {
        return $this->citas->update($cita, $datos);
    }

    public function cambiarEstado(Cita $cita, string $estado): Cita
    {
        return $this->citas->update($cita, ['estado' => $estado]);
    }
}
