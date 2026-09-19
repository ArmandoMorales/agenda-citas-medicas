<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pacientes = Paciente::orderBy('id')->get();
        $doctores = Doctor::orderBy('id')->get();

        if ($pacientes->isEmpty() || $doctores->isEmpty()) {
            return;
        }

        $hoy = Carbon::now()->startOfDay();

        $citas = [
            [
                'paciente_id' => $pacientes[0]->id,
                'doctor_id' => $doctores[0]->id,
                'fecha_inicio' => $hoy->copy()->addDay()->setTime(9, 0),
                'fecha_fin' => $hoy->copy()->addDay()->setTime(9, 30),
                'motivo' => 'Control general',
                'estado' => Cita::ESTADO_PENDIENTE,
            ],
            [
                'paciente_id' => $pacientes[1]->id,
                'doctor_id' => $doctores[1]->id,
                'fecha_inicio' => $hoy->copy()->addDay()->setTime(11, 0),
                'fecha_fin' => $hoy->copy()->addDay()->setTime(11, 30),
                'motivo' => 'Chequeo pediátrico',
                'estado' => Cita::ESTADO_CONFIRMADA,
            ],
            [
                'paciente_id' => $pacientes[2]->id,
                'doctor_id' => $doctores[2]->id,
                'fecha_inicio' => $hoy->copy()->addDays(2)->setTime(15, 0),
                'fecha_fin' => $hoy->copy()->addDays(2)->setTime(15, 30),
                'motivo' => 'Evaluación cardiológica',
                'estado' => Cita::ESTADO_CANCELADA,
            ],
            [
                'paciente_id' => $pacientes[3]->id,
                'doctor_id' => $doctores[0]->id,
                'fecha_inicio' => $hoy->copy()->subDay()->setTime(10, 0),
                'fecha_fin' => $hoy->copy()->subDay()->setTime(10, 30),
                'motivo' => 'Revisión de resultados',
                'estado' => Cita::ESTADO_ATENDIDA,
            ],
        ];

        foreach ($citas as $cita) {
            Cita::updateOrCreate(
                [
                    'paciente_id' => $cita['paciente_id'],
                    'doctor_id' => $cita['doctor_id'],
                    'fecha_inicio' => $cita['fecha_inicio'],
                ],
                $cita
            );
        }
    }
}
