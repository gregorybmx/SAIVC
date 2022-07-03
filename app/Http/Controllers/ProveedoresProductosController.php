<?php

/*
Integrantes: Anthony Rugama,
            Carlos Reyes,
            Greivin Montoya,
            Kendall Fallas 
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedoresProductosController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except'=>['store']]);
    }

    public function store(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 404,
            'message' => 'El proveedor no existe'
        );

        $json = $request->input('json',null);

        if($json)
        {
            $data = json_decode($json, true);

            if(!empty($data))
            {
                $data  = array_map('trim',$data);

                $rules = [
                    'proveedor_id' => 'required',
                    'producto_id' => 'required'
                ];

                $validate = \Validator::make($data,$rules);

                if($validate->fails())
                {
                    $response['message'] = 'No se ha guardado el registro';
                    $response['errors'] = $validate->errors();
                }
                else
                {
                    $proveedorProducto = new ProveedorProducto();
                    $proveedorProducto->proveedor_id = $data['proveedor_id'];
                    $proveedorProducto->producto_id = $data['producto_id'];

                    $proveedorProducto->save();

                    $response['status'] = 'success';
                    $response['code'] = 200;
                    $response['message'] = 'Registro guardado correctamente';
                }
            }

            else
            {
                $response['code'] = 404;
                $response['message'] = 'No se han enviado datos';
            }
        }

        return response()->json($response,$response['code']);
    }

    public function destroy($id)
    {
        $response = array(
            'status' => 'error',
            'code' => '404',
            'data' => 'Falta el identificador'
        );

        if(isset($id))
        {
            $deleted = ProveedoresProductos::where('id',$id) -> delete();

            if($deleted)
            {
                $response['status'] = 'success';
                $response['code'] = 200;
                $response['data'] = 'El registro se ha eliminado correctamente';
            }

            else
            {
                $response['data'] = 'No se ha eliminado el registro';
            }
        }

        return response()->json($response, $response['code']);
    }
}