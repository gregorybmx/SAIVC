<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacturaCompraController extends Controller
{
    
    public function _construct(){

    }

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
    
    public function show($numero_factura){
        $data=FacturaCompra::find($numero_factura);
        if(is_object($data)){
            $data=$data->load('FacturaCompra');
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

    public function strore(Request $request){
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data = array_map('trim',$data);
            $rules = [
                'numero_factura' => 'required',
                'proveedor' => 'required',
                'fecha_compra' => 'required',
                'fecha_vencimiento' => 'required',         
                'monto_total'=>'required',
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
                $compra = new FacturaCompra();
                $compra -> numero_factura = $data['numero_factura'];
                $compra -> proveedor = $data['proveedor'];
                $compra -> fecha_compra = $data['fecha_compra'];
                $compra -> fecha_vencimiento = $data['fecha_vencimiento'];
                $compra -> monto_total = $data['monto_total'];
                $compra -> save();
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

    public function update(Request $request){
        $json = $request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data =array_map('trim',$data);
            $rules = [
                'proveedor' => 'required',
                'fecha_compra' => 'required',
                'fecha_vencimiento'=> 'required',
                'monto_total' => 'required',
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
                $numFactura = $data['numero_factura'];
                unset($data['numero_factura']);
                $updated = FacturaCompra::where('numero_factura',$numFactura)->update($data);
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
            $deleted = FacturaCompra::where('numero_factura',$id)->delete();
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
