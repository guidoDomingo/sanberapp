<?php

namespace Database\Seeders;

use App\Models\Beneficiario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeneficiarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data from the Excel file
        $beneficiarios = [
            [
                'cedula' => '1234567',
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'carrera' => 'Ingeniería',
                'institucion' => 'Universidad Nacional',
                'adjudicado' => true
            ],
            [
                'cedula' => '7654321',
                'nombre' => 'Maria',
                'apellido' => 'Gomez',
                'carrera' => 'Medicina',
                'institucion' => 'Universidad Católica',
                'adjudicado' => true
            ],
            [
                'cedula' => '9876543',
                'nombre' => 'Carlos',
                'apellido' => 'Rodriguez',
                'carrera' => 'Derecho',
                'institucion' => 'Universidad Nacional',
                'adjudicado' => true
            ]
        ];
        
        foreach ($beneficiarios as $data) {
            Beneficiario::create($data);
        }
    }
}
