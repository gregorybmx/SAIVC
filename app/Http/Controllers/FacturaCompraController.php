<?php

/*
Integrantes: Anthony Rugama,
            Carlos Reyes,
            Greivin Montoya,
            Kendall Fallas 
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacturaCompra;
use App\Helpers\JwtAuth;

class FacturaCompraController extends Controller
{

    public function _construct()
    {
        $this->middleware('api.auth');
    }
 // devuelve todos los elementos mediasnte GET
    public function index(){
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );
        $data=FacturaCompra::all();

        if(sizeof($data)>0){
            $response['status']= 'success';
            $response['code']= 200;
            $response['data'] = $data;
        }
        return response()->json($response,$response['code']);

    }
    // devuelve un elemento por su id mediante GET
    public function show($id)
    {
        $response=array(
            'status'=>'error',
            'code'=>404,
            'data'=>'Registro no encontrado'
        );

        $data=FacturaCompra::find($id);

        if(is_object($data))
        {
            $data = $data->load('proveedor', 'abonoFactura');
            $response ['status'] = 'success';
            $response ['code'] = 200;
            $response ['data'] = $data;
        }

        return response()->json($response,$response['code']);
    }

    // agrega un elemento mediante POST
    public function store(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 406,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request->input('json',null);

        if($json)
        {
            $data = json_decode($json, true);

            if (!empty($data))
            {
                $data = array_map('trim', $data);

                $rules = [
                    'id' => 'required',
                    'proveedor_id' => 'required',
                    'fecha_Compra' => 'required',
                    'fecha_Vencimiento' => 'required',
                    'monto_Total' => 'required',
                ];

                $validate = \validator($data, $rules);

                if ($validate->fails()) {
                    $response ['message'] = 'Error al eviar los datos';
                    $response ['errors'] = $validate->errors();
                }

                else
                {
                    $compra = new FacturaCompra();
                    $compra->id = $data['id'];
                    $compra->proveedor_id = $data['proveedor_id'];
                    $compra->fecha_Compra = $data['fecha_Compra'];
                    $compra->fecha_Vencimiento = $data['fecha_Vencimiento'];
                    $compra->monto_Total = $data['monto_Total'];
                    $compra->save();
                    $response ['status'] = 'success';
                    $response ['code'] = 200;
                    $response ['message'] = 'Datos almacenados correctamente';
                }
            }

            else
            {
                $response ['code'] = 400;
                $response ['message'] = 'Faltan datos';
            }
        }

        return response()->json($response,$response['code']);
    }

    //elimina un elemento mediante DELETE
    public function destroy($id)
    {
        $response = array(
            'status' => 'error',
            'code' => 401,
            'message' => 'Faltan elementos'
        );

        if(isset($id))
        {
            $deleted = FacturaCompra::where('id',$id)->delete();
            if($deleted){
                $response ['status'] = 'succes';
                $response ['code'] = 200;
                $response ['message'] = 'Elemento eliminado correctamente';
            }

            else
            {
                $response ['code'] = 400;
                $response ['message'] = 'Error al eliminar el elemento';
            }
        }

        return response()->json($response,$response['code']);
    }
}
