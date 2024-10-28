<?php

namespace App\Http\Controllers;

use App\Models\Tipos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TiposController extends Controller
{
    public function index()
    {
        $tipo = Tipos::all();
        return response()->json($tipo);
    }

    public function store(Request $request)
    {
        $reglas = ['tipo' => 'required|string|min:1|max:50'];
        $validator = Validator::make($request->input(),$reglas);
        if($validator->fails()){
            return response()->json([
                'status' =>false,
                'error' => $validator->errors()->all()
            ],400);
        }
        $tipo = new Tipos ($request->input());
        $tipo->save();
        return response()->json([
            'status' =>true,
            'message' => 'Se ha creado  el tipo de mascota correctamente c:'
        ],200);
    }

    public function show(Tipos $tipos, $id)
    {
        $tipo = Tipos::findOrFail($id);
        return response()->json(['status' => true, 'data' => $tipo], 200);
    }

    public function update(Request $request, Tipos $tipos, $id)
    {
        // Encuentra el registro por ID
    $tipos = Tipos::find($id);

    // Verifica si el registro existe
    if (!$tipos) {
        return response()->json([
            'status' => false,
            'message' => 'Tipo de mascota no encontrado'
        ], 404);
    }
    // Reglas de validación
    $reglas = ['tipo' => 'required|string|min:1|max:50'];
    $validator = Validator::make($request->all(), $reglas);
    // Si falla la validación
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'error' => $validator->errors()->all()
        ], 400);
    }
    // Actualiza el registro existente
    $tipos->tipo = $request->input('tipo');
    $tipos->save();

    return response()->json([
        'status' => true,
        'message' => 'Tipo de mascota actualizado correctamente c:'
    ], 200);
}

    public function destroy(Tipos $tipos,$id)
    {
        $tipo = Tipos::findOrFail($id);
        $tipo->delete();
        return response()->json([
            'status' =>true,
            'message' => 'Tipo de mascota eliminada correctamente c:'
        ],200);
    }
}
