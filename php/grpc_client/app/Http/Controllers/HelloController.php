<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google\Protobuf\Timestamp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Grpc;

class HelloController extends Controller
{
    public function hello(): View
    {
        $client = new HelloServiceClient('grpc.sacre-tech.local:9001', [
            'credentials' => Grpc\ChannelCredentials::createInsecure()
        ]);

        $request = new HelloRequest();
        $request->setName('Sata');
        $info = new ClientRequestInfo();
        $timestamp = new Timestamp();
        $dt = Carbon::now();
        $timestamp->fromDateTime($dt);
        $info->setTimestamp($timestamp);

        list($reply, $status) = $client->SayHello($request)->wait();
        if ($status->code !== Grpc\STATUS_OK) {
            return view('error.html.twig', [
                'code' => $status->code,
                'details' => $status->details
            ]);
        }
        $client->close();

        $reqTime = $reply->getResponseInfo()->getRequestTime()->getSeconds();
        $respTime = $reply->getResponseInfo()->getResponseTime()->getSeconds();
        $lang = $reply->getResponseInfo()->getLanguage();

        $elapsed = $respTime - $reqTime;

        return view('hello.html.twig', [
            'name' => $reply->getMessage(),
            'elapsed' => $elapsed,
            'lang' => $lang
        ]);
    }
}
