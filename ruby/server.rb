this_dir = File.expand_path(File.dirname(__FILE__))
lib_dir = File.join(this_dir, 'gen')
$LOAD_PATH.unshift(lib_dir) unless $LOAD_PATH.include?(lib_dir)

require 'grpc'
require 'service_services_pb'
require 'response_pb'

class HelloServer < Service::HelloService::Service
  def say_hello(hello_req, _unused_call)
    Service::HelloResponse.new(message: "Hello from Ruby, #{hello_req.name}!")
  end
end

class CalculatorServer < Service::CalculatorService::Service
  def calculate(calc_req, _unused_call)
    Service::CalculatorResponse.new(
      addition: calc_req.a + calc_req.b,
      subtraction: calc_req.a - calc_req.b,
      multiplication: calc_req.a * calc_req.b,
      division: calc_req.b != 0 ? calc_req.a / calc_req.b : 0
    )
  end
end

def main
  s = GRPC::RpcServer.new
  s.add_http2_port('0.0.0.0:50051', :this_port_is_insecure)
  s.handle(HelloServer)
  s.handle(CalculatorServer)
  s.run_till_terminated_or_interrupted([1, 'int', 'SIGQUIT'])
end

main
