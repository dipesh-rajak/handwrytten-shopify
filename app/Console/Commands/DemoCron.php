<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron {first_name} {cus_id}';

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

        $credcardid = "";
        $user_details = DB::table('handwrytten_apis')->where([
            ['user_id', '=', 1],
          ])->first();
          $useremail = $user_details->email;
          $pass = $user_details->password;
          $uid = $user_details->uid;

     $cus_id = $this->argument('cus_id');
 $first_name = $this->argument('first_name');
      
        $triggerget = DB::table('shopify_triggers')->where([
            ['trigger_name', '=', 'Birthday'],
            ['user_id', '=', 1],
          ])->first();
          $fetch_card_id = $triggerget->card_id;
          $fetch_trigger_message = $triggerget->trigger_message;
          $fetch_trigger_signoff = $triggerget->trigger_signoff;
          $fetch_trigger_handwriting_style = $triggerget->trigger_handwriting_style;
          $fetch_trigger_insert = $triggerget->trigger_insert;
          $fetch_trigger_gift_card = $triggerget->trigger_gift_card;

    $cus_get = DB::table('shopifycustomer')->where([
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
      
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://backend.handwrytten.com/api.php/api/orders/singleStepOrder",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          
          
          CURLOPT_POSTFIELDS => "{\r\n\"uid\":\"$uid\",\r\n\"card_id\":\"$fetch_card_id\",\r\n\"denomination_id\":\"\",\r\n\"message\":\"$fetch_trigger_message\",\r\n\"font_label\":\"$fetch_trigger_handwriting_style\",\r\n\"sender_name\":\"Randy Rose\",\r\n\"sender_business_name\":\"123456\",\r\n\"sender_address1\":\"2112 Manchester\",\r\n\"sender_address2\":\"\",\r\n\"sender_city\":\"Los Angeles\",\r\n\"sender_state\":\"CA\",\r\n\"sender_zip\":\"91111\",\r\n\"sender_country\":\"USA\",\r\n\"recipient_name\":\"Josh Davis\",\r\n\"recipient_business_name\":\"Express Logistics and Transport\",\r\n\"recipient_address1\":\"621 SW 5th Avenue Suite 400\",\r\n\"recipient_address2\":\"\",\r\n\"recipient_city\":\"Portland\",\r\n\"recipient_state\":\"OR\",\r\n\"recipient_zip\":\"85123\",\r\n\"recipient_country\":\"USA\",\r\n\"insert_id\":\"\",\r\n\"credit_card_id\":\"$credcardid\"\r\n}",
          // CURLOPT_POSTFIELDS => "{\r\n\"uid\":\"$uid\",\r\n\"card_id\":\"$fetch_card_id\",\r\n\"denomination_id\":\"2\",\r\n\"message\":\"$fetch_trigger_message\",\r\n\"font_label\":\"$fetch_trigger_handwriting_style\",\r\n\"sender_name\":\"$usernameus\",\r\n\"sender_business_name\":\"123456\",\r\n\"sender_address1\":\"2112 Manchester\",\r\n\"sender_address2\":\"\",\r\n\"sender_city\":\"Los Angeles\",\r\n\"sender_state\":\"CA\",\r\n\"sender_zip\":\"91111\",\r\n\"sender_country\":\"USA\",\r\n\"recipient_name\":\"$fetch_recipient_name\",\r\n\"recipient_business_name\":\"$fetch_recipient_business_name\",\r\n\"recipient_address1\":\"$fetch_recipient_address1\",\r\n\"recipient_city\":\"$fetch_recipient_city \",\r\n\"recipient_zip\":\"$fetch_recipient_zip\",\r\n\"recipient_country\":\"$fetch_recipient_country\",\r\n\"insert_id\":\"$fetch_trigger_insert\",\r\n\"credit_card_id\":\"\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 5100f8af-c0f2-2691-8872-53f00bbd5fb4"
          ),
        ));
  
        $responseyt = curl_exec($curl);
        \Log::info($responseyt);
        $err = curl_error($curl);
        curl_close($curl);   


        $this->info('Demo:Cron Cummand Run successfully!');
    }
}
