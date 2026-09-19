<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pacientes = [
            ['nombre' => 'Ana Torres', 'documento' => '0102030405', 'email' => 'ana.torres@example.com', 'telefono' => '0991234567'],
            ['nombre' => 'Luis Ramírez', 'documento' => '0203040506', 'email' => 'luis.ramirez@example.com', 'telefono' => '0987654321'],
            ['nombre' => 'María Fernández', 'documento' => '0304050607', 'email' => 'maria.fernandez@example.com', 'telefono' => '0976543210'],
            ['nombre' => 'Carlos Jiménez', 'documento' => '0405060708', 'email' => 'carlos.jimenez@example.com', 'telefono' => '0965432109'],
        ];

        foreach ($pacientes as $paciente) {
            Paciente::updateOrCreate(['documento' => $paciente['documento']], $paciente);
        }
    }
}
