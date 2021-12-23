<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();

            $token = $user->createToken('myapptoken')->plainTextToken;

            $coockie = Cookie('jwt', $token, 1440);

            return response([
                'message' => 'Success',
                'token'=> $token
            ])->withCookie($coockie);        
        }
        else{
            return response(['message' => 'invalid Credentials'], Response::HTTP_UNAUTHORIZED);
        }

    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'name' => ['required'], 
            'email' => ['required'], 
            'password' => ['required'], 
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return 'User Saved Successfully';
    }
    // public function user()
    // {
    //     if (Auth::user()) {
    //         return Auth::user();
    //     }
    //     else {
    //         return Response::HTTP_UNAUTHORIZED;
    //     }
    // }

    public function logout()
    {
        $coockie = Cookie::forget('jwt');
        return response(['message' => 'Logged Out'])->withCookie($coockie);
    }
}
