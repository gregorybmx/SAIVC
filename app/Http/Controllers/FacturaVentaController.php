<?php

namespace App\Http\Controllers;

use App\Models\FacturaVenta;
use Illuminate\Http\Request;

class FacturaVentaController extends Controller
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
            //$facturaVenta = $facturaVenta -> load('detalle_factura_ventas');
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $facturaVenta;
        }

        return response()->json($response, $response['code']);
    }

    //store ->agrega o guarda elemento mediante metodo Post
    public function store(Request $request) //Implementar comprobacion de existencia de archivo json
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        $data = array_map('trim', $data);

        $rules = [
            'vendedor' => 'required',
            'fecha_venta' => 'required',
            'subtotal' => 'required',
            'iva' => 'required',
            'total' => 'required'
        ];

        $validate = \validator($data, $rules);

        if ($validate->fails())
        {
            $response = array(
                'status' => 'error',
                'code' => 406,
                'message' => 'Datos enviados no cumplen con las reglas establecidas ',
                'errors' => $validate->errors()
            );
        }

        else
        {
            $facturaVenta = new FacturaVenta();
            $facturaVenta->vendedor = $data['vendedor'];
            $facturaVenta->fecha_venta = $data['fecha_venta'];
            $facturaVenta->subtotal = $data['subtotal'];
            $facturaVenta->iva = $data['iva'];
            $facturaVenta->total = $data['total'];
            $facturaVenta->save();

            $response = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Factura de Venta almacenada satisfactoriamente'
            );
        }

        return response()->json($response, $response['code']);
    }

    //update modifica elemento mediante Metodo Put
    public function update(Request $request) //Implementar comprobacion de existencia de archivo json
    {
        $json = $request->input('json', null);
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
            $response = array(
                'status' => 'error',
                'code' => 406,
                'message' => 'Los datos enviados son incorrectos',
                'errors' => $validate->errors()
            );
        }

        else
        {
            $id = $data['id'];
            unset($data['id']);
            unset($data['vendedor']);
            unset($data['fecha_venta']);

            $updated = FacturaVenta::where('id', $id)->update($data);

            if ($updated > 0)
            {
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos actualizados exitosamente'
                );
            }

            else
            {
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No se pudo actualizar los datos'
                );
            }
        }

        return response()->json($response, $response['code']);
    }

    public function destroy($id)
    {
        if(isset($id))
        {
            $deleted = FacturaVenta::where('id', $id) -> delete();

            if($deleted)
            {
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Factura de Venta eliminada correctamente'
                );
            }

            else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar la factura de venta, puede que el registro no exista'
                );
            }
        }

        else
        {
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Falta el identificador de la Factura de Venta'
            );
        }
        return response()->json($response, $response['code']);
    }
}
