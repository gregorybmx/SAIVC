<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(){

    }
    public function __invoke(){

    }
    public function index(){
        $response = array(
            'status' => 'error',
            'code' => '404',
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
    
    public function store(Request $request){

        $json = $request->input('json', null);
        $data = json_decode($json, true);
        var_dump($data);
        $data = array_map('trim', $data);
        $rules = [
            'id' => 'required|alpha',
            'name' => 'required',
            'last_name' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
        $validate = \validator($data,$rules);
        if($validate->fails()) {
            $response = array(
                'status' => 'error',
                'code' => 404,
                'menssage' => 'El usuario no se ha creado',
                'errors' => $validate->errors(),
            );
        } else{
            $pwd = password_hash($data->password, PASSWORD_BCRYPT,['cost'=> 4]);
            $user = new User();
            $user->id= $data['id'];
            $user->name= $data['name'];
            $user->last_name= $data['last_name'];
            $user->name= $data['role'];
            $user->email= $data['email'];
            $user->password= $pwd;
            $user->role = 'ROLE_ADMIN';
            $user->save();
            $response = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El usuario se ha creado',
                'username' => $user
            );
        }
        return response()->json($response, $response['code']);
    }

    public function update(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules = [
            'id' => 'required|alpha',
            'name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'role' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'password' => 'required|alpha',
        ];
        $valid=\validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status' => 'error',
                'code' => 406,
                'message' => 'Datos incorrectos',
                'errors'=>$valid->errors()
            );
        } else{
            $email=$data['email'];
            unset($data['id']);
            unset($data['created_at']);
            unset($data['remember_token']);
            $updated=User::where('email',$email)->update($data);
            if($updated>0){
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos actualizados'
                );
            } else{
                $response=array(
                    'status' =>'error',
                    'code' => 400,
                    'message' => 'Error al actualizar los datos'
                );
            }
        }
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted=User::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Usuario eliminado'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar'
                );
            }
        }else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Falta el identificador'
            );
        }
        return response()->json($response,$response['code']);
    }

    public function uploadImage(Request $request){
        $image=$request->file('file0');
        $valid=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,png'
        ]);
        if(!$image||$valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Error al subir el archivo',
                'errors'=>$valid->errors()
            );
        }else{
            $filename=time().$image->getClientOriginalName();
            \Storage::disk('users')->put($filename,\File::get($image));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Imagen guardada correctamente',
                'image_name'=>$filename
            );
        }
        return response()->json($response,$response['code']);
    }

    public function getImage($filename){
        $exist=\Storage::disk('users')->exists($filename);
        if($exist){
            $file=\Storage::disk('users')->get($filename);
            return new Response($file,200);
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Imagen no existe'
            );
            return response()->json($response,404);
        }
    }

    public function login(Request $request){
       
    }
}