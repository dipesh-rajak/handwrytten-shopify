<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\HandwryttenApi;
class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron {first_name} {cus_id} {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $cus_id = $this->argument('cus_id');
      $first_name = $this->argument('first_name');
      $user_id = $this->argument('user_id');
           
        $credcardid = "";
        $user_details = DB::table('handwrytten_apis')->where([
            ['user_id', '=', $user_id],
          ])->first();
          $useremail = $user_details->email;
          $pass = $user_details->password;
          $uid = $user_details->uid;
 
        $triggerget = DB::table('shopify_triggers')->where([
            ['trigger_name', '=', 'Birthday'],
            ['user_id', '=', $user_id],
          ])->first();
          $fetch_card_id = $triggerget->card_id;
          $fetch_trigger_message = $triggerget->trigger_message;
          $fetch_trigger_signoff = $triggerget->trigger_signoff;
          $fetch_trigger_handwriting_style = $triggerget->trigger_handwriting_style;
          $fetch_trigger_insert = $triggerget->trigger_insert;
          $fetch_trigger_gift_card = $triggerget->trigger_gift_card;

          $userhavingbirthdaytrigger = DB::table('users')->where([
            ['id', '=',  $user_id]
        ])->first();
        $expShopName =  substr($userhavingbirthdaytrigger->name, 0, -14);
        $tableNameCustomer = $expShopName . '_shopifycustomer';

    $cus_get = DB::table( $tableNameCustomer)->where([
                ['customer_id', '=', $cus_id]
              ])->first();
              $do_noteb =$cus_get->dob;
              $dob =trim($do_noteb,"dob: ");
        $day =      substr( $dob, -2);
        $month =               substr( $dob, 5,-3);
      
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */
        $credcardid = "";
            
  //  $shop = $user_details;   
  //  //   $allwebhook = $shop->api()->rest('get', '/admin/api/2020-10/webhooks.json');
  //   $request = $shop->api()->rest('get', '/admin/api/2020-10/customers/4312065802398.json')['body']['customer'];
  //     return  $request;

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
          "recipient_name"            => $cus_get->name,
          "recipient_business_name"   => $cus_get->company,
          "recipient_address1"        => $cus_get->address1,
          "recipient_address2"        => $cus_get->address2,
          "recipient_city"            => $cus_get->city,
          "recipient_state"           => $cus_get->province,
          "recipient_zip"             => $cus_get->zip,
          "recipient_country"         => $cus_get->country_code,
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
         CURLOPT_POSTFIELDS => $postdata,
         //  CURLOPT_POSTFIELDS => "{\r\n\"uid\":\"$uid\",\r\n\"card_id\":\"$triggerget->card_id\",\r\n\"denomination_id\":\"\",\r\n\"message\":\"$triggerget->trigger_message\",\r\n\"font_label\":\"$triggerget->trigger_handwriting_style\", \r\n\"sender_name\":\"Randy Rose\",\r\n\"sender_business_name\":\"123456\",\r\n\"sender_address1\":\"2112 Manchester\",\r\n\"sender_address2\":\"\",\r\n\"sender_city\":\"Los Angeles\",\r\n\"sender_state\":\"CA\",\r\n\"sender_zip\":\"91111\",\r\n\"sender_country\":\"USA\",\r\n\"recipient_name\":\"Josh Davis\",\r\n\"recipient_business_name\":\"Express Logistics and Transport\",\r\n\"recipient_address1\":\"621 SW 5th Avenue Suite 400\",\r\n\"recipient_address2\":\"\",\r\n\"recipient_city\":\"Portland\",\r\n\"recipient_state\":\"OR\",\r\n\"recipient_zip\":\"85123\",\r\n\"recipient_country\":\"USA\",\r\n\"insert_id\":\"\",\r\n\"credit_card_id\":\"$credcardid\"\r\n}",
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


        $this->info('Demo:Cron Cummand Run successfully!');
    }
}
