<?php

namespace App\Http\Controllers;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function test(){
        return response(json_encode([
            'message' => 'API is working!',
            'status' => true
        ]), 200)->header('Content-Type', 'application/json');
    }
    public function getAllProducts(Request $request)
{
    try{
        $query = Products::whereNull('deleted_at');

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('make')) {
            $query->where('make', 'like', '%' . $request->input('make') . '%');
        }
        if ($request->has('model')) {
            $query->where('model', 'like', '%' . $request->input('model') . '%');
        }
        if ($request->has('color')) {
            $query->where('color', 'like', '%' . $request->input('color') . '%');
        }
        if ($request->has('creator_user_id')) {
            $query->where('creator_user_id', $request->input('creator_user_id'));
        }
        if ($request->has('availability') && $request->input('availability') == 'true') {
            $query->where('availability', 'available');
        }

        $products = $query->paginate(10);
    }catch(\Exception $e){
        return response(json_encode([
            'message' => 'Error al acceder a la base de datos',
            'data' => $e->getMessage(),
        ]), 500)->header('Content-Type', 'application/json');
    }
    return response(json_encode([
        'message' => 'Productos encontrados',
        'status' => true,
        'data' => $products
    ]), 200)->header('Content-Type', 'application/json');
}

    public function createProduct(Request $request){
        try{
            $inventarioDB = Products::where('name', $request->name)->first();

        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        if($inventarioDB){
            return response(json_encode([
                'message' => 'Producto ya existe',
                'status' => false,
                'data' => $inventarioDB
            ]), 401)->header('Content-Type', 'application/json');
        }
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'sometimes|string|max:255',
                'category' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'img_url' => 'sometimes|string|max:255',
                'color' => 'sometimes|string|max:50',
                'make' => 'sometimes|string|max:255',
                'model' => 'sometimes|string|max:255',
                'availability' => 'required|boolean',
                'keywords' => 'sometimes|string|max:255',
                'creator_user_id' => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response(json_encode([
                    'status' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ]), 400)->header('Content-Type', 'application/json');
            }
        } catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al crear producto',
                'status' => false,
                'data' => $e->getMessage()
            ]), 500)->header('Content-Type', 'application/json');
        }
        try{
            if (!$request->has('keywords')) {
                $request->merge(['keywords' => '']);
            }
            if (!$request->has('description')) {
                $request->merge(['description' => '']);
            }
            if(!$request->has('deleted')){
                $request->merge(['deleted' => 0]);
            }
            $product = Products::create($request->all());
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al crear producto',
                'status' => false,
                'data' => $e->getMessage()
            ]), 500)->header('Content-Type', 'application/json');
        }
        return response(json_encode([
            'message' => 'Producto creado',
            'status' => true,
            'data' => $product
        ]), 201)->header('Content-Type', 'application/json');
    }

    public function getProductById($id){
        try{
            $product = Products::find($id);
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        if(!$product){
            return response(json_encode([
                'message' => 'Producto no encontrado',
                'status' => false
            ]), 404)->header('Content-Type', 'application/json');
        }
        return response(json_encode([
            'message' => 'Producto encontrado',
            'status' => true,
            'data' => $product
        ]), 200)->header('Content-Type', 'application/json');
    }

    public function updateProduct(Request $request, $id){
        $delete = $request->input('delete');
        if($request->all() == [] && $delete !== 'true'){
            return response(json_encode([
                'message' => 'No data provided for update',
                'status' => false
            ]), 400)->header('Content-Type', 'application/json');
        }
        try{
            $product = Products::find($id);
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al acceder a la base de datos',
                'data' => $e->getMessage(),
            ]), 500)->header('Content-Type', 'application/json');
        }
        if(!$product){
            return response(json_encode([
                'message' => 'Producto no encontrado',
                'status' => false
            ]), 404)->header('Content-Type', 'application/json');
        }
        if ($delete === 'true') {
            try{
                $product->delete();
            }catch(\Exception $e){
                return response(json_encode([
                    'message' => 'Error al eliminar producto',
                    'status' => false,
                    'data' => $e->getMessage()
                ]), 500)->header('Content-Type', 'application/json');
            }
            return response(json_encode([
                'message' => 'Producto eliminado',
                'status' => true,
                'data' => $product
            ]), 200)->header('Content-Type', 'application/json');
        }
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string|max:255',
                'category' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric',
                'stock' => 'sometimes|numeric',
                'img_url' => 'sometimes|string|max:255',
                'color' => 'sometimes|string|max:50',
                'make' => 'sometimes|string|max:255',
                'model' => 'sometimes|string|max:255',
                'availability' => 'sometimes|boolean',
                'keywords' => 'sometimes|string|max:255',
                'creator_user_id' => 'sometimes|integer'
            ]);
    
            if ($validator->fails()) {
                return response(json_encode([
                    'status' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ]), 400)->header('Content-Type', 'application/json');
            }
        } catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al actualizar producto',
                'status' => false,
                'data' => $e->getMessage()
            ]), 500)->header('Content-Type', 'application/json');
        }
        try{
            $product->update($request->all());
        }catch(\Exception $e){
            return response(json_encode([
                'message' => 'Error al actualizar producto',
                'status' => false,
                'data' => $e->getMessage()
            ]), 500)->header('Content-Type', 'application/json');
        }
        return response(json_encode([
            'message' => 'Producto actualizado',
            'status' => true,
            'data' => $product
        ]), 200)->header('Content-Type', 'application/json');
    }


}  
