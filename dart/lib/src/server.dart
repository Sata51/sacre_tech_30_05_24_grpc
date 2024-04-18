import 'package:dart/gen/request.pb.dart';
import 'package:dart/gen/response.pb.dart';
import 'package:dart/gen/service.pbgrpc.dart';
import 'package:grpc/grpc.dart' as grpc;
import 'package:grpc/service_api.dart';

class HelloService extends HelloServiceBase {
  @override
  Future<HelloResponse> sayHello(ServiceCall call, HelloRequest request) async {
    return HelloResponse()..message = 'Hello from dart, ${request.name}!';
  }
}

class CalculatorService extends CalculatorServiceBase {
  @override
  Future<CalculatorResponse> calculate(
      ServiceCall call, CalculatorRequest request) async {
    return CalculatorResponse()
      ..addition = request.a + request.b
      ..subtraction = request.a - request.b
      ..multiplication = request.a * request.b
      ..division = request.b == 0 ? 0 : request.a / request.b;
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
  }
}
