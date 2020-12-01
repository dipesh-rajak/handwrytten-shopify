<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Log;

class AppUninstalledJob extends \Osiset\ShopifyApp\Messaging\Jobs\AppUninstalledJob
{
   
    public function index()
    {
        Log::info('afterinstallworking');
        $shopName = Auth::user()->name;
        $expShopName =  substr($shopName, 0, -14);
        $tableNameCustomer = $expShopName . '_shopifycustomer';
        $tableNameOrders = $expShopName . '_shopifyorders';
        Schema::dropIfExists($tableNameCustomer);
        Schema::dropIfExists($tableNameOrders);
    }
}
