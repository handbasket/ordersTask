<?php

namespace App\Jobs;

use App\Http\Resources\Order;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class OrdersCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $client = new Client(['verify' => false,
            'headers' => [
                'accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);
        $response = $client->request('PUT', $this->order->links->self, [
            'json' => ['status' => 'processed']
        ]);
    }
}
