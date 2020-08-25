<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order;
use App\Jobs\OrdersCheck;
use App\OrderRequest;
use GuzzleHttp\Client;

class OrdersParserController extends Controller
{

    /**
     * Closure method for schedule
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function parse()
    {
        $client = new Client(['verify' => false]);
        $path = env('ORDERS_PARSE_PAGE');
        if($orderRequest = OrderRequest::query()->orderByDesc('request_time')->first()){
            $path = $orderRequest->next_url;
        }
        if($path){
            $response = $client->request('GET', $path, [
                'accept' => 'application/json'
            ]);
            if($orders = Order::make(json_decode($response->getBody()->getContents()))){
                foreach ($orders->data as $order){
                    $order = Order::make($order);
                    if($order->attributes->status == 'new'){
                        OrdersCheck::dispatch($order)->onQueue('orders');
                    }
                }
                $date = new \DateTime('now');
                OrderRequest::add([
                    'url' => $path,
                    'next_url' => $orders->links->next ?? $orders->links->first,
                    'request_time' => $date->format('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
