import 'package:dart/gen/google/protobuf/timestamp.pb.dart';
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
    final info = ClientRequestInfo()
      ..timestamp = Timestamp.fromDateTime(DateTime.now());
    final request = HelloRequest()
      ..name = name
      ..requestInfo = info;
    final response = await _helloServiceClient.sayHello(request);

    print("From language: ${response.responseInfo.language}");
    print(response.message);
    print(
        'Elapsed time: ${response.responseInfo.responseTime.toDateTime().millisecondsSinceEpoch - response.responseInfo.requestTime.toDateTime().millisecondsSinceEpoch}ms');
  }

  Future<void> _calculate(double a, double b) async {
    final info = ClientRequestInfo()
      ..timestamp = Timestamp.fromDateTime(DateTime.now());
    final request = CalculatorRequest()
      ..a = a
      ..b = b
      ..requestInfo = info;
    final response = await _calculatorServiceClient.calculate(request);

    print("From language: ${response.responseInfo.language}");

    print("Addition: ${response.addition}");
    print("Subtraction: ${response.subtraction}");
    print("Multiplication: ${response.multiplication}");
    print("Division: ${response.division}");
    print("Power: ${response.power}");
    print("Modulus: ${response.mod}");
    print("Square root of A: ${response.sqrtA}");
    print("Square root of B: ${response.sqrtB}");
    print("Factorial of A: ${response.factorialA}");
    print("Factorial of B: ${response.factorialB}");

    print(
        'Elapsed time: ${response.responseInfo.responseTime.toDateTime().millisecondsSinceEpoch - response.responseInfo.requestTime.toDateTime().millisecondsSinceEpoch}ms');
  }
}
