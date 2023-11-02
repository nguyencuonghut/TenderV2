<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;

class RunTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tender:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically change tender status to in-progress based on tender_in_progress_time';

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
        $tenders = Tender::where('status', 'Mở')->where('is_checked', true)->where('tender_in_progress_time', '<', now())->update(['status' => 'Đang diễn ra']);
        return Command::SUCCESS;
    }
}
