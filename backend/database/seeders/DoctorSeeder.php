<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctores = [
            ['nombre' => 'Dr. Pedro Salazar', 'especialidad' => 'Medicina General', 'email' => 'pedro.salazar@example.com', 'telefono' => '0991112233'],
            ['nombre' => 'Dra. Sofía Vera', 'especialidad' => 'Pediatría', 'email' => 'sofia.vera@example.com', 'telefono' => '0992223344'],
            ['nombre' => 'Dr. Andrés Molina', 'especialidad' => 'Cardiología', 'email' => 'andres.molina@example.com', 'telefono' => '0993334455'],
        ];

        foreach ($doctores as $doctor) {
            Doctor::updateOrCreate(['email' => $doctor['email']], $doctor);
        }
    }
}
