<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar administrador para asignarlo como creador de los productos
        $admin = User::role('administrador')->first();

        if (!$admin) {
            // Si no hay administrador, usar el primer usuario
            $admin = User::first();
        }

        // Producto 1: Monitor de Signos Vitales
        $medtechBrand = Brand::where('name', 'MedTech Solutions')->first();
        $equimedSupplier = Supplier::where('name', 'Equimed Internacional')->first();

        $product1 = Product::create([
            // Datos Generales
            'name' => 'Monitor Multiparamétrico IntelliCare 4000',
            'model' => 'IC-4000-PRO',
            'cb_key' => 'CB-MSV-2023-450',
            'serial_number' => 'MSVP89745632',
            'batch' => 'L-2025-03-A',
            'group' => 'Monitoreo y Diagnóstico',

            // Clasificación y Origen
            'brand_id' => $medtechBrand->id,
            'specialty_area' => 'Cuidados Intensivos',
            'supplier_id' => $equimedSupplier->id,
            'brand_reference' => 'REF-MT-4000-ICU',

            // Datos Operativos
            'location' => 'Almacén Central, Estante A3',
            'manufacturer_unit' => 'Unidad',
            'freight_company' => 'TransMed Logistics',
            'freight_cost' => 1200.00,
            // Ya no se incluye expiration_date ni quantity
            'description' => 'Monitor multiparamétrico con pantalla táctil de 15", medición de ECG, SpO2, NIBP, temperatura y respiración. Incluye batería de respaldo con autonomía de 4 horas, alarmas configurables y conectividad a red inalámbrica hospitalaria.',

            // Usuario creador
            'created_by' => $admin->id
        ]);

        // Crear un lote inicial para el producto 1
        ProductBatch::create([
            'product_id' => $product1->id,
            'batch_number' => 'L-2025-03-A',
            'quantity' => 5,
            'location' => 'Almacén Central, Estante A3',
            'expiration_date' => null, // Sin caducidad para equipos
            'created_by' => $admin->id
        ]);

        // Producto 2: Set de Instrumental Quirúrgico
        $surgicalBrand = Brand::where('name', 'SurgicalTech')->first();
        $instrumentosSupplier = Supplier::where('name', 'Instrumentos Médicos Especializados')->first();

        $product2 = Product::create([
            // Datos Generales
            'name' => 'Set de Instrumental Quirúrgico Titanium Pro',
            'model' => 'TPQ-150',
            'cb_key' => 'CB-INS-2024-278',
            'serial_number' => 'TPQS2025-456',
            'batch' => 'QS-278-2025',
            'group' => 'Instrumental Quirúrgico',

            // Clasificación y Origen
            'brand_id' => $surgicalBrand->id,
            'specialty_area' => 'Cirugía General',
            'supplier_id' => $instrumentosSupplier->id,
            'brand_reference' => 'ST-CG-150-PRO',

            // Datos Operativos
            'location' => 'Área Estéril, Armario B12',
            'manufacturer_unit' => 'Kit',
            'freight_company' => 'Express Medical Supplies',
            'freight_cost' => 450.00,
            // Ya no se incluye expiration_date ni quantity
            'description' => 'Set completo de instrumental quirúrgico fabricado en acero inoxidable y titanio. Incluye 25 piezas: pinzas, tijeras, separadores, portaagujas y mangos de bisturí. Estuche de esterilización incluido.',

            // Usuario creador
            'created_by' => $admin->id
        ]);

        // Crear un lote inicial para el producto 2
        ProductBatch::create([
            'product_id' => $product2->id,
            'batch_number' => 'QS-278-2025',
            'quantity' => 8,
            'location' => 'Área Estéril, Armario B12',
            'expiration_date' => null, // Sin caducidad para instrumental
            'created_by' => $admin->id
        ]);

        // Producto 3: Antibiótico Inyectable
        $pharmaBrand = Brand::where('name', 'PharmaPro')->first();
        $farmaceuticaSupplier = Supplier::where('name', 'Distribuidora Farmacéutica Nacional')->first();

        $product3 = Product::create([
            // Datos Generales
            'name' => 'Ceftriaxona Sódica 1g',
            'model' => 'CS-1000',
            'cb_key' => 'CB-MED-2025-189',
            'serial_number' => null,
            'batch' => 'CFT-4587-2025',
            'group' => 'Antimicrobianos',

            // Clasificación y Origen
            'brand_id' => $pharmaBrand->id,
            'specialty_area' => 'Medicina Interna/Infectología',
            'supplier_id' => $farmaceuticaSupplier->id,
            'brand_reference' => 'PP-CFT-1G-IV',

            // Datos Operativos
            'location' => 'Farmacia Central, Refrigerador 2',
            'manufacturer_unit' => 'Ampolleta',
            'freight_company' => 'FarmExpress',
            'freight_cost' => 350.00,
            // Ya no se incluye expiration_date ni quantity
            'description' => 'Antibiótico cefalosporínico de tercera generación para administración intravenosa. Cada frasco ámpula contiene 1g de ceftriaxona. Se incluye ampolleta con 10ml de agua inyectable como diluyente. Indicado para infecciones graves causadas por microorganismos sensibles.',

            // Usuario creador
            'created_by' => $admin->id
        ]);

        // Crear un lote inicial para el producto 3
        ProductBatch::create([
            'product_id' => $product3->id,
            'batch_number' => 'CFT-4587-2025',
            'quantity' => 500,
            'location' => 'Farmacia Central, Refrigerador 2',
            'expiration_date' => '2026-12-15', // Este producto sí tiene caducidad
            'created_by' => $admin->id
        ]);

        // Actualizar cantidades de todos los productos
        $products = Product::all();
        foreach ($products as $product) {
            $product->quantity = $product->getTotalQuantityAttribute();
            $product->save();
        }
    }
}
