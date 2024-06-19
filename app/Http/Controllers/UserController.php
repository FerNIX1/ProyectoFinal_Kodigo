<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function show(Request $request)
{
    try {
        $user = $request->user();
        if ($user === null) {
            return response(json_encode([
                'message' => 'Unauthenticated',
                'status' => false,
            ]), 401)->header('Content-Type', 'application/json');
        }
    } catch (\Exception $e) {
        return response(json_encode([
            'message' => 'Error al acceder a la base de datos',
            'data' => $e->getMessage(),
        ]), 500)->header('Content-Type', 'application/json');
    }

    return response(json_encode([
        'message' => 'User data',
        'status' => true,
        'data' => $user,
    ]), 200)->header('Content-Type', 'application/json');
}

    protected function unauthenticated($request, array $guards)
    {
        return response(json_encode(['message' => 'Unauthenticated.']), 401)->header('Content-Type', 'application/json');
    }

    public function searchUser (Request $request){
        try{
            $user = User::where('id', $request->id)->first();
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        if ($user === null) {
            return response(json_encode([
                'message' => 'Usuario no encontrado',
                'status' => false,
            ]), 404)->header('Content-Type', 'application/json');
        }
        return response(json_encode([
            'message' => 'Usuario encontrado',
            'status' => true,
            'data' => $user
        ]), 200)->header('Content-Type', 'application/json');
    }

    public function updateUser(Request $request, $id){
        $authUser = $request->user();
        if ($authUser === null) {
            return response(json_encode([
                'message' => 'Unauthenticated',
                'status' => false
            ]), 401)->header('Content-Type', 'application/json');
        }
        if ($authUser->id != $id && $authUser->role != 'admin') {
            return response(json_encode([
                'message' => 'Unauthorized',
                'status' => false
            ]), 403)->header('Content-Type', 'application/json');
        }
        $delete = $request->input('delete');
        if($request->all() == [] && $delete !== 'true'){
            return response(json_encode([
                'message' => 'No data provided for update',
                'status' => false
            ]), 400)->header('Content-Type', 'application/json');
        }
        try{
            $user = User::find($id);
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        if(!$user){
            return response(json_encode([
                'message' => 'Usuario no encontrado',
                'status' => false
            ]), 404)->header('Content-Type', 'application/json');
        }
        
        if ($delete === 'true') {
            try{
                $user->delete();
            }catch(\Exception $e){
                return response(json_encode([
                    'message' => 'Error al acceder a la base de datos',
                    'data' => $e->getMessage(),
                ]), 500)->header('Content-Type', 'application/json');
            }
            return response(json_encode([
                'message' => 'Usuario eliminado',
                'status' => true
            ]), 200)->header('Content-Type', 'application/json');
        }
        if ($request->has('role') && $authUser->role != 'admin') {
            return response(json_encode([
                'message' => 'Unauthorized to update role',
                'status' => false
            ]), 403)->header('Content-Type', 'application/json');
        }
        try{
            $user->update($request->all());
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
    
        return response(json_encode([
            'message' => 'Usuario actualizado',
            'status' => true,
            'data' => $user
        ]), 200)->header('Content-Type', 'application/json');
    }
}
