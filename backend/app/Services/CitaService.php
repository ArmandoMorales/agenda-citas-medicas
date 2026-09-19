<?php

namespace App\Services;

use App\Exceptions\ConflictoHorarioException;
use App\Exceptions\TransicionEstadoInvalidaException;
use App\Models\Cita;
use App\Repositories\CitaRepository;
use Illuminate\Database\Eloquent\Collection;

class CitaService
{
    /**
     * RQF-05: cancelada y atendida son estados terminales, no admiten
     * mas transiciones. Se valida en el servidor (RQNF-07).
     */
    private const TRANSICIONES_PERMITIDAS = [
        Cita::ESTADO_PENDIENTE => [Cita::ESTADO_CONFIRMADA, Cita::ESTADO_CANCELADA],
        Cita::ESTADO_CONFIRMADA => [Cita::ESTADO_ATENDIDA, Cita::ESTADO_CANCELADA],
        Cita::ESTADO_CANCELADA => [],
        Cita::ESTADO_ATENDIDA => [],
    ];

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

        $this->validarDisponibilidad($datos['doctor_id'], $datos['fecha_inicio'], $datos['fecha_fin']);

        return $this->citas->create($datos)->load(['paciente', 'doctor']);
    }

    public function reprogramar(Cita $cita, array $datos): Cita
    {
        $doctorId = $datos['doctor_id'] ?? $cita->doctor_id;
        $inicio = $datos['fecha_inicio'] ?? $cita->fecha_inicio;
        $fin = $datos['fecha_fin'] ?? $cita->fecha_fin;

        $this->validarDisponibilidad((int) $doctorId, (string) $inicio, (string) $fin, $cita->id);

        return $this->citas->update($cita, $datos);
    }

    public function cambiarEstado(Cita $cita, string $estado): Cita
    {
        if ($cita->estado === $estado) {
            return $cita;
        }

        $permitidos = self::TRANSICIONES_PERMITIDAS[$cita->estado] ?? [];

        if (!in_array($estado, $permitidos, true)) {
            throw new TransicionEstadoInvalidaException(
                "No se puede cambiar la cita de '{$cita->estado}' a '{$estado}'."
            );
        }

        return $this->citas->update($cita, ['estado' => $estado]);
    }

    /**
     * RQF-03 / RQNF-07: valida en el servidor que el doctor no tenga
     * otra cita activa solapada antes de crear o reprogramar.
     */
    private function validarDisponibilidad(int $doctorId, string $inicio, string $fin, ?int $excludeId = null): void
    {
        if ($this->citas->existsOverlapping($doctorId, $inicio, $fin, $excludeId)) {
            throw new ConflictoHorarioException();
        }
    }
}
