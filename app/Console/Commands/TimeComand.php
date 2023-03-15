<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

class TimeComand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'output:comand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displaying the current time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Displaying the current time using date: '. date('h:i:s'));


        $this->info('Displaying the current time using Carbon: '. Carbon::now());
        return Command::SUCCESS;
    }
}
