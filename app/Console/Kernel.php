<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

use App\ShopifyTrigger;
use App\ShopifyWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\HandwryttenApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Log;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DemoCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
   
        $triggeravilable = DB::table('shopify_triggers')->where([
            ['trigger_status', '=', 1],
            ['trigger_name', '=', "Birthday"],
        ])->get();

        foreach ($triggeravilable as $trigger) {
            $userhavingbirthdaytrigger = DB::table('users')->where([
                ['id', '=',  $trigger->user_id]
            ])->first();
            $expShopName =  substr($userhavingbirthdaytrigger->name, 0, -14);
            $tableNameCustomer = $expShopName . '_shopifycustomer';
            $customers = DB::table($tableNameCustomer)->get();
            foreach ($customers as $customer) {
                $first_name = $customer->first_name;
                $cus_id = $customer->customer_id;
                $do_noteb = $customer->dob;
                $days_ago = date('Y-m-d', strtotime('-6 days', strtotime($do_noteb)));
                $day =      substr($days_ago, -2);
                $month =               substr($days_ago, 5, -3);
                $schedule->command('demo:cron', [$first_name, $cus_id,$trigger->user_id])
                 ->everyMinute();
            //    $schedule->command('demo:cron', [$first_name, $cus_id,$trigger->user_id])
            //         ->yearly($day,  $month, '00:00');
            }
        }

              
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
