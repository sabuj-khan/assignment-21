<?php 
namespace App\Helper;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken {
   public static function createToken($userEmail){
        $key = env('JWT_KEY');
        $payload = [
            'iss'=>'multiuser-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail
        ];

        return JWT::encode($payload, $key, 'HS256');
    }


    public static function createTokenForPassword($userEmail){
        $key = env('JWT_KEY');
        $payload = [
            'iss'=>'multiuser-token',
            'iat'=>time(),
            'exp'=>time()+60*30,
            'userEmail'=>$userEmail
        ];

        return JWT::encode($payload, $key, 'HS256');
    }




    public static function varifyToken($token){
        try{
            
        if($token == null){
            return 'Unauthorized';
        }else{
            $key = env('JWT_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded->userEmail;
        }

        }catch(Exception $e){
            return 'Unauthorized';
        }
    }
}