<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\ProductAdded; 
use Illuminate\Support\Facades\Http;
use App\Jobs\SendProductNotification;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getAllProducts(){
        $products = DB::table('products')->get();
        return response()->json($products);
    }
    public function deleteProduct($id){
        $existInCart = DB::table('carts')->where('product_id', $id)->exists();
        if($existInCart){
          $existInCart = DB::table('carts')->where('product_id' ,$id)->delete();
        }
        $deleted= DB::table('products')->where('id',$id)->delete();
        
    }

    public function getProducts($id)
{
   
     $products = DB::table('products')->where('id', $id)->get();
    
    return response()->json($products);
}
    
    public function addProduct(Request $request)
    {
        $validation = $request->validate([
            'user_id' => 'required|integer',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ]);
    
        $existingProduct = DB::table('products')->where('name_en', $validation['name_en'])
            ->orWhere('name_ar', $validation['name_ar'])
            ->first();
    
        if ($existingProduct) {
            return response()->json(['error' => 'Product already exists'], 444);
        }
    
        DB::table('products')->insert([
            'user_id'=>$validation['user_id'],
            'name_en' => $validation['name_en'],
            'name_ar' => $validation['name_ar'],
            'image' => $validation['image'],
            'description_en' => $validation['description_en'],
            'description_ar' => $validation['description_ar'],
            'quantity' => $validation['quantity'],
            'price' => $validation['price'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    
        $product = DB::table('products')->orderBy('created_at', 'desc')->first();

        SendProductNotification::dispatch($product->id, $product->user_id, $product->name_en,$product->name_ar);

    
        return response()->json(['message' => 'Product added successfully']);
    }
}
