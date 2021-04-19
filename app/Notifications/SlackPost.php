<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Carbon\Carbon;
use App\Order;

class SlackPost extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
		//
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
	public function toSlack($notifiable)
	{
		$order = new Order;
		$yesterday = Carbon::yesterday();
		$sales = $order->dailySales($yesterday);
		return (new SlackMessage)
			->content($yesterday->toDateString() . ' の売上は ' . number_format($sales) . '円です。');
	}
}
