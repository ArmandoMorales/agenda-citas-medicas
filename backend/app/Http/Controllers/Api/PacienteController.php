<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PacienteRepository;
use Illuminate\Http\JsonResponse;

class PacienteController extends Controller
{
    public function __construct(private PacienteRepository $pacientes)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->pacientes->all());
    }
}
