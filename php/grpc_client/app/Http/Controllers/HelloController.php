<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google\Protobuf\Timestamp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Grpc;
use Service\ClientRequestInfo;
use Service\HelloRequest;
use Service\HelloServiceClient;

class HelloController extends Controller
{
    public function hello(): View
    {
        $client = new HelloServiceClient('grpc.sacre-tech.local:9001', [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);

        $request = new HelloRequest();
        $request->setName('Sata');
        $info = new ClientRequestInfo();
        $timestamp = new Timestamp();
        $dt = Carbon::now();
        $timestamp->fromDateTime($dt);
        $info->setTimestamp($timestamp);
        $request->setRequestInfo($info);

        list($reply, $status) = $client->SayHello($request)->wait();
        if ($status->code !== Grpc\STATUS_OK) {
            return view('error', [
                'code' => $status->code,
                'details' => $status->details
            ]);
        }
        $client->close();

        $reqTimeSec = $reply->getResponseInfo()->getRequestTime()->getSeconds();
        $reqTimeNanos = $reply->getResponseInfo()->getRequestTime()->getNanos();
        $reqTime = $reqTimeSec + $reqTimeNanos / 1e9;
        $reqTime = Carbon::createFromTimestamp($reqTime);
        $respTimeSec = $reply->getResponseInfo()->getResponseTime()->getSeconds();
        $respTimeNanos = $reply->getResponseInfo()->getResponseTime()->getNanos();
        $respTime = $respTimeSec + $respTimeNanos / 1e9;
        $respTime = Carbon::createFromTimestamp($respTime);

        $lang = $reply->getResponseInfo()->getLanguage();

        $elapsed = $respTime->diffInMilliseconds($reqTime, true);

        return view('hello', [
            'message' => $reply->getMessage(),
            'elapsed' => $elapsed,
            'lang' => $lang
        ]);
    }


    public function helloMany(int $count): View
    {
        $client = new HelloServiceClient('grpc.sacre-tech.local:9001', [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);

        // Store result in an array with the following structure:
        // [
        //      "language":   [ the time it took to process the request in milliseconds ],
        // ]
        $reqRes = array();

        for ($i = 0; $i < $count; $i++) {
            $request = new HelloRequest();
            $request->setName('Sata');
            $info = new ClientRequestInfo();
            $timestamp = new Timestamp();
            $dt = Carbon::now();
            $timestamp->fromDateTime($dt);
            $info->setTimestamp($timestamp);
            $request->setRequestInfo($info);

            list($reply, $status) = $client->SayHello($request)->wait();
            if ($status->code !== Grpc\STATUS_OK) {
                return view('error', [
                    'code' => $status->code,
                    'details' => $status->details
                ]);
            }

            $reqTimeSec = $reply->getResponseInfo()->getRequestTime()->getSeconds();
            $reqTimeNanos = $reply->getResponseInfo()->getRequestTime()->getNanos();
            $reqTime = $reqTimeSec + $reqTimeNanos / 1e9;
            $reqTime = Carbon::createFromTimestamp($reqTime);
            $respTimeSec = $reply->getResponseInfo()->getResponseTime()->getSeconds();
            $respTimeNanos = $reply->getResponseInfo()->getResponseTime()->getNanos();
            $respTime = $respTimeSec + $respTimeNanos / 1e9;
            $respTime = Carbon::createFromTimestamp($respTime);

            $lang = $reply->getResponseInfo()->getLanguage();

            $elapsed = $respTime->diffInMilliseconds($reqTime, true);

            if (!array_key_exists($lang, $reqRes)) {
                $reqRes[$lang] = array();
            }
            array_push($reqRes[$lang], $elapsed);
        }
        $client->close();

        $total = array();
        $avg = array();
        $min = array();
        $max = array();

        foreach ($reqRes as $lang => $times) {
            $total[$lang] = sizeof($times);
            $avg[$lang] = array_sum($times) / count($times);
            $min[$lang] = min($times);
            $max[$lang] = max($times);
        }

        return view('helloMany', [
            'reqRes' => $reqRes,
            'total' => $total,
            'avg' => $avg,
            'min' => $min,
            'max' => $max
        ]);
    }
}
