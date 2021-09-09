<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'message' => 'No se pudo registrar',
            'success' => true,
            'status' => 200,
            'error' =>$validator->errors()->first(), //obtener solo el primer error   
           ];
            return response()->json([
               'result' => [$result]
             ], 200); //401 -> crashea el app para eso se implementa safeApiCall en el App Android. Lo dejamos en 200 para mostrar los mensajes de validacion.
           
         }else{
            //Registrar el usuario
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
               'result' => [$result],
           ], 200);
    
        }   
      
   }
        public function login(Request $request){
           if(!Auth::attempt($request->only('email', 'password'))){
              $result = [
               'user' => null,
               'access_token' => null,
               'token_type' => null,
               'message' => 'Credenciales no validas',
               'success' => false,
               'status' => 200
              ];
              return response()->json([
                'result' => [$result],
              ], 200);

           }else{

            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            $result = [
               'user' => $user,
               'access_token' => $token,
               'token_type' => 'Bearer',
               'message' => 'Credenciales validas',
               'success' => true,
               'status' => 200
            ];
            return response()->json([
               'result' => [$result],
              ], 200);
           }
        }

        public function userInfo(Request $request){
         $result = [
            'user' =>  $request->user(),
         ];
         // return $request->user();
         return response()->json([
            'result' => [$result],
        ], 200);
        }

        public function storeReport(Request $request){
         $validator = Validator::make($request->all(), [ 
            'description' => 'required|max:255',
            'photo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:10000|nullable',
            //10000 = 10mb maximo, el valor esta en kilobytes
        ]);
        if ($validator->fails()) { 
         $result = [
            'message' => 'No se puede registrar el reporte',
            'success' => false,
            'status' => 200,
            'error' => $validator->errors()->first(), //obtener solo el primer error   
           ];
            return response()->json([
               'result' => [$result]
             ], 200); //401 -> crashea el app para eso se implementa safeApiCall en el App Android. Lo dejamos en 200 para mostrar los mensajes de validacion.
           
         }else{
            $data = $request->all();
            //Si no hay errores, registrar el reporte
            if($request->hasFile('photo')){
               $file = $request->file('photo');
               $name = 'reports/'. uniqid() . '.' . $file->extension();
               $file->storePubliclyAs('public', $name);
               $data['photo'] = $name;
           }
           $product = Report::create($data);

           //retornar respuesta json
           $result = [
            'message' => 'Reporte registrado correctamente',
            'success' => true,
            'status' => 200,
            'error' => null         
           ];
         
           return response()->json([
               'result' => [$result],
           ], 200);
    
        }   
      }

      public function getReports(){
         $reports = Report::orderBy('id','desc')->get();
       
         //retornar response json
         return response()->json([
            'result' => $reports,
        ], 200);
      
      }

      public function index(){
         $reports = Report::orderBy('id','desc')->get();

         return view("report.index", compact('reports'));
      }

}
