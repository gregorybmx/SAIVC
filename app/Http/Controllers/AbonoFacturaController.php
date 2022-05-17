<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbonoFacturaController extends Controller
{
    
    public function _construct(){

    }

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

    public function show($id){
        $data=AbonoFactura::find($id);
        if(is_object($data)){
            $data=$data->load('abono_factura');
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
}
