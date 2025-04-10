<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('administrador');

        // Crear usuario de almacén
        $almacen = User::create([
            'name' => 'Usuario Almacén',
            'email' => 'almacen@example.com',
            'password' => Hash::make('password'),
        ]);
        $almacen->assignRole('almacen');

        // Usuario de prueba (sin rol asignado)
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
