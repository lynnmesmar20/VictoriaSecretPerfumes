<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
   public function login(Request $request){
    
      $user = DB::table("users")->where('username',$request->input('username'))
                                ->where('password',$request->input('password'))
                                ->first();
            

       if($user){
         $token = $this->generateToken();
          return response()->json(['message'=>'successfully login ' ,'id'=> $user->id , 'token' => $token]);
       }
       else{
        return response()->json(['error' => 'Invalid Username or password' ] ,422);
       }

   }

   private function generateToken()
    {
        return Str::random(40); 
    }

    public function getUsers(){
        $users = DB::table('users')->get();
        return response()->json($users);
    }
}
