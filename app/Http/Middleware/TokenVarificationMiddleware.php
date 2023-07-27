<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVarificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->header('token');
        $result = JWTToken::varifyToken($token);

       

        if($result == 'Unauthorized'){
            return response()->json([
                'status'=>'Failed',
                'message'=>'Unauthorized from middleware'
            ], 401);
        }else{
            $request->headers->set('email', $result);
            return $next($request);
        }

       
    }
}