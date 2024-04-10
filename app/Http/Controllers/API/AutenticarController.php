<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccesoRequest;
use App\Http\Requests\RegistroRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutenticarController extends Controller
{
    public function registro(RegistroRequest $request) {

        // Metodo de Registro
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        
        //asignando rol
        $user->roles()->attach($request->roles);

        return response()->json([
            'res' => true,  
            'msg' => "Usuario registrado correctamente"  
        ],200); 
    }
    
    // Metodo de autenticación (Log in)
    public function login(AccesoRequest $request) {

        //Crear Las Apitokens que se relacionaran con el usuario
        $user = User::with('roles')->where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas!!'],
            ]);
        }
    
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'res '=> true,
            'token' => $token,
            'usuario' => $user,
            'msg' => 'Autenticación exitosa'
        ],200);
    }


    public function logout(Request $request) {
    // Eliminando token que fue usado para hacer autenticarse
    $request->user()->currentAccessToken()->delete();
    
    return response()->json([
        'res'=>true,
        'msg'=>'Sesión cerrada correctamente'
    ],200);
    }

}
