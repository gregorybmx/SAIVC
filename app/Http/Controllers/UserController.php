<?php

/*
Integrantes: Anthony Rugama,
            Carlos Reyes,
            Greivin Montoya,
            Kendall Fallas
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('api.auth',['except'=>['login']]);
    }

    public function __invoke(){

    }
    public function index(){
        $response = array(
            'status' => 'error',
            'code' => 404,
            'data' => 'No se han agregado registros'
        );

        $data = User::all();
        if(sizeof($data)>0){
            $response['status'] = 'success';
            $response['code'] = 200;
            $response['data'] = $data;
        }
        return response()->json($response, $response['code']);
    }

    public function show($id)
    {
        $response = array(
            'status' => 'error',
            'code' => 404,
            'data' => 'Recurso no encontrado'
        );

        if(isset($id))
        {
            $user = User::find($id);

            if(is_object($user))
            {
                $user = $user->load('facturaVenta');
                $response['status'] = 'success';
                $response['code'] = 200;
                $response['data'] = $user;
            }
        }

        else
        {
            $response['code'] = 400;
            $response['message'] = 'No se ha ingresado el Id deseado';
        }

        return response() -> json($response, $response['code']);
    }

    public function store(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 400,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json = $request->input('json', null);

        if($json)
        {
            $data = json_decode($json, true);
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required',
                'name' => 'required|alpha',
                'last_name' => 'required',
                'role' => 'required',
                'state' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'required',
                'address' => 'required',
                'password' => 'required'
            ];
            $validate = \validator($data,$rules);

            if($validate->fails())
            {
                $response['code'] = 404;
                $response['message'] = 'El usuario no se ha creado';
                $response['errors'] = $validate->errors();
            }

            else
            {
                $user = new User();
                $user->id= $data['id'];
                $user->name= $data['name'];
                $user->last_name= $data['last_name'];
                $user->email= $data['email'];
                $user->state= $data['state'];
                $user->password= hash('sha256',$data['password']);;
                $user->role = $data['role'];
                $user->phone = $data['phone'];
                $user->address = $data['address'];
                $user->save();

                $response['status'] = 'success';
                $response['code'] = 200;
                $response['message'] = 'El usuario se ha creado';
                $response['username'] = $user;
            }
        }

        return response()->json($response, $response['code']);
    }

    public function update(Request $request)
    {
        $response = array(
            'status' => 'error',
            'code' => 409,
            'message' => 'No se ha enviado el archivo con la informacion necesaria'
        );

        $json=$request->input('json',null);

        if($json)
        {
            $data=json_decode($json,true);
            $data=array_map('trim',$data);

            $rules = [
                'id' => 'required',
                'name' => 'required|alpha',
                'last_name' => 'required',
                'role' => 'required',
                'state' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'required',
                'address' => 'required',
                'password' => 'required'
            ];

            $valid=\validator($data,$rules);

            if(!($valid->fails()))
            {
                $email = $data['email'];

                unset($data['id']);
                unset($data['created_at']);
                unset($data['updated_at']);
                unset($data['remember_token']);

                $data['password']= hash('sha256',$data['password']);

                $updated=User::where('email',$email)->update($data);

                if($updated>0)
                {
                    $response['status'] = 'success';
                    $response['code'] = 200;
                    $response['message'] = 'Datos actualizados';
                }

                else
                {
                    $response['code'] = 400;
                    $response['message'] = 'Error al actualizar los datos';
                }
            }
            else
            {
                $response['errors'] = $valid->errors();
            }
        }

        return response()->json($response,$response['code']);
    }

    public function destroy($id)
    {
        $response=array(
            'status'=>'error',
            'code'=>400,
            'message'=>'Falta el identificador'
        );

        if(isset($id)){

            $deleted=User::where('id',$id)->delete();

            if($deleted)
            {
                $response['status'] = 'success';
                $response['code'] = 200;
                $response['message'] = 'Usuario eliminado';
            }

            else
            {
                $response['message'] = 'No se pudo eliminar';
            }
        }

        return response()->json($response,$response['code']);
    }

    public function login(Request $request){
        $jwtAuth=new JwtAuth();
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=['email'=>'required|email','password'=>'required'];
        $valid=\validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>409,
                'message'=>'Los datos enviados son incorrectos',
                'errors'=>$valid->errors()
            );
            return response()->json($response,406);
        }else{
            $response=$jwtAuth->getToken($data['email'],$data['password']);
            return response()->json($response);
        }
    }

    public function getIdentity(Request $request){
        $jwtAuth=new JwtAuth();
        $token=$request->header('token');

        if(isset($token))
        {
            $response=$jwtAuth->checkToken($token,true);
        }

        else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'token no encontrado'
            );
        }
        return response()->json($response);
    }
}
