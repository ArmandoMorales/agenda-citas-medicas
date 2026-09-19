<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DoctorRepository;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    public function __construct(private DoctorRepository $doctores)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->doctores->all());
    }
}
