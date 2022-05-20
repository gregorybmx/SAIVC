<?php

namespace App\Http\Controllers;

use App\Models\DetalleFacturasVenta;
use Illuminate\Http\Request;

class DetalleFacturaVentaController extends Controller
{
    public function __construct()
    {
        //Inyectar Middleware
    }

    //Index -> Devuelve todos los elementos mediante el metodo Get
    public function index()
    {
        $response = array(
            'status' => 'error',
            'code' => 404,
            'data' => 'No se han agregado registros'
        );

        $data = DetalleFacturasVenta::all();

        if(sizeof($data) > 0)
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

        $detalleFactura = DetalleFacturasVenta::find($id);

        if(is_object($detalleFactura))
        {
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $detalleFactura;
        }

        return response()->json($response, $response['code']);
    }

    //store ->agrega o guarda elemento mediante metodo Post
    public  function store(Request $request)//Implementar comprobacion de existencia de archivo json
    {
        $response = array(
            'status' => 'error',
            'code' => 406,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request -> input('json',null);
        if($json)
        {
            $data = json_decode($json, true);
            $data = array_map('trim', $data);

            $rules = [
                'factura' => 'required',
                'producto' => 'required',
                'cantidad' => 'required',
                'precio_unitario' => 'required',
                'precio_total' => 'required'
            ];

            $validate = \validator($data, $rules);

            if ($validate->fails())
            {
                $response['message'] = 'Datos enviados no cumplen con las reglas establecidas ';
                $response['errors'] = $validate->errors();
            }

            else
            {
                $detalleFactura = new DetalleFacturasVenta();
                $detalleFactura->factura = $data['factura'];
                $detalleFactura->producto = $data['producto'];
                $detalleFactura->cantidad = $data['cantidad'];
                $detalleFactura->precio_unitario = $data['precio_unitario'];
                $detalleFactura->precio_total = $data['precio_total'];
                $detalleFactura->save();


                $response['status'] = 'success';
                $response['code'] = 201;
                $response['message'] = 'Detalle de Factura de Venta almacenado satisfactoriamente';
            }
        }

        return response()->json($response, $response['code']);
    }

    //update modifica elemento mediante Metodo Put
    public function update(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 406,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request -> input('json',null);

        if($json)
        {
            $data = json_decode($json, true);
            $data = array_map('trim', $data);


            $rules = [
                'id' => 'required',
                'producto' => 'required',
                'cantidad' => 'required',
                'precio_unitario' => 'required',
                'precio_total' => 'required'
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
                unset($data['factura']);

                $updated = DetalleFacturasVenta::where('id', $id)->update($data);

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
        if(isset($id))
        {
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Falta el identificador del Detalle de Factura'
            );

            $deleted = DetalleFacturasVenta::where('id', $id) -> delete();

            if($deleted)
            {
                $response['status'] = 'success';
                $response['code'] =200;
                $response['message'] = 'Detalle Factura de Venta eliminada correctamente';
            }

            else{
                $response['code'] = 400;
                $response['message'] = 'No se pudo eliminar el Detalle de Factura, puede que el registro no exista';
            }
        }

        return response()->json($response, $response['code']);
    }
}
