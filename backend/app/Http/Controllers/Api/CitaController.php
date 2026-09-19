<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use App\Http\Requests\UpdateEstadoCitaRequest;
use App\Services\CitaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function __construct(private CitaService $citaService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $citas = $this->citaService->listar(
            $request->only(['doctor_id', 'paciente_id', 'desde', 'hasta'])
        );

        return response()->json($citas);
    }

    public function store(StoreCitaRequest $request): JsonResponse
    {
        $cita = $this->citaService->crear($request->validated());

        return response()->json($cita, 201);
    }

    public function show(int $id): JsonResponse
    {
        $cita = $this->citaService->buscar($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada.'], 404);
        }

        return response()->json($cita);
    }

    public function update(UpdateCitaRequest $request, int $id): JsonResponse
    {
        $cita = $this->citaService->buscar($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada.'], 404);
        }

        $cita = $this->citaService->reprogramar($cita, $request->validated());

        return response()->json($cita);
    }

    public function actualizarEstado(UpdateEstadoCitaRequest $request, int $id): JsonResponse
    {
        $cita = $this->citaService->buscar($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada.'], 404);
        }

        $cita = $this->citaService->cambiarEstado($cita, $request->validated()['estado']);

        return response()->json($cita);
    }
}
