<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'HealthTech',
                'description' => 'Tecnología de salud avanzada para hospitales'
            ],
            [
                'name' => 'BioMed',
                'description' => 'Equipos biomédicos de última generación'
            ],
            [
                'name' => 'CarePlus',
                'description' => 'Soluciones de cuidado médico integral'
            ],
            [
                'name' => 'MedTech Solutions',
                'description' => 'Equipos médicos de monitoreo y diagnóstico de alta precisión'
            ],
            [
                'name' => 'SurgicalTech',
                'description' => 'Instrumental quirúrgico de calidad superior'
            ],
            [
                'name' => 'PharmaPro',
                'description' => 'Productos farmacéuticos de alta calidad para uso hospitalario'
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
