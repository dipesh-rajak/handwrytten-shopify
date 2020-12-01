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

class ShopifyCustomerController extends Controller
{

  public function createCustomer(Request $request)
  {
    Log::info($request);
    $shopDomain = request()->header('x-shopify-shop-domain');
    Log::info($shopDomain);
    $expShopName =  substr($shopDomain, 0, -14);
    $tableNameCustomer = $expShopName . '_shopifycustomer';
    $userdata = DB::table('users')->where([
      ['name', '=', $shopDomain]
    ])->first();
    $userid = $userdata->id;
 
    $triggeravilable = DB::table('shopify_triggers')->where([
      ['user_id', '=', $userid],
      ['trigger_status', '=', 1],
      ['trigger_name', '=', "New Registration"],
    ])->count();
    $handwryttendata = DB::table('handwrytten_apis')->where([
      ['user_id', '=', $userid],
    ])->first();

    $useremail = $handwryttendata->email;
    $pass = $handwryttendata->password;

    $string = $request->note;

    $array = array();
    $lines = explode(",", $string);

    // foreach ($lines as $line) {
    //   list($key, $value) = explode(": ", $line);
    //   $array[$key] = $value;
    // }
$dob = '1994-10-12';

    if ($triggeravilable == 1) {


      $triggerget = DB::table('shopify_triggers')->where([
        ['user_id', '=', $userid],
        ['trigger_status', '=', 1],
        ['trigger_name', '=', "New Registration"],
      ])->first();



      DB::table($tableNameCustomer)->insert([
        'customer_id'          =>  $request->id,
        'first_name'           =>  $request->first_name,
        'last_name'            =>  $request->last_name,
        'email'                =>  $request->email,
        'dob'                  =>  $dob,
        'name'                 =>  $request['shipping_address']['name'],
        'business_name'        =>  $request['shipping_address']['company'],
        'address1'             =>  $request['shipping_address']['address1'],
        'address2'             =>  $request['shipping_address']['address2'],
        'city'                 =>  $request['shipping_address']['city'],
        'province'             =>  $request['shipping_address']['province'],
        'zip'                  =>  $request['shipping_address']['zip'],
        'country'              =>  $request['shipping_address']['country_code']
      ]);




      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.handwrytten.com/v1/auth/authorization",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('login' => $useremail, 'password' => $pass),
        CURLOPT_HTTPHEADER => array(
          "Accept: application/json",
        ),
      ));

      $response = curl_exec($curl);
      $data = json_decode($response);
      $uid = $data->uid;

      Log::info($uid);
      Log::info($triggerget->trigger_handwriting_style);
     
      $credcardid = "";

      $wrapper_singlestep_order = array(
        "uid"                       => $uid,
        "card_id"                   => $triggerget->card_id,
        "denomination_id"           => "",
        "message"                   => $triggerget->trigger_message,
        "font_label"                => $triggerget->trigger_handwriting_style,
        "sender_name"               => "Randy Rose",
        "sender_business_name"      => "123456",
        "sender_address1"           => "2112 Manchester",
        "sender_address2"           => "",
        "sender_city"               => "Los Angeles",
        "sender_state"              => "CA",
        "sender_zip"                => "91111",
        "sender_country"            => "USA",
        "recipient_name"            => $request['shipping_address']['name'],
        "recipient_business_name"   => $request['shipping_address']['company'],
        "recipient_address1"        => $request['shipping_address']['address1'],
        "recipient_address2"        => $request['shipping_address']['address1'],
        "recipient_city"            => $request['shipping_address']['city'],
        "recipient_state"           => $request['shipping_address']['province'],
        "recipient_zip"             => $request['shipping_address']['zip'],
        "recipient_country"         => $request['shipping_address']['country_code'],
        "insert_id"                 => $triggerget->trigger_insert,
        "credit_card_id"            => $credcardid

      );
      $postdata = json_encode($wrapper_singlestep_order);

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://backend.handwrytten.com/api.php/api/orders/singleStepOrder",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        //CURLOPT_POSTFIELDS => $postdata,
          CURLOPT_POSTFIELDS => "{\r\n\"uid\":\"$uid\",\r\n\"card_id\":\"$triggerget->card_id\",\r\n\"denomination_id\":\"\",\r\n\"message\":\"$triggerget->trigger_message\",\r\n\"font_label\":\"$triggerget->trigger_handwriting_style\", \r\n\"sender_name\":\"Randy Rose\",\r\n\"sender_business_name\":\"123456\",\r\n\"sender_address1\":\"2112 Manchester\",\r\n\"sender_address2\":\"\",\r\n\"sender_city\":\"Los Angeles\",\r\n\"sender_state\":\"CA\",\r\n\"sender_zip\":\"91111\",\r\n\"sender_country\":\"USA\",\r\n\"recipient_name\":\"Josh Davis\",\r\n\"recipient_business_name\":\"Express Logistics and Transport\",\r\n\"recipient_address1\":\"621 SW 5th Avenue Suite 400\",\r\n\"recipient_address2\":\"\",\r\n\"recipient_city\":\"Portland\",\r\n\"recipient_state\":\"OR\",\r\n\"recipient_zip\":\"85123\",\r\n\"recipient_country\":\"USA\",\r\n\"insert_id\":\"\",\r\n\"credit_card_id\":\"$credcardid\"\r\n}",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: application/json",
          "postman-token: 5100f8af-c0f2-2691-8872-53f00bbd5fb4"
        ),
      ));

      $responseyt = curl_exec($curl);
      Log::info($responseyt);
      $err = curl_error($curl);
      curl_close($curl);
    }



  }

}
