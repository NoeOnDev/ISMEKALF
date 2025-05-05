<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Búsqueda por nombre o email
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por rol
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    // Método para mostrar el formulario de creación
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Actualizar método store
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar el rol seleccionado
        $user->assignRole($request->role);

        return redirect()->route('admin.users')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Actualizar método update
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // Validación para evitar que un administrador cambie su propio rol
        if ($user->id === auth()->id() && $user->hasRole('administrador') && $request->role !== 'administrador') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No puedes cambiar tu propio rol de administrador.');
        }

        // Validar que no sea el último administrador
        if ($user->hasRole('administrador') && $request->role !== 'administrador') {
            $adminCount = User::role('administrador')->count();
            if ($adminCount <= 1) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No puedes cambiar el rol. Este es el último usuario administrador.');
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Actualizar rol (primero quitar todos y luego asignar el nuevo)
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // Añade este método para verificar si el usuario tiene relaciones
    private function userHasRelations(User $user): bool
    {
        // Verifica si el usuario ha creado órdenes
        if ($user->orders()->count() > 0) {
            return true;
        }

        // Verifica si el usuario ha creado productos
        if ($user->products()->count() > 0) {
            return true;
        }

        // Verifica si el usuario ha creado lotes de productos
        if ($user->productBatches()->count() > 0) {
            return true;
        }

        // Si no hay relaciones, devuelve false
        return false;
    }

    // Método para bloquear usuario
    public function block(User $user)
    {
        // Valida que no sea el usuario logueado
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'No puedes bloquear tu propia cuenta.');
        }

        // Valida que no sea el último administrador activo
        if ($user->hasRole('administrador')) {
            $activeAdmins = User::role('administrador')->where('active', true)->count();
            if ($activeAdmins <= 1) {
                return redirect()->route('admin.users')
                    ->with('error', 'No puedes bloquear al último administrador activo.');
            }
        }

        // Actualizar el usuario para bloquear y forzar cierre de sesión
        $user->active = false;
        $user->remember_token = null; // Invalidar "recordarme"
        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'Usuario bloqueado correctamente. Si tenía sesiones activas, han sido cerradas.');
    }

    // Método para desbloquear usuario
    public function unblock(User $user)
    {
        $user->active = true;
        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'Usuario desbloqueado correctamente.');
    }

    // Actualiza el método destroy para verificar relaciones
    public function destroy(User $user)
    {
        // No permitir eliminar el propio usuario
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Si tiene relaciones, sugerir bloquearlo en lugar de eliminarlo
        if ($this->userHasRelations($user)) {
            return redirect()->route('admin.users')
                ->with('error', 'Este usuario no puede ser eliminado porque tiene registros asociados. Considera bloquearlo en su lugar.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
