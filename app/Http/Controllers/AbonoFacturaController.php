<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbonoFactura;


class AbonoFacturaController extends Controller
{
    
    public function _construct(){

    }

     // devuelve todos los elementos mediasnte GET
    public function index(){
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );
        $data=AbonoFactura::all();

        if(sizeof($data)>0){
            $response['status']= 'success';
            $response['code']= 200;
            $response['data'] = $data;
        }
        return response()->json($response,$response['code']);

    }
    // devuelve un elemento por su id mediante GET


    public function show($id){
        $data=AbonoFactura::find($id);
        if(is_object($data)){
  
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

    // agrega un elemento mediante POST

    public function store(Request $request){
        $json= $request->input('json',null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data=array_map('trim',$data);
            $rules=[
                'factura'=>'required',
                'fechaAbono' =>'required',
                'saldoAnterior'=>'required',
                'montoAbono'=>'required',
                'saldoActual'=>'required'

            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Error al enviar los datos',
                    'errors' => $validate->errors()
                );
            }else{
                $abono = new AbonoFactura();
                $abono->factura=$data['factura'];
                $abono->fechaAbono=$data['fechaAbono'];
                $abono->saldoAnterior=$data['saldoAnterior'];
                $abono->montoAbono=$data['montoAbono'];
                $abono->saldoActual=$data['saldoActual'];
                $abono->save();
                $response =array(
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'Datos alamacenados correctamente'
                );
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

        //modifica un elemento mediante PUT
    public function update(Request $request){
        $json = $request -> input('json',null);
        $data= json_decode($json,true);
        if(!empty($data)){
            $data = array_map('trim',$data);
            $rules=[
                'factura'=>'required',
                'fechaAbono' =>'required',
                'saldoAnterior'=>'required',
                'montoAbono'=>'required',
                'saldoActual'=>'required'

            ];
            $validate=\validator($data,$rules);
            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Error al enviar los datos',
                    'errors' => $validate->errors()
                );
            }else{
                $id=$data['id'];
                unset($data['id']);
                unset($data['created_at']);
                unset($data['updated_at']);
                $updated = AbonoFactura::where('id',$id)->update($data);
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
     //elimina un elemento mediante DELETE

    public function destroy($id){
        if(isset($id)){
            $deleted = AbonoFactura::where('id',$id)->delete();
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
