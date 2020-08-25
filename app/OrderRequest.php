<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class OrderRequest extends Model
{
    public $timestamps = false;

    protected $table = 'requests';

    protected $fillable = [
        'url', 'next_url', 'request_time'
    ];

    public static function add(array $data)
    {
        $order = new self(self::validator($data)->validate());
        return ($order->save()) ? $order : false;
    }

    protected static function validator(array $data)
    {
        return Validator::make($data, [
            'url' => ['required', 'string'],
            'next_url' => ['required', 'string'],
            'request_time' => ['required', 'date_format:Y-m-d H:i:s']
        ]);
    }
}
