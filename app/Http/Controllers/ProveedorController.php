<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function _construct(){ }

    public function index(){
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );

        $data=Provedores::all();

        if(sizeof($data)>0){
            $response['status']= 'success';
            $response['code']= 200;
            $response['data'] = $data;
        }
        return response()->json($response,$response['code']);

    }

    public function show($idProvedor){
        $data=Provedores::find($idProvedor);
        if(is_object($data)){
            $data=$data->load('Provedores');
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
                'idProvedor' => 'required',
                'agente_ventas' => 'required',
                'nombre' => 'required',
                'numeroTelefonico' => 'required' 
            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' =>406,
                    'message' =>'Error al eviar los datos',
                    'errors' =>$validate->errors()

                );
            }else{
                $probe = new Proveedores();
                $probe -> idProvedor = $data['idProvedor'];
                $probe -> agente_ventas = $data['agente_ventas'];
                $probe -> nombre = $data['nombre'];
                $probe -> numeroTelefonico = $data['numeroTelefonico'];
                $probe -> save();
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
                'idProvedor' => 'required',
                'agente_ventas' => 'required',
                'nombre' => 'required',
                'numeroTelefonico' => 'required'        
            ];
            $validate=\validator($data,$rules);
            if($validate -> fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Error al eviar los datos',
                    'errors' =>$validate->errors()
                );

            }else{
                $probe = $data['idProvedor'];
                unset($data['idProvedor']);
                $updated = Producto::where('idProvedor',$probe)->update($data);
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
            $deleted = Provedores::where('idProvedor',$id)->delete();
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
