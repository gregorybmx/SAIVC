<?php

namespace App\Http\Controllers;

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
        $data=AgenteVendedor::find($id);
        if(is_object($data)){
            $data=$data->load('AgenteVendedor');
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Registro no encontrado'

            );
        }
        return response()->json($response,$response['code']);

    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data = array_map('trim',$data);
            $rules = [
                'id' => 'required',
                'nombre' => 'required',
                'apellidos' => 'required',
                'telefono' => 'required' 
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' =>406,
                    'message' =>'Error al enviar los datos',
                    'errors' =>$validate->errors()

                );
            }else{
                $agentVen = new AgenteVendedor();
                $agentVen -> id = $data['id'];
                $agentVen -> nombre = $data['nombre'];
                $agentVen -> apellidos = $data['apellidos'];
                $agentVen -> telefono = $data['telefono'];
                $agentVen -> save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos almacenados correctamente'
                );
            }
        }else{
            $response = array(
                'status'=>'error',
                'code' => 400,
                'message' => 'Faltan datos'
            );
        }
        return response()->json($response,$response['code']); 
    }
//tengo una duda con el agente ventas----- tambien es requerido? o no se actualiza
    public function update(Request $request){
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data =array_map('trim',$data);
            $rules = [
                'id' => 'required',
                'nombre' => 'required',
                'apellidos' => 'required',
                'telefono' => 'required'        
            ];
            $validate=\validator($data,$rules);
            if($validate -> fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Error al enviar los datos',
                    'errors' =>$validate->errors()
                );

            }else{
                $agentVen = $data['id'];//duda si va o nelson
                unset($data['id']);
                unset($data['nombre']);
                unset($data['apellidos']);
                unset($data['telefono']);
                $updated = AgenteVendedor::where('id',$agentVen)->update($data);
                if($updated > 0){
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos actualizados correctamente'
                    );
                }else{
                    $response = array( 
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Error al actualizar los datos'
                    );
                }

            }
        }else{
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Faltan Datos'
            );
        }
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = AgenteVendedor::where('id',$id)->delete();
            if($deleted){
                $response = array(
                    'status' => 'succes',
                    'code' =>200,
                    'message' => 'Elemento eliminado correctamente'

                );
               
            }else{
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Error al eliminar el elemento'

                );
            }
        }else{
            $response = array(
                'status' => 'error',
                'code' => 401,
                'message' => 'Faltan elementos'
            );
        }
        return response()->json($response,$response['code']);

    }
}
