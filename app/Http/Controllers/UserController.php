<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        try {
            $user = $request->user();
            if ($user === null) {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'status' => false,
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ], 500);
        }
        try{
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => false,
                    'data' => null
                ], 401);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'User data',
            'status' => true,
            'data' => $request->user(),
        ], 200);
    }

    protected function unauthenticated($request, array $guards)
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    public function searchUser (Request $request){
        try{
            $user = User::where('id', $request->id)->first();
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ], 500);
        }
        if ($user === null) {
            return response()->json([
                'message' => 'Usuario no encontrado',
                'status' => false,
            ], 404);
        }
        return response()->json([
            'message' => 'Usuario encontrado',
            'status' => true,
            'data' => $user
        ], 200);
    }

    public function updateUser(Request $request, $id){
        $delete = $request->input('delete');
        if($request->all() == [] && $delete !== 'true'){
            return response()->json([
                'message' => 'No data provided for update',
                'status' => false
            ], 400);
        }
        try{
            $user = User::find($id);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ], 500);
        }
        if(!$user){
            return response()->json([
                'message' => 'Usuario no encontrado',
                'status' => false
            ], 404);
        }
        if ($delete === 'true') {
            try{
                $user->delete();
            }catch(\Exception $e){
                return response()->json([
                    'message' => 'Error al acceder a la base de datos',
                    'data' => $e->getMessage(),
                ], 500);
            }
            return response()->json([
                'message' => 'Usuario eliminado',
                'status' => true
            ], 200);
        }
        try{
            $user->update($request->all());
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'Usuario actualizado',
            'status' => true,
            'data' => $user
        ], 200);
    }
}
