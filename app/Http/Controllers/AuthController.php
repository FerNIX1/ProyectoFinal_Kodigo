<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if(Auth::check()){
            return response()->json([
                'message' => 'User already logged in',
                'status' => false
            ], 401);
        }
        try{
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response()->json([
                    'message' => 'User already exists',
                    'status' => false
                ], 401);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ], 500);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users|alpha_dash',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|string|max:255',
            'nombre' => 'sometimes|string|max:255',
            'apellido' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'dui' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'zipcode' => 'sometimes|string|max:10',
            'paymethod' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        /* Create user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();*/

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password), 'role' => $request->role ?? 'user']
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'status' => true
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        return $this->responseWithToken($token);
    }

    public function responseWithToken($token)
    {
        Log::info('Token: ' . $token);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'status' => true,
            'message' => 'Successfully logged in'
        ], 200);
    }

    public function logout()
    {
        try{
            auth('api')->logout();
        } catch(\Exception $e){
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'status' => false,
                'data' => $e->getMessage()
            ], 500);
        }


        return response()->json([
            'message' => 'Successfully logged out',
            'status' => true
        ], 200);
    }

    public function showLoginForm()
    {
        return response(json_encode([
            'message' => 'A login is required to access this resource',
            'status' => false,
            'data' => null
        ]), 403)->header('Content-Type', 'application/json');
    }


}
