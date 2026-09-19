<?php

namespace App\Repositories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class DoctorRepository
{
    public function all(): Collection
    {
        return Doctor::orderBy('nombre')->get();
    }

    public function find(int $id): ?Doctor
    {
        return Doctor::find($id);
    }
}
