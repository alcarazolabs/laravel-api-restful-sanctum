<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{
  
        public function register(Request $request) {
           //usar facade Validator para poder obtener los errores
           // https://stackoverflow.com/questions/52058152/laravel-custom-error-validation-json-response-object-to-array
         $validator = Validator::make($request->all(), [ 
            'name' => 'required|max:255|string',
            'email' => 'required|max:255|string|email|unique:users',
            'password' => 'required|max:255|string|min:6',
        ]);
        if ($validator->fails()) { 
         $result = [
            'message' => 'Registrado correctamente',
            'success' => true,
            'status' => 200,
            'error' =>$validator->errors()->first(), //obtener solo el primer error   
           ];
            return response()->json([
               'result' => $result
             ], 401);
           
         }else{

           $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
           ]);

           //Crear token de acceso para el usuario
           //https://youtu.be/n-J3zw4OWmI?t=731
           //$token = $user->createToken('auth_token')->plainTextToken;
           
           //retornar respuesta json al usuario con token de acceso
           $result = [
            'message' => 'Registrado correctamente',
            'success' => true,
            'status' => 200,
            'error' => null         
           ];
         
           return response()->json([
               'result' => $result
           ], 200);
    
        }   
      
   }
        public function login(Request $request){
           if(!Auth::attempt($request->only('email', 'password'))){
              return response()->json([
               'user' => null,
               'access_token' => null,
               'token_type' => null,
               'message' => 'Credenciales no validas',
               'success' => false,
               'status' => 401
              ], 401);

           }else{

            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                  'user' => $user,
                  'access_token' => $token,
                  'token_type' => 'Bearer',
                  'message' => 'Credenciales validas',
                  'success' => true,
                  'status' => 200
              ], 200);
           }
        }

        public function userInfo(Request $request){
        
         // return $request->user();
         return response()->json([
            'user' =>  $request->user(),
            'success' => true,
            'status' => 200
            
        ], 200);
        }

}
