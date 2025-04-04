<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // En tu controlador de usuarios (por ejemplo, UserController.php)
    public function index()
    {
        try {
            $usuarios = User::all(); // Asegúrate de que la consulta sea correcta
            return response()->json($usuarios);
        } catch (\Exception $e) {
            \Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los usuarios'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string|max:50'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6', // No valida si no se envía
            'role' => 'required|string|max:50'
        ]);

        $user = User::findOrFail($id);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Solo actualiza la contraseña si se proporciona
        if (isset($validated['password']) && !empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->role = $validated['role'];

        $user->save();

        return response()->json($user, 200);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed', // Confirmación de la nueva contraseña
        ]);

        $user = auth()->user(); // Obtener el usuario autenticado
        $user->password = bcrypt($request->password); // Actualizar la contraseña
        $user->save();

        return response()->json(['message' => 'Contraseña cambiada exitosamente'], 200);
    }



}