<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $shopName = Auth::user()->name;       
        $expShopName =  substr($shopName,0 , -14);    
        $tableNameCustomer = $expShopName.'_shopifycustomer';
        $tableNameOrders = $expShopName.'_shopifyorders';
        Schema::dropIfExists($tableNameCustomer);
        Schema::dropIfExists($tableNameOrders);

          $output =  Schema::create($tableNameCustomer, function (Blueprint $table) {
         $table->increments('id');
         $table->string('customer_id');
         $table->string('first_name');
         $table->string('last_name')->nullable();
         $table->string('email')->nullable();
         $table->string('dob')->nullable();
         $table->string('name')->nullable();
         $table->string('business_name')->nullable();
         $table->string('address1')->nullable();
         $table->string('address2')->nullable();
         $table->string('city')->nullable();
         $table->string('province')->nullable();
         $table->string('zip')->nullable();
          $table->string('country')->nullable();            
         $table->timestamps();    
     });
     
     Schema::create($tableNameOrders, function (Blueprint $table) {
         $table->increments('id');
         $table->string('order_id')->nullable();;
         $table->integer('order_number')->nullable();
         $table->string('email')->nullable();
         $table->string('order_status_url')->nullable();
         $table->string('product_id')->nullable();
         $table->string('title')->nullable();;
         $table->string('quantity')->nullable();
         $table->string('amount')->nullable();
         $table->string('currency_code')->nullable();
         $table->string('vendor')->nullable();
         $table->string('recipient_name')->nullable();
         $table->string('recipient_business_name')->nullable();
         $table->string('recipient_address1')->nullable();
         $table->string('recipient_address2')->nullable();
         $table->string('recipient_city')->nullable();
         $table->string('recipient_province')->nullable();
         $table->string('recipient_zip')->nullable();
          $table->string('recipient_country')->nullable();            
         $table->timestamps();
     });
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
     
    }
}
