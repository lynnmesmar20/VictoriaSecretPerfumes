<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Jobs\SendProductNotification;

class UserController extends Controller
{
   public function login(Request $request){
   
      $user = DB::table("users")->where('username',$request->input('username'))
                                ->where('password',$request->input('password'))
                                ->first();
            

       if($user){
        DB::table('users')->where('id', $user->id)->update(['login' => 1]);
     
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
    public function logout(Request $request){
       
        $username = $request->input('username');
    
        
        $user = DB::table("users")->where('username', $username)->first();
    
        if($user){
          
            DB::table('users')->where('id', $user->id)->update(['login' => 0]);
    
            return response()->json(['message' => 'successfully logged out']);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
    public function getNotifications(Request $request){
        $userId= $request->input('userId');
        $notifications = DB::table("notification")->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }
    public function deleteNotification($productId, $userId){
        $notification = DB::table("notification")->where('product_id', $productId)->where('user_id', $userId)->delete();

       if ($notification) {
         return response()->json(['message' => 'Notification deleted successfully'] ,200);
}
      return response()->json(['message' => 'Notification not found'], 404);
    }
    public function deleteAllNotification($userId){
        $notification=DB::table('notification')->where('user_id',$userId)->delete();
        if ($notification) {
            return response()->json(['message' => 'Notifications deleted successfully'] ,200);
           }
   return response()->json(['message' => 'Notifications not found'], 404);
    }
}
