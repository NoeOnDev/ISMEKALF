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
        // Lista de marcas de ejemplo para nuestro inventario
        $brands = [
            [
                'name' => 'Truper',
                'description' => 'Herramientas y equipos de trabajo'
            ],
            [
                'name' => 'Makita',
                'description' => 'Herramientas eléctricas profesionales'
            ],
            [
                'name' => 'DeWalt',
                'description' => 'Herramientas eléctricas y accesorios'
            ],
            [
                'name' => 'Bosch',
                'description' => 'Herramientas y accesorios de precisión'
            ],
            [
                'name' => 'Milwaukee',
                'description' => 'Herramientas eléctricas de alta calidad'
            ],
            [
                'name' => 'Urrea',
                'description' => 'Herramientas manuales y de precisión'
            ],
            [
                'name' => 'Stanley',
                'description' => 'Herramientas manuales y de medición'
            ],
            [
                'name' => '3M',
                'description' => 'Productos de seguridad y cintas'
            ],
            [
                'name' => 'Pretul',
                'description' => 'Herramientas básicas y accesorios'
            ],
            [
                'name' => 'Rotoplas',
                'description' => 'Productos para almacenamiento de agua'
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
