<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Equimed Internacional',
                'contact_name' => 'Carlos Méndez',
                'email' => 'contacto@equimed.com',
                'phone' => '555-789-1234',
                'address' => 'Av. Tecnología Médica 123',
                'city' => 'Ciudad de México',
                'postal_code' => '01234',
            ],
            [
                'name' => 'Instrumentos Médicos Especializados',
                'contact_name' => 'Patricia Vega',
                'email' => 'ventas@instrumentosme.com',
                'phone' => '555-456-7890',
                'address' => 'Calle Cirugía 456',
                'city' => 'Guadalajara',
                'postal_code' => '45678',
            ],
            [
                'name' => 'Distribuidora Farmacéutica Nacional',
                'contact_name' => 'Miguel Ángel Torres',
                'email' => 'info@distfarmacia.com',
                'phone' => '555-123-4567',
                'address' => 'Blvd. Salud 789',
                'city' => 'Monterrey',
                'postal_code' => '67890',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
