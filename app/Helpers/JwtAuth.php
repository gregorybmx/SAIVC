<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use App\Models\User;

class JwtAuth{
    private $key;
    function __construct(){
        $this->key = 'jujasjasjasjijijas';
    }
    public function getToken($email,$password){
        $user=User::where(['email'=>$email,'password'=>hash('shar256',$password)])->first();
        if(is_object($user)){
            $token=array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'role' => $user->role,
                'iat' => time(),
                'exp' => time()+(120)
            );
            $data=JWT::encode($token,$this->key,'HS256');
        } else{
            $data=array(
                'status' => 'error',
                'code' => 401,
                'message' => 'Datos erroneos'
            );
        }
        return $data;
    }
    public function checkToken($jwt,$getIdentify=false){
        $auth=false;
        if(isset($jwt)){
            try{
                $decoded=JWT::decode($jwt,new Key($this->key,'HS256'));
            }catch(\DomainException $ex){
                $auth=false;
             }catch(\UnexpectedValueException $ex){
                $auth=false;
            }catch(\ExpiredException $ex){
                $auth=false;
            }

            if(!empty($decoded)&&is_object($decoded)&&isset($decoded->sub)){
                $auth=true;
            }
            if($getIdentity&&$auth){
                return $decoded;
            }

        }
        return $auth;
    }
}