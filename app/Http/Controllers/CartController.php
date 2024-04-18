<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{     
    
    
    public function getCarts(Request $request) {
        $userId = $request->input('userId');
      
        $carts = DB::table('carts')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->select('carts.product_id', 'products.name_en', 'products.name_ar' ,'carts.quantity','products.price')
            ->where('carts.user_id', $userId)
            ->get();
    
        return response()->json($carts);
    }

    public function addToCart(Request $request)
    {    
        $userId = $request->input('userId');
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
      
        $existingCart = DB::table('carts')
        ->where('user_id', $userId)
        ->where('product_id', $productId)
        ->first();

        if($existingCart){

            DB::table('carts')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->update([
                'quantity' => $existingCart->quantity + $quantity,
                'updated_at' => now()
            ]);
    } else {
        DB::table('carts')->insert([
            'product_id' => $productId,
            'user_id' => $userId,
            
            'quantity' => $quantity,
            'created_at' => now(), 
            'updated_at' => now() 
        ]);
    }

        return response()->json(['message' => 'Product added to cart']);
    }
    public function deleteCart($userId, $productId){
        $deletedCart = DB::table('carts')
        ->where('user_id', $userId)
        ->where('product_id', $productId)
        ->delete();

       if($deletedCart){
           return response()->json(['message' => 'Product deleted from the cart'], 200);
    } else {
              return response()->json(['error' => 'Product not found in the cart'], 404);
}
}
    }


