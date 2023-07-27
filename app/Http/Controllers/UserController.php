<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function userRegistration(Request $request){
        try{
            User::create($request->input());

            return response()->json([
                'status'=>'Success',
                'message'=>'You are has been registered'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status'=>'Failed',
                'message'=>'Unauthorized'
            ]);
        }
        

    }
    function userLogin(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $count = User::where('email', '=', $email)
        ->where('password', '=', $password)
        ->count();

        if($count == 1){
            $token = JWTToken::createToken($email);

            return response()->json([
                'status'=>'Success',
                'message'=>'You have been logged in Successfully',
                'token'=>$token
            ]);
        }else{
            return response()->json([
                'status'=>'Failed',
                'message'=>'Unauthorized',
            ]);
        }
    }


    function sendOTPToEmail(Request $request){
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();

        if($count == 1){
            // Send OTP to user email
            Mail::to($email)->send(new OTPMail($otp));

            // OTP code set to the user table
            User::where('email', '=', $email)->update(['otp'=>$otp]);

            return response()->json([
                'status'=>'Success',
                'message'=>'4 Digit OTP code has been sent',
            ]);


        }else{
            return response()->json([
                'status'=>'Failed',
                'message'=>'Unauthorized from UserController',
            ]);
        }
    }
    function OTPVarification(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');

        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();

        if($count == 1){
            // OTP update to Database
            User::where('email', '=', $email)->update(['otp'=>'0']);

            // Create new Token
            $token = JWTToken::createTokenForPassword($email);

            return response()->json([
                'status'=>'Success',
                'message'=>'OTP has been varified Successfully',
                'token'=>$token
            ]);

        }else{
            return response()->json([
                'status'=>'Failed',
                'message'=>'Unauthorized to varified OTP',
            ]);
        }
    }
    function resetPassword(Request $request){

        try{
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', '=', $email)->update(['password'=>$password]);

            return response()->json([
                'status'=>'Success',
                'message'=>'Password has been reset Successfully'
            ]);

        }catch(Exception $e){
            return response()->json([
                'status'=>'Fail',
                'message'=>'Something went wrong'
            ]);
        }
    }
    function profileUpdate(){

    }
}


