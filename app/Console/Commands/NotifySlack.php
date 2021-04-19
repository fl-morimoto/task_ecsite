<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\SlackPost;
use App\Order;

class NotifySlack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Slackへ送信';

	private $order;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        parent::__construct();
		$this->order = $order;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$this->order->notify(new SlackPost());
    }
}
