<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google\Protobuf\Timestamp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Service\CalculatorRequest;
use Service\CalculatorServiceClient;
use Service\ClientRequestInfo;
use Grpc;

class CalcController extends Controller
{
    public function calc(): View
    {
        $client = new CalculatorServiceClient('grpc.sacre-tech.local:9001', [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);

        $request = new CalculatorRequest();
        $request->setA(10);
        $request->setB(5);
        $info = new ClientRequestInfo();
        $timestamp = new Timestamp();
        $dt = Carbon::now();
        $timestamp->fromDateTime($dt);
        $info->setTimestamp($timestamp);
        $request->setRequestInfo($info);

        list($reply, $status) = $client->Calculate($request)->wait();
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

        $elapsed = $respTime->diffInMilliseconds($reqTime, true);

        $lang = $reply->getResponseInfo()->getLanguage();

        var_dump($reply);

        return view('calc', [
            'addition' => $reply->getAddition(),
            'subtraction' => $reply->getSubtraction(),
            'multiplication' => $reply->getMultiplication(),
            'division' => $reply->getDivision(),
            'elapsed' => $elapsed,
            'lang' => $lang
        ]);
    }
}
