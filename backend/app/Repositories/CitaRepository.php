<?php

namespace App\Repositories;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Collection;

class CitaRepository
{
    public function all(array $filtros = []): Collection
    {
        $query = Cita::with(['paciente', 'doctor']);

        if (!empty($filtros['doctor_id'])) {
            $query->where('doctor_id', $filtros['doctor_id']);
        }

        if (!empty($filtros['paciente_id'])) {
            $query->where('paciente_id', $filtros['paciente_id']);
        }

        if (!empty($filtros['desde'])) {
            $query->where('fecha_fin', '>=', $filtros['desde']);
        }

        if (!empty($filtros['hasta'])) {
            $query->where('fecha_inicio', '<=', $filtros['hasta']);
        }

        return $query->orderBy('fecha_inicio')->get();
    }

    public function find(int $id): ?Cita
    {
        return Cita::with(['paciente', 'doctor'])->find($id);
    }

    public function create(array $datos): Cita
    {
        return Cita::create($datos);
    }

    public function update(Cita $cita, array $datos): Cita
    {
        $cita->update($datos);

        return $cita->refresh()->load(['paciente', 'doctor']);
    }

    /**
     * RQF-03 / RQNF-07: existe una cita activa (no cancelada) del mismo
     * doctor cuyo rango de horario se solapa con [$inicio, $fin).
     */
    public function existsOverlapping(int $doctorId, string $inicio, string $fin, ?int $excludeId = null): bool
    {
        $query = Cita::where('doctor_id', $doctorId)
            ->where('estado', '!=', Cita::ESTADO_CANCELADA)
            ->where('fecha_inicio', '<', $fin)
            ->where('fecha_fin', '>', $inicio);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
