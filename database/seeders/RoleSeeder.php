<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $admin = Role::create(['name' => 'administrador']);
        $almacen = Role::create(['name' => 'almacen']);

        // Crear permisos básicos
        $verProductos = Permission::create(['name' => 'ver productos']);
        $crearProductos = Permission::create(['name' => 'crear productos']);
        $editarProductos = Permission::create(['name' => 'editar productos']);
        $eliminarProductos = Permission::create(['name' => 'eliminar productos']);

        $verUsuarios = Permission::create(['name' => 'ver usuarios']);
        $crearUsuarios = Permission::create(['name' => 'crear usuarios']);
        $editarUsuarios = Permission::create(['name' => 'editar usuarios']);
        $eliminarUsuarios = Permission::create(['name' => 'eliminar usuarios']);

        // Asignar permisos al rol administrador
        $admin->givePermissionTo([
            'ver productos', 'crear productos', 'editar productos', 'eliminar productos',
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios'
        ]);

        // Asignar permisos al rol almacén
        $almacen->givePermissionTo([
            'ver productos', 'crear productos', 'editar productos'
        ]);
    }
}
