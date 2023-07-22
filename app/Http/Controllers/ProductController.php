<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getProduct()
    {

        $users = Product::all();
        return response()->json(['success' => true, 'products' => $users], 200);
    }

    public function createProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'img' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $file = $request->file('img');
        //obtenemos el nombre del archivo
        $nombre =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::disk('local')->put($nombre,  File::get($file));


        $produc = new Product();

        $produc->name = $request->name;
        $produc->description = $request->description;
        $produc->img = $nombre;

        $produc->save();

        return response()->json(['success' => true, 'product' => $produc], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'img' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $file = $request->file('img');
        //obtenemos el nombre del archivo
        $nombre =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::disk('local')->put($nombre,  File::get($file));



        $product->name = $request->name;
        $product->description = $request->description;
        $product->img = $nombre;

        $product->save();

        return response()->json(['success' => true, 'product' => $product], 201);
    }

    public function deleteProduct(Request $request, $id){
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['success'=> false,'message' => 'Producto no encontrado'], 404);
        }
    
        $product->delete();
    
        return response()->json(['success'=> true,'message' => 'Producto eliminado correctamente'], 200);
    }
}
