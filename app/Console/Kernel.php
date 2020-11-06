<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        $orders = DB::table('shopifycustomer')->get();
        foreach ($orders as $order) {
            $first_name =$order->first_name;
            $cus_id =$order->customer_id;
            $do_noteb =$order->dob;
            $dob =trim($do_noteb,"dob: ");
            $days_ago = date('Y-m-d', strtotime('-5 days', strtotime(' $dob')));        
            $day =      substr( $days_ago, -2);
            $month =               substr( $days_ago, 5,-3);
            $schedule->command('demo:cron',[$first_name,$cus_id])
            ->everyMinute();
            // $schedule->command('demo:cron',[$first_name,$cus_id])
          //  ->yearly(   $day,  $month, '00:00');
            }      
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
