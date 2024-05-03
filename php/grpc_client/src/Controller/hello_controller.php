<?php
// src/Controller/hello_controller.php
namespace App\Controller;

use Carbon\Carbon;
use Service\HelloServiceClient;
use Symfony\Component\HttpFoundation\Response;
use Grpc;
use Grpc\ChannelCredentials;
use Google\Protobuf\Timestamp;
use Service\ClientRequestInfo;
use Service\HelloRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
  #[Route('/hello', name: 'hello')]
  public function hello(): Response
  {
    $client = new HelloServiceClient('grpc.sacre-tech.local:9001', [
      'credentials' => ChannelCredentials::createInsecure()
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
      return $this->render('error.html.twig', [
        'code' => $status->code,
        'details' => $status->details
      ]);
    }
    $client->close();

    $reqTime = $reply->getResponseInfo()->getRequestTime()->getSeconds();
    $respTime = $reply->getResponseInfo()->getResponseTime()->getSeconds();
    $lang = $reply->getResponseInfo()->getLanguage();

    $elapsed = $respTime - $reqTime;

    return $this->render('hello.html.twig', [
      'name' => $reply->getMessage(),
      'elapsed' => $elapsed,
      'lang' => $lang
    ]);
  }
}
