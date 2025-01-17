<?php

namespace App\Http\Controllers;

use App\Models\Contactanos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContactanosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    } 
    
    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarproducto');
        $consultas = DB::table('contactanos as c')
            ->select(
                'c.id as idconsulta',
                'c.nombres',
                'c.apellidos',
                'c.email',
                'c.telefono',
                'c.asunto',
                'c.servicio',
                'c.comentario'
            )
            ->where('c.estado', '!=', 'RESUELTO')
            ->get();
        return view("nosotros/contacto/indexSR", ['consultas' => $consultas, 'buscarpor' => $buscarpor]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contactanos  $contactanos
     * @return \Illuminate\Http\Response
     */


    public function edit($id, Request $request)
    {
        $buscarpor = $request->get('buscarproducto');
        $consulta = Contactanos::find($id);

        return view("nosotros/contacto/edit", ['consulta' => $consulta, 'buscarpor' => $buscarpor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contactanos  $contactanos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $buscarpor = $request->get('buscarproducto');
        //Regla de validación
        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'email' => 'required',
            'servicio' => 'required',
            'asunto' => 'required',
            'celular' => 'required',
            'estado' => 'required',
            'comentario' => 'required'

        ];
        //Usamos el Facade Validator para validar nuestra regla respecto a los datos recibidos en Request
        $validator = Validator::make($request->all(), $rules);
        //Si falla la validacion retornamos los errores
        if ($validator->fails()) {
            return $validator->errors();
        }
        //Instancia modelo Gender
        $consulta = Contactanos::find($id)->first();
        //Llevanos el modelo con los datos del Request
        $consulta->nombres = $request->nombres;
        $consulta->apellidos = $request->apellidos;
        $consulta->email = $request->email;
        $consulta->servicio = $request->servicio;
        $consulta->asunto = $request->asunto;
        $consulta->comentario = $request->comentario;
        $consulta->telefono = $request->celular;
        $consulta->estado = $request->estado;
        //Guardamos
        if ($consulta->update()) {

            $respuesta =  2;
            return redirect('/contactanos/index')->with('respuesta', $respuesta, 'buscarpor', $buscarpor);
        } else {

            $respuesta = -2;
            return redirect('/contactanos/index')->with('respuesta', $respuesta, 'buscarpor', $buscarpor);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contactanos  $contactanos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $consulta = Contactanos::find($id);

        if ($consulta->delete()) {
            $respuesta = 3;
        } else {
            $respuesta = -3;
        }

        return redirect('contactanos')->with('respuesta', $respuesta);
    }
}
