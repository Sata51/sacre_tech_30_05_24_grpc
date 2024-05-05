this_dir = File.expand_path(File.dirname(__FILE__))
lib_dir = File.join(this_dir, 'gen')
$LOAD_PATH.unshift(lib_dir) unless $LOAD_PATH.include?(lib_dir)

require 'grpc'
require 'google/protobuf/well_known_types'
require 'service_services_pb'


def main
  helloStub = Service::HelloService::Stub.new('localhost:50051', :this_channel_is_insecure)
  calcStub = Service::CalculatorService::Stub.new('localhost:50051', :this_channel_is_insecure)
  begin
    message = helloStub.say_hello(Service::HelloRequest.new(name: 'Sata', request_info: Service::ClientRequestInfo.new(timestamp: Google::Protobuf::Timestamp.from_time(Time.now))))
    p "From language: #{message.response_info.language}"
    p message.message

    p "Elapsed time: #{message.response_info.request_time.to_time.to_i - message.response_info.response_time.to_time.to_i} ms"
    p "---------------------------------"
    response = calcStub.calculate(Service::CalculatorRequest.new(a: 10, b: 18, request_info: Service::ClientRequestInfo.new(timestamp: Google::Protobuf::Timestamp.from_time(Time.now))))

    p "From language: #{message.response_info.language}"
    p "Addition: #{response.addition}"
    p "Subtraction: #{response.subtraction}"
    p "Multiplication: #{response.multiplication}"
    p "Division: #{response.division}"
    p "Power: #{response.power}"
    p "Modulus: #{response.mod}"
    p "Square root of A: #{response.sqrtA}"
    p "Square root of B: #{response.sqrtB}"
    p "Factorial of A: #{response.factorialA}"
    p "Factorial of B: #{response.factorialB}"


    p "Elapsed time: #{response.response_info.request_time.to_time.to_i - response.response_info.response_time.to_time.to_i} ms"
    p "---------------------------------"
  rescue GRPC::BadStatus => e
    abort "ERROR: #{e.message}"
  end
end

main