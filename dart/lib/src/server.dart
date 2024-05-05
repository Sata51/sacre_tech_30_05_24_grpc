import 'dart:io';
import 'dart:math';

import 'package:dart/gen/google/protobuf/timestamp.pb.dart';
import 'package:dart/gen/request.pb.dart';
import 'package:dart/gen/response.pb.dart';
import 'package:dart/gen/service.pbgrpc.dart';
import 'package:dart_numerics/dart_numerics.dart';
import 'package:grpc/grpc.dart' as grpc;
import 'package:grpc/service_api.dart';

class HelloService extends HelloServiceBase {
  @override
  Future<HelloResponse> sayHello(ServiceCall call, HelloRequest request) async {
    final response = HelloResponse();
    response.message = 'Hello from dart, ${request.name}!';
    response.responseInfo = ClientResponseInfo()
      ..requestTime = request.requestInfo.timestamp
      ..responseTime = Timestamp.fromDateTime(DateTime.now())
      ..language = "dart";
    return response;
  }
}

class CalculatorService extends CalculatorServiceBase {
  @override
  Future<CalculatorResponse> calculate(
      ServiceCall call, CalculatorRequest request) async {
    final response = CalculatorResponse()
      ..addition = request.a + request.b
      ..subtraction = request.a - request.b
      ..multiplication = request.a * request.b
      ..division = request.b == 0 ? 0 : request.a / request.b
      ..power = pow(request.a, request.b).toDouble()
      ..sqrtA = sqrt(request.a)
      ..sqrtB = sqrt(request.b)
      ..mod = request.b == 0 ? 0 : request.a % request.b
      ..factorialA = gamma(request.a + 1).toDouble()
      ..factorialB = gamma(request.b + 1).toDouble();

    response.responseInfo = ClientResponseInfo()
      ..requestTime = request.requestInfo.timestamp
      ..responseTime = Timestamp.fromDateTime(DateTime.now())
      ..language = "dart";

    return response;
  }
}

class Server {
  Future<void> main(List<String> args) async {
    final server = grpc.Server.create(services: [
      HelloService(),
      CalculatorService(),
    ]);
    await server.serve(port: 50051);
    print('Server listening on port ${server.port}...');

    // Handle SIGINT and SIGTERM
    ProcessSignal.sigint.watch().listen((signal) async {
      print('Received signal $signal, shutting down...');
      await server.shutdown();
    });
    ProcessSignal.sigterm.watch().listen((signal) async {
      print('Received signal $signal, shutting down...');
      await server.shutdown();
    });
  }
}
