php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Sección 1: Datos Generales y de Identificación
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('cb_key')->nullable(); // clave CB
            $table->string('serial_number')->nullable();
            $table->string('batch')->nullable(); // lote
            $table->string('group')->nullable();

            // Sección 2: Datos de Clasificación y Origen
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->string('specialty_area')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->string('brand_reference')->nullable();

            // Sección 3: Datos Operativos y Adicionales
            $table->string('location')->nullable();
            $table->string('manufacturer_unit')->nullable();
            $table->string('freight_company')->nullable(); // fletera
            $table->decimal('freight_cost', 10, 2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();

            // Usuario creador
            $table->foreignId('created_by')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
