<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarPacienteRequest;
use App\Http\Requests\GuardarPacienteRequest;
use App\Http\Resources\PacienteResource;
use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{

    public function index()
    {
        /* return Paciente::all(); */

        //con resources
        return PacienteResource::collection(Paciente::all());
        
        //paginacion
        /* return PacienteResource::collection(Paciente::paginate(3)); */
    }


    public function store(GuardarPacienteRequest $request)
    {
        //guardando en la bd el registro de pacientes
/*         return response()->json([
            'res' => true,
            'msg' => "Paciente registrado correctamente"
        ],200); */

       //con resources
       return (new PacienteResource(Paciente::create($request->all())))
       ->additional(['msg' => "Paciente Registrado correctamente"])
       ->response()
       ->setStatusCode(200);
    }


    public function show(Paciente $paciente)
    {
 /*        return response()->json([
            'res' => true,
            'paciente' => $paciente
        ],200); */

        //con resources
        return new PacienteResource($paciente);
    }


    public function update(ActualizarPacienteRequest $request, Paciente $paciente)
    {
/*         $paciente->update($request->all());
        return response()->json([
            'res' => true,
            'msg' => "Paciente actualizado correctamente"
        ], 202); */

        //con resources
        $paciente->update($request->all());
        return (new PacienteResource($paciente))
        ->additional(['msg' => "Paciente actualizado correctamente"])
        ->response()
        ->setStatusCode(202);
    }


    public function destroy(Paciente $paciente)
    {
/*         $paciente->delete();
        return response()->json([
            'res' => true,
            'msg' => "Paciente Eliminado correctamente"
        ], 200); */

        //con resources
        $paciente->delete();
        return (new PacienteResource($paciente))
        ->additional(['msg' => "Paciente Eliminado correctamente"])
        ->response()
        ->setStatusCode(200);
    }
}
