<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InvoiceCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Invoice Create';

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
     */
    public function handle(InvoiceCreatorService $invoiceCreatorService): int
    {
        $invoiceCreatorService->execute();
    }
}
