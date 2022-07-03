<?php

/*
Integrantes: Anthony Rugama,
            Carlos Reyes,
            Greivin Montoya,
            Kendall Fallas 
*/

namespace App\Http\Controllers;

use App\Models\FacturaVenta;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class FacturaVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['show','store', 'index']]);
    }

    //Index -> Devuelve todos los elementos mediante el metodo Get
    public function index()
    {
        $response = array(
            'status' => 'success',
            'code' => '204',
            'data'=>'No se han agregado registros'
        );

        $data = FacturaVenta::all(); //obtengo el arreglo de todos las Facturas venta

        if (sizeof($data) > 0) //compruebo si el arreglo viene vacio
        {
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $data;
        }

        return response()->json($response, $response['code']);
    }

    //Show -> devuelve elemento buscado por id mediante el metodo Get
    public function show($id)
    {
        $response = array(
            'status' => 'error',
            'code' => 404,
            'data' => 'Recurso no encontrado'
        );

        $facturaVenta = FacturaVenta::find($id);

        if (is_object($facturaVenta))
        {
            $facturaVenta = $facturaVenta -> load('vendedor','detalleFactura');
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $facturaVenta;
        }

        return response()->json($response, $response['code']);
    }

    //store ->agrega o guarda elemento mediante metodo Post
    public function store(Request $request) //Implementar comprobacion de existencia de archivo json
    {
        $response = array(
            'status' => 'error',
            'code' => 406,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request->input('json', null);

        if($json)
        {
            $data = json_decode($json, true);


            $rules = [
                'user_id' => 'required',
                'subtotal' => 'required',
                'iva' => 'required',
                'total' => 'required'
            ];

            $validate = \validator($data, $rules);

            if ($validate->fails()) {
                $response['message'] = 'Datos enviados no cumplen con las reglas establecidas ';
                $response['errors'] = $validate->errors();
            }

            else
            {
                $facturaVenta = new FacturaVenta();
                $facturaVenta->user_id = $data['user_id'];
                $facturaVenta->subtotal = $data['subtotal'];
                $facturaVenta->iva = $data['iva'];
                $facturaVenta->total = $data['total'];
                $facturaVenta->save();

                $response['status'] = 'success';
                $response['code'] = 201;
                $response['data'] = $facturaVenta;
                $response['message'] = 'Factura de Venta almacenada satisfactoriamente';
            }
        }

        return response()->json($response, $response['code']);
    }

    //update modifica elemento mediante Metodo Put
    public function update(Request $request) //Implementar comprobacion de existencia de archivo json
    {
        $response = array(
            'status' => 'error',
            'code' => 406,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request->input('json', null);

        if($json)
        {
            $data = json_decode($json, true);
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required',
                'subtotal' => 'required',
                'iva' => 'required',
                'total' => 'required'
            ];

            $validate = \validator($data, $rules);

            if ($validate->fails())
            {
                $response['message'] = 'Los datos enviados son incorrectos';
                $response['errors'] = $validate->errors();
            }

            else
            {
                $id = $data['id'];
                unset($data['id']);
                unset($data['user_id']);
                unset($data['fecha_venta']);

                $updated = FacturaVenta::where('id', $id)->update($data);

                if ($updated > 0)
                {
                    $response['status'] = 'success';
                    $response['code'] = 200;
                    $response['message'] = 'Datos actualizados exitosamente';
                }
                else
                {
                    $response['code'] = 400;
                    $response['message'] = 'No se pudo actualizar los datos';
                }
            }
        }

        return response()->json($response, $response['code']);
    }

    public function destroy($id)
    {

        $response=array(
            'status'=>'error',
            'code'=>404,
            'message'=>'Falta el identificador de la Factura de Venta'
        );

        if(isset($id))
        {
            $deleted = FacturaVenta::where('id', $id) -> delete();

            if($deleted)
            {
                $response['status'] = 'success';
                $response['code'] = 200;
                $response['message'] = 'Factura de Venta eliminada correctamente';
            }

            else{
                $response['code'] = 400;
                $response['message'] = 'No se pudo eliminar la factura de venta, puede que el registro no exista';
            }
        }

        return response()->json($response, $response['code']);
    }
}
