<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    public function getAllPedidos(Request $request)
    {
        try{
            $query = Pedido::whereNull('deleted_at');

            if ($request->has('pedido_id')) {
                $query->where('pedido_id', $request->input('pedido_id'));
            }

            if ($request->has('producto_id')) {
                $query->where('producto_id', 'like', '%' . $request->input('producto_id') . '%');
            }
            if ($request->has('user_id')) {
                $query->where('user_id', 'like', '%' . $request->input('user_id') . '%');
            }
            if ($request->has('completed')) {
                $query->where('completed', 'like', '%' . $request->input('completed') . '%');
            }
            if ($request->has('cancelled')) {
                $query->where('cancelled', 'like', '%' . $request->input('cancelled') . '%');
            }
            if ($request->has('wishlist')) {
                $query->where('wishlist', 'like', '%' . $request->input('wishlist') . '%');
            }

            $pedidos = $query->paginate(10);
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        return response(json_encode([
            'message' => 'Pedidos encontrados',
            'status' => true,
            'data' => $pedidos
        ]), 200)->header('Content-Type', 'application/json');
    }

    public function createPedido(Request $request){
        try{
            $inventarioDB = Pedido::where('pedido_id', $request->pedido_id)->first();

        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }

        if($inventarioDB){
            return response(json_encode([
                'message' => 'El pedido ya existe',
                'status' => false
            ]), 401)->header('Content-Type', 'application/json');
        }

        $validator = Validator::make($request->all(), [
            'pedido_id' => 'required|string|max:255|unique:pedidos',
            'producto_id' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'completed' => 'sometimes|boolean',
            'cancelled' => 'sometimes|boolean',
            'wishlist' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ]), 400)->header('Content-Type', 'application/json');
        }
        try{
        $pedido = Pedido::create($validator->validated());
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }

        return response(json_encode([
            'message' => 'Pedido creado exitosamente',
            'pedido' => $pedido,
            'status' => true
        ]), 201)->header('Content-Type', 'application/json');
    }

    public function updatePedido(Request $request, $id){
        $delete = $request->input('delete');
        if($request->all() == [] && $delete !== 'true'){
            return response(json_encode([
                'message' => 'No data provided for update',
                'status' => false
            ]), 400)->header('Content-Type', 'application/json');
        }
        try{
            $pedido = Pedido::find($id);
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        if(!$pedido){
            return response(json_encode([
                'message' => 'Pedido no encontrado',
                'status' => false
            ]), 404)->header('Content-Type', 'application/json');
        }
        if ($delete === 'true') {
            try{
                $pedido->delete();
            }catch(\Exception $e){
                return response(json_encode([
                    'message' => 'Error al acceder a la base de datos',
                    'data' => $e->getMessage(),
                ]), 500)->header('Content-Type', 'application/json');
            }
            return response(json_encode([
                'message' => 'Pedido eliminado',
                'status' => true
            ]), 200)->header('Content-Type', 'application/json');
        }
        try{
            $pedido->update($request->all());
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        return response(json_encode([
            'message' => 'Pedido actualizado',
            'status' => true,
            'data' => $pedido
        ]), 200)->header('Content-Type', 'application/json');
    }
}
