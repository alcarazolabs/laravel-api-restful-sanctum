<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{
    
        public function register(Request $request) {
           $validatedData = $request->validate([
            'name' => 'required|max:255|string',
            'email' => 'required|max:255|string|email|unique:users',
            'password' => 'required|max:255|string|min:6',
           ]);

           $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
           ]);

           //Crear token de acceso para el usuario
           //https://youtu.be/n-J3zw4OWmI?t=731
           $token = $user->createToken('auth_token')->plainTextToken;
           
           //retornar respuesta json al usuario con token de acceso
           return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
           ]);
    
        }   

        public function login(Request $request){
           if(!Auth::attempt($request->only('email', 'password'))){
              return response()->json([
               'message' => 'Credenciales no validas'
              ], 401);
           }else{
            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                  'User' => $user,
                  'access_token' => $token,
                  'token_type' => 'Bearer',
              ], 200);
           }
        }

        public function userInfo(Request $request){
        
          return $request->user();
      
        }

}
