<?php

namespace App\Console\Commands;

use App\Models\Tender;
use Illuminate\Console\Command;

class UpdateTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tender:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update the Tender status to new status when the tender_end_time is gone';

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
        $tenders = Tender::where('status', 'Đang diễn ra')->where('tender_end_time', '<', now())->update(['status' => 'Đang kiểm tra']);
        return Command::SUCCESS;
    }
}
