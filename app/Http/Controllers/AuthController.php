<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function create (Request $request){
        $reglas =[
            'name' =>'required|string|max:100',
            'email' =>'required|email|max:100|unique:users',
            'password' => 'required|string|min:8'
        ];
        $validator = Validator::make($request->input(),$reglas);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Usuario creado correctamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function login(Request $request){
        $reglas =[
            'email' => 'required|email',
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->input(),$reglas);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status' => false,
                'errors' => ['Sin autorización']
            ],401);
        }
        $user = User::where('email',$request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado correctamente',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function logout(Request $request)
{
    // Eliminar todos los tokens generados por el usuario autenticado
    $request->user()->tokens()->delete();

    return response()->json([
        'status' => true,
        'message' => 'Sesión cerrada correctamente',
    ], 200);
}
}
