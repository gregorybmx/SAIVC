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
            'status'=>'error',
            'code'=>404,
            'data'=>'No se han agregado registros'
        );

        $data = FacturaVenta::all();//obtengo el arreglo de todos las Facturas venta

        if(sizeof($data) > 0)//compruebo si el arreglo viene vacio
        {
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $data;
        }

        return response() -> json($response,$response['code']);
    }

    //Show -> devuelve elemento buscado por id mediante el metodo Get
    public function show($id)
    {
        $response=array(
            'status'=>'error',
            'code'=>404,
            'data'=>'Recurso no encontrado'
        );

        $data = FacturaVenta::find($id);

        if(is_object($data))
        {
            $data=$data->load('detalle_factura_ventas');
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $data;
        }

        return response() -> json($response,$response['code']);
    }
}
