<?php

/*
Integrantes: Anthony Rugama,
            Carlos Reyes,
            Greivin Montoya,
            Kendall Fallas 
*/

namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class ProductoController extends Controller
{
    public function _construct()
    { 
        $this->middleware('api.auth',['except'=>['show','store']]);
    }

    public function index()
    {
        $response=array(
            'status' => 'success',
            'code' => '404',
            'data'=>'No se han agregado registros'
        );

        $data=Producto::all();

        if(sizeof($data))
        {
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

        $data=Producto::find($id);

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
                    'id' => 'required|unique:productos',
                    'descripcion' => 'required',
                    'precio_compra' => 'required',
                    'porcentaje_ganancia' => 'required',
                    'precio_venta' => 'required',
                    'cantidadMinima' => 'required',
                    'stock' => 'required'
                ];

                $validate = \validator($data, $rules);

                if ($validate->fails())
                {
                    $response ['message'] = 'Error al enviar los datos';
                    $response ['errors'] = $validate->errors();
                }

                else
                {
                    $produc = new Producto();
                    $produc->id = $data['id'];
                    $produc->descripcion = $data['descripcion'];
                    $produc->precio_compra = $data['precio_compra'];
                    $produc->porcentaje_ganancia = $data['porcentaje_ganancia'];
                    $produc->precio_venta = $data['precio_venta'];
                    $produc->cantidadMinima = $data['cantidadMinima'];
                    $produc->stock = $data['stock'];
                    $produc->save();
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
                'descripcion' => 'required',
                'precio_compra' => 'required',
                'porcentaje_ganancia' => 'required',
                'precio_venta' => 'required',
                'cantidadMinima' => 'required',
                'stock' => 'required'
            ];

            $validate = \validator($data, $rules);

            if ($validate->fails()) {
                $response ['message'] = 'Error al enviar los datos';
                $response ['errors'] = $validate->errors();
            }

            else
            {
                $produc = $data['id']; //duda si va o nelson
                unset($data['id']);

                $updated = Producto::where('id', $produc)->update($data);

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
            'code' => 404,
            'message' => 'Faltan elementos datos'
        );

        if(isset($id))
        {

            $deleted = Producto::where('id',$id)->delete();

            if($deleted)
            {
                $response ['status'] = 'succes';
                $response ['code'] = 200;
                $response ['message'] = 'Elemento eliminado correctamente';
            }

            else{
                $response ['code'] = 400;
                $response ['message'] = 'Error al eliminar los datos';
            }
        }

        return response()->json($response,$response['code']);
    }
}
