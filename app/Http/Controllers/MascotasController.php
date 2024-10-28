<?php

namespace App\Http\Controllers;

use App\Models\Tipos;
use App\Models\Mascotas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MascotasController extends Controller

{

    public function index()
    {
        $mascotas = Mascotas::select('mascotas.*', 'tipos.tipo as tipo')
            ->join('tipos', 'tipos.id', '=', 'mascotas.id_tipo')
            ->get();

        $mascotas->transform(function ($mascota) {
            $mascota->foto = $mascota->foto ? url('storage/' . $mascota->foto) : null;
            return $mascota;
        });
        return response()->json($mascotas);
    }

    public function store(Request $request)
    {
        $reglas = [
            'id_tipo' => 'required|exists:tipos,id',
            'raza' => 'required|string|min:1|max:50',
            'nombre' => 'required|string|min:1|max:50',
            'cuidados' => 'required|string|min:1|max:50',
            'fecha_nacimiento' => 'required|date',
            'precio' => 'required|numeric|min:500|max:999',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        $validator = Validator::make($request->all(), $reglas);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Guardar la imagen si se ha subido
        $filePath = null;
        if ($request->hasFile('foto')) {
            $fileName = time() . '.' . $request->foto->extension();
            $filePath = $request->file('foto')->storeAs('imagenes_mascotas', $fileName, 'public');
        }

        // Crear el nuevo registro con la ruta de la imagen guardada
        $mascotas = Mascotas::create([
            'id_tipo' => $request->id_tipo,
            'raza' => $request->raza,
            'nombre' => $request->nombre,
            'cuidados' => $request->cuidados,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'precio' => $request->precio,
            'foto' => $filePath // Guardar la ruta de la imagen
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Mascota creada correctamente c:',
            'data' => $mascotas
        ], 200);
    }

    public function show($id)
    {
        $mascota = Mascotas::find($id);
        if (!$mascota){
            return response()->json([
                'status' =>false,
                'message' => 'La mascota seleccionada no existe'
            ],400);
        }
        return response()->json(['status' =>true, 'data' => $mascota]);
    }

    public function update(Request $request, $id)
    {
    // Buscar el registro manualmente
        $mascotas = Mascotas::find($id);
        if (!$mascotas) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la mascota seleccionada'
            ], 404);
        }

        $reglas = [
            'id_tipo' => 'required|numeric|exists:tipos,id',
            'raza' => 'required|string|min:1|max:50',
            'nombre' => 'required|string|min:1|max:50',
            'cuidados' => 'required|string|min:1|max:50',
            'fecha_nacimiento' => 'required|date',
            'precio' => 'required|numeric|min:500|max:999',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Validar los datos recibidos
        $validator = Validator::make($request->all('_method'), $reglas);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Manejar la imagen si se sube una nueva
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($mascotas->foto && Storage::disk('public')->exists($mascotas->foto)) {
                Storage::disk('public')->delete($mascotas->foto);
            }

            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $filePath = $foto->storeAs('imagenes_mascotas', $fotoName, 'public');
            $mascotas->foto = $filePath;
        }

        // Actualizar los otros campos
        $mascotas->update($request->except(['_method', 'foto']));
        $mascotas->save();

        return response()->json([
            'status' => true,
            'message' => 'Mascota actualizada correctamente c:'
        ], 200);
    }

    public function destroy($id)
    {
        $mascota = Mascotas::find($id);
        if (!$mascota) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la mascota seleccionada',
            ], 404);
        }
        if ($mascota->foto) {
            Storage::disk('public')->delete($mascota->foto);
        }

        $mascota->delete();

        return response()->json([
            'status' => true,
            'message' => 'Mascota eliminada correctamente c:',
        ], 200);
    }

    public function MascotasByTipos()
    {
        $mascotas = Mascotas::select(DB::raw('count(mascotas.id) as count, tipos.tipo'))
            ->rightJoin('tipos', 'tipos.id', '=', 'mascotas.id_tipo')
            ->groupBy('tipos.tipo')->get();
        return response()->json($mascotas);
    }

    public function getAllMascotas()
    {
        $mascotas = Mascotas::select('mascotas.*', 'tipos.tipo as tipos')
            ->join('tipos', 'tipos.id', '=', 'mascotas.id_tipo')
            ->get();

        return response()->json($mascotas);
    }
}