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
        // Lista de proveedores de ejemplo
        $suppliers = [
            [
                'name' => 'Distribuidora de Herramientas S.A.',
                'contact_name' => 'Juan Pérez',
                'email' => 'ventas@distherramientas.com',
                'phone' => '555-123-4567',
                'address' => 'Av. Industrial 456',
                'city' => 'Ciudad de México',
                'postal_code' => '01000',
            ],
            [
                'name' => 'Materiales y Construcción del Norte',
                'contact_name' => 'Ana González',
                'email' => 'contacto@maconorte.mx',
                'phone' => '555-987-6543',
                'address' => 'Blvd. Construcción 789',
                'city' => 'Monterrey',
                'postal_code' => '64000',
            ],
            [
                'name' => 'Ferretería Nacional',
                'contact_name' => 'Roberto Ramírez',
                'email' => 'ventas@ferrenacional.com',
                'phone' => '555-456-7890',
                'address' => 'Calle Herramienta 123',
                'city' => 'Guadalajara',
                'postal_code' => '44100',
            ],
            [
                'name' => 'Suministros Industriales S.A.',
                'contact_name' => 'Carmen Sánchez',
                'email' => 'info@sumindustriales.com',
                'phone' => '555-234-5678',
                'address' => 'Parque Industrial 567',
                'city' => 'Querétaro',
                'postal_code' => '76000',
            ],
            [
                'name' => 'Herramientas Profesionales de México',
                'contact_name' => 'Luis Torres',
                'email' => 'ventas@herpromex.com',
                'phone' => '555-876-5432',
                'address' => 'Av. Los Fabricantes 890',
                'city' => 'Puebla',
                'postal_code' => '72000',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
