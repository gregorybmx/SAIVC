<?php

/*
Integrantes: Anthony Rugama,
            Carlos Reyes,
            Greivin Montoya,
            Kendall Fallas 
*/

namespace App\Http\Controllers;
use App\Models\Proveedores;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class ProveedorController extends Controller
{
    public function _construct()
    { 
        $this->middleware('api.auth',['except'=>['show','store']]);
    }

    public function index(){
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );

        $data=Proveedores::all();

        if(sizeof($data)>0){
            $response['status']= 'success';
            $response['code']= 200;
            $response['data'] = $data;
        }
        return response()->json($response,$response['code']);

    }

    public function show($id)
    {
        $response=array(
            'status'=>'error',
            'code'=>404,
            'message'=>'Registro no encontrado'
        );

        $data=Proveedores::find($id);

        if(is_object($data))
        {
            $response ['status'] ='success';
            $response ['code'] = 200;
            $response ['data'] = $data;
        }

        return response()->json($response,$response['code']);
    }

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
            $data = json_decode($json,true);

            if(!empty($data))
            {
                $data = array_map('trim',$data);
                $rules = [
                    'id' => 'required|unique:proveedores',
                    'agente_ventas' => 'required',
                    'nombre' => 'required',
                    'numeroTelefonico' => 'required'
                ];

                $validate=\validator($data,$rules);

                if($validate->fails())
                {
                    $response ['message'] ='Error al enviar los datos';
                    $response ['errors'] = $validate->errors();
                }

                else
                {
                    $probe = new Proveedores();
                    $probe -> id = $data['id'];
                    $probe -> agente_ventas = $data['agente_ventas'];
                    $probe -> nombre = $data['nombre'];
                    $probe -> numeroTelefonico = $data['numeroTelefonico'];
                    $probe -> save();

                    $response ['status'] = 'success';
                    $response ['code'] = 201;
                    $response ['message'] = 'Datos almacenados correctamente';
                }
            }

            else
            {
                $response ['code'] = 404;
                $response ['message'] = 'Faltan datos';
            }
        }

        return response()->json($response,$response['code']);
    }

//tengo una duda con el agente ventas----- tambien es requerido? o no se actualiza
    public function update(Request $request)
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
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required',
                'agente_ventas' => 'required',
                'nombre' => 'required',
                'numeroTelefonico' => 'required'
            ];
            $validate = \validator($data, $rules);
            if ($validate->fails())
            {
                $response ['message'] = 'Error al enviar los datos';
                $response ['errors'] = $validate->errors();
            }

            else
            {
                $produc = $data['id']; //duda si va o nelson
                unset($data['id']);

                $updated = Proveedores::where('id', $produc)->update($data);

                if ($updated > 0)
                {
                    $response ['status'] = 'success';
                    $response ['code'] = 200;
                    $response ['message'] = 'Datos actualizados correctamente';
                }

                else
                {
                    $response ['code'] = 404;
                    $response ['message'] = 'Error al actualizar los datos';
                }
            }
        }

        return response()->json($response,$response['code']);
    }

    public function destroy($id)
    {
        $response = array(
            'status' => 'error',
            'code' => 401,
            'message' => 'Faltan elementos'
        );

        if(isset($id)){
            $deleted = Proveedores::where('id',$id)->delete();
            if($deleted)
            {
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
