from concurrent import futures
import logging

import grpc
from gen import  response_pb2, service_pb2_grpc


class HelloService(service_pb2_grpc.HelloServiceServicer):
    def SayHello(self, request, context):
        return response_pb2.HelloResponse(message='Hello from python, %s!' % request.name)

class CalculatorService(service_pb2_grpc.CalculatorServiceServicer):
    def Calculate(self, request, context):
        return response_pb2.CalculatorResponse(
            addition=request.a + request.b,
            subtraction=request.a - request.b,
            multiplication=request.a * request.b,
            division=request.a / request.b if request.b != 0 else 0
          )


def serve():
    port = 50051
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    service_pb2_grpc.add_HelloServiceServicer_to_server(HelloService(), server)
    service_pb2_grpc.add_CalculatorServiceServicer_to_server(CalculatorService(), server)
    server.add_insecure_port('[::]:%s' % port)
    server.start()
    print('Server started on port %s' % port)
    server.wait_for_termination()

if __name__ == '__main__':
    logging.basicConfig()
    serve()