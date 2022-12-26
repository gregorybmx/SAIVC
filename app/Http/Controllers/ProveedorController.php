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
        $this->middleware('api.auth');
    }

    public function index(){
        $response=array(
            'status' => 'error',
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

        if(isset($id))
        {
            $data=Proveedores::find($id);

            if(is_object($data))
            {
                $response ['status'] ='success';
                $response ['code'] = 200;
                $response ['data'] = $data;
            }
        }
        else
        {   
            $response ['code'] = 409;
            $response ['message'] = 'No se ha ingresado el Id deseado';
            
        }

        return response()->json($response,$response['code']);
    }

    public function store(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 409,
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
                    'nombre_Proveedor' => 'required',
                    'nombre_Agente' => 'required',
                    'apellidos_Agente' => 'required',
                    'telefono_Proveedor' => 'required',
                    'telefono_Agente' => 'required'
                ];

                $validate=\validator($data,$rules);

                if(!($validate->fails()))
                {
                    $probe = new Proveedores();
                    $probe -> id = $data['id'];
                    $probe -> nombre_Proveedor = $data['nombre_Proveedor'];
                    $probe -> nombre_Agente = $data['nombre_Agente'];
                    $probe -> apellidos_Agente = $data['apellidos_Agente'];
                    $probe -> telefono_Proveedor = $data['telefono_Proveedor'];
                    $probe -> telefono_Agente = $data['telefono_Agente'];
                    $probe -> save();

                    $response ['status'] = 'success';
                    $response ['code'] = 200;
                    $response ['message'] = 'Datos almacenados correctamente';
                }
                else
                {
                    $response ['errors'] = $validate->errors();
                }
            }
        }

        return response()->json($response,$response['code']);
    }

//tengo una duda con el agente ventas----- tambien es requerido? o no se actualiza
    public function update(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 409,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request->input('json',null);

        if($json)
        {
            $data = json_decode($json, true);
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required',
                'nombre_Proveedor' => 'required',
                'nombre_Agente' => 'required',
                'apellidos_Agente' => 'required',
                'telefono_Proveedor' => 'required',
                'telefono_Agente' => 'required'
            ];
            $validate = \validator($data, $rules);
            if ($validate->fails())
            {
                $response ['message'] = 'Error al enviar los datos';
                $response ['errors'] = $validate->errors();
            }

            else
            {
                $produc = $data['id']; 
                unset($data['id']);
                unset($data['created_at']);
                unset($data['updated_at']);

                $updated = Proveedores::where('id', $produc)->update($data);

                if ($updated > 0)
                {
                    $response ['status'] = 'success';
                    $response ['code'] = 200;
                    $response ['message'] = 'Datos actualizados correctamente';
                }

                else
                {
                    $response ['code'] = 400;
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
            'code' => 409,
            'message' => 'Faltan elementos'
        );

        if(isset($id)){
            $deleted = Proveedores::where('id',$id)->delete();
            if($deleted)
            {
                $response ['status'] = 'success';
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
