<?php

namespace App\Http\Controllers;


use App\ShopifyTrigger;
use App\ShopifyWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\HandwryttenApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Log;

use App\ShopifyOrder;
use App\ShopifyCustomer;

class ShopifyAppUninstallController extends Controller
{



  public function appUnistall(Request $request)
  {
    Log::info("unistall working");
    $shopDomain = request()->header('x-shopify-shop-domain');
    $expShopName =  substr($shopDomain, 0, -14);
    $tableNameCustomer = $expShopName . '_shopifycustomer';
    $tableNameOrders = $expShopName . '_shopifyorders';
    Schema::dropIfExists($tableNameCustomer);
    Schema::dropIfExists($tableNameOrders);
  }
}
