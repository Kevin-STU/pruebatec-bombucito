<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    public function index()
    {
        return Empleado::all();
    }

    public function store(Request $request)
    {
        $empleado = new Empleado($request->all());

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars');
            $empleado->avatar = $path;
        }

        $empleado->save();
        return response()->json($empleado, 201);
    }

    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return response()->json($empleado);
    }

    public function update(Request $request, $id)
{
    $empleado = Empleado::findOrFail($id);
    $empleado->fill($request->all());

    if ($request->hasFile('avatar')) {
        if ($empleado->avatar) {
            Storage::disk('public')->delete($empleado->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $empleado->avatar = $path;
    }

    $empleado->save();
    return response()->json($empleado, 200);
}

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        if ($empleado->avatar) {
            Storage::delete($empleado->avatar);
        }
        $empleado->delete();
        return response()->json(null, 204);
    }
}
