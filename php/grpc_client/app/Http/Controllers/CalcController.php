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
use App\Http\Resources\QueryStat as ResourcesQueryStat;
use App\Models\QueryStat as ModelsQueryStat;

class CalcController extends Controller
{
    public function calc(): View
    {
        $client = new CalculatorServiceClient('grpc.sacre-tech.local:9001', [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);

        $randomizer = new \Random\Randomizer();

        $a = $randomizer->getFloat(0, 10, \Random\IntervalBoundary::ClosedClosed);
        $b = $randomizer->getFloat(0, 10, \Random\IntervalBoundary::ClosedClosed);

        $request = new CalculatorRequest();
        $request->setA($a);
        $request->setB($b);
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

        return view('calc', [
            'a' => $a,
            'b' => $b,
            'addition' => $reply->getAddition(),
            'subtraction' => $reply->getSubtraction(),
            'multiplication' => $reply->getMultiplication(),
            'division' => $reply->getDivision(),
            'power' => $reply->getPower(),
            'mod' => $reply->getMod(),
            'sqrtA' => $reply->getSqrtA(),
            'sqrtB' => $reply->getSqrtB(),
            'factorialA' => $reply->getFactorialA(),
            'factorialB' => $reply->getFactorialB(),
            'elapsed' => $elapsed,
            'lang' => $lang
        ]);
    }

    public function calcMany(int $count): View
    {
        $client = new CalculatorServiceClient('grpc.sacre-tech.local:9001', [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);

        $randomizer = new \Random\Randomizer();

        // Store result in an array of arrays with shape:
        // [
        //     'language' => [
        //         'duration' => int,
        //         'request_time' => Carbon,
        //         'language' => string
        //     ]
        // ]

        $reqRes = array();

        for ($i = 0; $i < $count; $i++) {
            $request = new CalculatorRequest();
            $request->setA($randomizer->getFloat(0, 10, \Random\IntervalBoundary::ClosedClosed)); // random number
            $request->setB($randomizer->getFloat(0, 10, \Random\IntervalBoundary::ClosedClosed)); // random number
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

            $reqTimeSec = $reply->getResponseInfo()->getRequestTime()->getSeconds();
            $reqTimeNanos = $reply->getResponseInfo()->getRequestTime()->getNanos();
            $reqTime = $reqTimeSec + $reqTimeNanos / 1e9;
            $reqTime = Carbon::createFromTimestamp($reqTime);
            $respTimeSec = $reply->getResponseInfo()->getResponseTime()->getSeconds();
            $respTimeNanos = $reply->getResponseInfo()->getResponseTime()->getNanos();
            $respTime = $respTimeSec + $respTimeNanos / 1e9;
            $respTime = Carbon::createFromTimestamp($respTime);

            $elapsed =

                $lang = $reply->getResponseInfo()->getLanguage();

            $mqs = new ModelsQueryStat();
            $mqs->duration = $respTime->diffInMicroseconds($reqTime, true);;
            $mqs->requestTime = $reqTime->getPreciseTimestamp(6);
            $mqs->language = $lang;

            $qs = new ResourcesQueryStat($mqs);

            if (!array_key_exists($lang, $reqRes)) {
                $reqRes[$lang] = array();
            }
            array_push($reqRes[$lang], $qs);
        }
        $client->close();

        return view('queryStats', [
            'reqRes' => $reqRes,
        ]);
    }
}
