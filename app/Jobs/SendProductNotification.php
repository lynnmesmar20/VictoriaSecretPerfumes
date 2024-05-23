<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SendProductNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
     public $productId;
     public $userId;
     public $nameEn;
     public $nameAr;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productId ,$userId , $nameEn , $nameAr)
    {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->nameEn = $nameEn;
        $this->nameAr = $nameAr;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        $productData= [
            'productId' => $this->productId,
            'userId' => $this->userId,
            'nameEn' =>$this->nameEn,
            'nameAr'=>$this->nameAr
        ];
       
       
        $response =  Http::post('http://localhost:6001/product-channel', [
            'productData' =>   $productData 
        ]);
       
        if ($response->successful()) {
            
            
              $users = DB::table('users')->where('id', '!=', $this->userId)->get();
               logger("add to database");
                foreach ($users as $user) {
                    logger('Users exist');
                    DB::table('notification')->insert([
                        'user_id' =>$user->id,
                        'product_id' => $this->productId,
                        'name_en' => $this->nameEn,
                        'name_ar'=>$this->nameAr,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            
            
        } else {
            
            logger('Failed to send product notification.');
        }
    }
}
