<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function _construct(){ }

    public function index(){
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );

        $data=Producto::all();

        if(sizeof($data)>0){
            $response['status']= 'success';
            $response['code']= 200;
            $response['data'] = $data;
        }
        return response()->json($response,$response['code']);

    }

    public function show($codigo){
        $data=Producto::find($codigo);
        if(is_object($data)){
            $data=$data->load('Producto');
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
                'codigo' => 'required',
                'descripcion' => 'required',
                'precio_compra' => 'required',
                'porcentaje_ganancia' => 'required',         
                'precio_venta'=>'required',
                'cantidadMinima'=>'required',
                'stock'=>'required'
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
                $produc = new Producto();
                $produc -> codigo = $data['codigo'];
                $produc -> description = $data['descripcion'];
                $produc -> precio_compra = $data['precio_compra'];
                $produc -> porcentaje_ganancia = $data['porcentaje_ganancia'];
                $produc -> precio_venta = $data['precio_venta'];
                $produc -> cantidadMinima = $data['cantidadMinima'];
                $produc -> stock = $data['stock'];
                $produc -> save();
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
                'codigo' => 'required',
                'descripcion' => 'required',
                'precio_compra' => 'required',
                'porcentaje_ganancia' => 'required',         
                'precio_venta'=>'required',
                'cantidadMinima'=>'required',
                'stock'=>'required'
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
                $produc = $data['codigo'];
                unset($data['codigo']);
                $updated = Producto::where('codigo',$produc)->update($data);
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
            $deleted = Producto::where('codigo',$id)->delete();
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
