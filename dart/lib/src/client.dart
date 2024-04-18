import 'package:dart/gen/request.pb.dart';
import 'package:dart/gen/service.pbgrpc.dart';
import 'package:grpc/grpc.dart';

class Client {
  late HelloServiceClient _helloServiceClient;
  late CalculatorServiceClient _calculatorServiceClient;

  Future<void> main(List<String> args) async {
    final channel = ClientChannel('127.0.0.1',
        port: 50051,
        options:
            const ChannelOptions(credentials: ChannelCredentials.insecure()));
    _helloServiceClient = HelloServiceClient(channel,
        options: CallOptions(timeout: Duration(seconds: 30)));
    _calculatorServiceClient = CalculatorServiceClient(channel,
        options: CallOptions(timeout: Duration(seconds: 30)));

    try {
      await _sayHello("Sata");
      await _calculate(10, 5);
    } catch (e) {
      print(e);
    }

    await channel.shutdown();
  }

  Future<void> _sayHello(String name) async {
    final request = HelloRequest()..name = name;
    final response = await _helloServiceClient.sayHello(request);
    print(response.message);
  }

  Future<void> _calculate(double a, double b) async {
    final request = CalculatorRequest()
      ..a = a
      ..b = b;
    final response = await _calculatorServiceClient.calculate(request);
    print("Addition: ${response.addition}");
    print("Subtraction: ${response.subtraction}");
    print("Multiplication: ${response.multiplication}");
    print("Division: ${response.division}");
  }
}
