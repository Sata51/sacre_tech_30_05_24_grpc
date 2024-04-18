this_dir = File.expand_path(File.dirname(__FILE__))
lib_dir = File.join(this_dir, 'gen')
$LOAD_PATH.unshift(lib_dir) unless $LOAD_PATH.include?(lib_dir)

require 'grpc'
require 'service_services_pb'


def main
  helloStub = Service::HelloService::Stub.new('localhost:50051', :this_channel_is_insecure)
  calcStub = Service::CalculatorService::Stub.new('localhost:50051', :this_channel_is_insecure)
  begin
    message = helloStub.say_hello(Service::HelloRequest.new(name: 'Sata'))
    p message.message
    response = calcStub.calculate(Service::CalculatorRequest.new(a: 10, b: 18))
    p "Addition: #{response.addition}"
    p "Subtraction: #{response.subtraction}"
    p "Multiplication: #{response.multiplication}"
    p "Division: #{response.division}"
  rescue GRPC::BadStatus => e
    abort "ERROR: #{e.message}"
  end
end

main