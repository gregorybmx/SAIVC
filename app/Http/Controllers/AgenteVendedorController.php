<?php

namespace App\Http\Controllers;
use App\Models\AgenteVendedor;
use Illuminate\Http\Request;

class AgenteVendedorController extends Controller
{
    public function _construct(){ }

    public function index(){
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );

        $data=AgenteVendedor::all();

        if(sizeof($data)>0){
            $response['status']= 'success';
            $response['code']= 200;
            $response['data'] = $data;
        }
        return response()->json($response,$response['code']);

    }

    public function show($id){

        $response=array(
            'status'=>'error',
            'code'=>404,
            'message'=>'Registro no encontrado'
        );

        $data=AgenteVendedor::find($id);

        if(is_object($data))
        {
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $data;
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
            $data = json_decode($json, true);

            if (!empty($data))
            {
                $data = array_map('trim', $data);
                $rules = [
                    'id' => 'required|unique:agentes_vendedores',
                    'nombre' => 'required',
                    'apellidos' => 'required',
                    'telefono' => 'required'
                ];

                $validate = \validator($data, $rules);

                if ($validate->fails())
                {
                    $response ['message'] = 'Error al enviar los datos';
                    $response ['errors'] = $validate->errors();
                }

                else
                {
                    $agentVen = new AgenteVendedor();
                    $agentVen->id = $data['id'];
                    $agentVen->nombre = $data['nombre'];
                    $agentVen->apellidos = $data['apellidos'];
                    $agentVen->telefono = $data['telefono'];
                    $agentVen->save();

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

            if (!empty($data)) {
                $data = array_map('trim', $data);
                $rules = [
                    'id' => 'required',
                    'nombre' => 'required',
                    'apellidos' => 'required',
                    'telefono' => 'required'
                ];

                $validate = \validator($data, $rules);

                if ($validate->fails())
                {
                    $response ['message'] = 'Error al enviar los datos';
                    $response ['errors'] = $validate->errors();
                }

                else
                {
                    $agentVen = $data['id']; //duda si va o nelson
                    unset($data['id']);

                    $updated = AgenteVendedor::where('id', $agentVen)->update($data);

                    if ($updated > 0) {
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

            else
            {
                $response ['code'] = 400;
                $response ['message'] = 'Faltan Datos';
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
            $deleted = AgenteVendedor::where('id',$id)->delete();
            if($deleted){
                $response ['status'] = 'succes';
                $response ['code'] = 200;
                $response ['message'] = 'Elemento eliminado correctamente';

            }else{
                $response ['code'] = 400;
                $response ['message'] = 'Error al eliminar el elemento';
            }
        }

        return response()->json($response,$response['code']);

    }
}
