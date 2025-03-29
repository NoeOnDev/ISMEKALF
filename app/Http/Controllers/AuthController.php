<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'almacen',
            'image' => $request->image,
        ]);

        // Enviar correo de verificación
        $user->sendEmailVerificationNotification();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Se ha enviado un enlace de verificación a tu correo electrónico.'
        ], 201);
    }

    /**
     * Iniciar sesión
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'error' => 'Credenciales inválidas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Verificar correo electrónico
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        // Validamos el hash y el usuario
        if (!$user || !hash_equals(
            (string) $request->route('hash'),
            sha1($user->getEmailForVerification())
        )) {
            // En caso de error, redirigimos al frontend con un parámetro de error
            return redirect($frontendUrl . '/verificacion?estado=error');
        }

        // Si ya está verificado
        if ($user->hasVerifiedEmail()) {
            return redirect($frontendUrl . '/verificacion?estado=ya-verificado');
        }

        // Marcamos como verificado
        $user->markEmailAsVerified();

        // Redireccionamos al frontend con estado exitoso
        return redirect($frontendUrl . '/verificacion?estado=exito');
    }

    /**
     * Reenviar el correo de verificación
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un usuario con ese correo electrónico'
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'El correo ya ha sido verificado'
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Se ha reenviado el enlace de verificación'
        ]);
    }
}
